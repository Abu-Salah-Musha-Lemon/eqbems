<?php

use App\Models\Blueprint;
use App\Models\Exam;
use App\Models\Institution;
use App\Models\Question;
use App\Models\QuestionVersion;
use App\Models\Result;
use App\Models\ResultRevision;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->seed();
});

function domainUser(string $role, ?Institution $institution = null): User
{
    $user = User::factory()->create(['institution_id' => $institution?->id]);
    $user->assignRole($role);
    return $user;
}
function domainInstitution(string $code): Institution
{
    return Institution::create(['code'=>$code,'name'=>'Institution '.$code,'country'=>'Bangladesh','status'=>true]);
}
function domainSubject(Institution $institution): Subject
{
    return Subject::create(['institution_id'=>$institution->id,'code'=>'S'.strtoupper(substr($institution->code,0,15)),'name'=>'Computer Science']);
}
function domainQuestionData(Subject $subject): array
{
    return [
        'subject_id'=>$subject->id, 'type'=>'mcq','difficulty'=>'easy',
        'body'=>'Which option has the correct answer?', 'marks'=>'10.00',
        'option_a'=>'Correct choice', 'option_b'=>'Wrong choice',
        'option_c'=>'Another wrong choice','option_d'=>'Last wrong choice',
        'answer_text'=>'A',
    ];
}
function approvedDomainQuestion($test, Subject $subject, User $setter, User $moderator): Question
{
    $test->actingAs($setter)->post(route('questions.store'), domainQuestionData($subject))->assertRedirect();
    $question = Question::latest('id')->firstOrFail();
    $test->actingAs($setter)->post(route('questions.submit',$question))->assertRedirect();
    $test->actingAs($moderator)->post(route('questions.review',$question),['decision'=>'approved'])->assertRedirect();
    return $question->fresh();
}

test('permission seeding is repeatable and institutions generate UUIDs', function () {
    $before = DB::table('permissions')->count();
    $this->seed();
    expect(DB::table('permissions')->count())->toBe($before);
    $institution = domainInstitution('AIUB');
    expect($institution->uuid)->toBeString()->toHaveLength(36)
        ->and($institution->id)->toBeInt();
});

test('users are isolated by institution and cannot bypass authorization via URLs', function () {
    $a = domainInstitution('A'); $b = domainInstitution('B');
    $subjectB = domainSubject($b);
    $teacher = domainUser('Institution Admin',$a);
    $this->actingAs($teacher)->get(route('subjects.show',$subjectB))->assertForbidden();
    $this->actingAs($teacher)->post(route('subjects.store'),[
        'institution_id'=>$b->id,'code'=>'AB','name'=>'Malicious subject',
    ])->assertForbidden();
    $this->actingAs($teacher)->get(route('institutions.show',$b))->assertForbidden();
    $this->actingAs($teacher)->get(route('users.index'))->assertForbidden();
});

test('setter submits a question but cannot self-approve it', function () {
    $institution = domainInstitution('ONE'); $subject = domainSubject($institution);
    $setter = domainUser('Question Setter',$institution);
    $moderator = domainUser('Moderator',$institution);
    $question = approvedDomainQuestion($this,$subject,$setter,$moderator);
    expect($question->latestVersion->status)->toBe('approved');
    $this->actingAs($setter)->post(route('questions.revise',$question))->assertRedirect();
    $this->actingAs($setter)->post(route('questions.submit',$question))->assertRedirect();
    $this->actingAs($setter)->post(route('questions.review',$question),['decision'=>'approved'])->assertForbidden();
    expect($question->fresh()->latestVersion->status)->toBe('submitted');
});

test('an approved paper records immutable snapshots and excludes unapproved questions', function () {
    $institution = domainInstitution('EXM'); $subject = domainSubject($institution);
    $setter = domainUser('Question Setter',$institution);
    $moderator = domainUser('Moderator',$institution);
    $examiner = domainUser('Exam Controller',$institution);
    $student = domainUser('Student',$institution);
    $bp = Blueprint::create(['institution_id'=>$institution->id,'subject_id'=>$subject->id,'title'=>'First paper','question_count'=>2,'duration_minutes'=>30]);
    $this->actingAs($examiner)->post(route('exams.generate',$bp))->assertSessionHasErrors('blueprint');
    $question = approvedDomainQuestion($this,$subject,$setter,$moderator);
    $bp->update(['question_count'=>1]);
    $this->actingAs($examiner)->post(route('exams.generate',$bp))->assertRedirect();
    $exam = Exam::firstOrFail();
    expect($exam->questions()->count())->toBe(1)->and($exam->total_marks)->toBe('10.00');
    $this->actingAs($examiner)->post(route('exams.issue',$exam))->assertRedirect();
    $this->actingAs($setter)->post(route('questions.revise',$question))->assertRedirect();
    $this->actingAs($setter)->put(route('questions.update',$question), array_replace(domainQuestionData($subject),['body'=>'A completely revised question body']))->assertRedirect();
    expect($exam->fresh()->questions()->first()->content_snapshot['body'])->toBe('Which option has the correct answer?');
    $this->actingAs($student)->get(route('exams.answers',$exam))->assertForbidden();
    $this->actingAs($student)->get(route('questions.show',$question))->assertForbidden();
});

test('results are validated, released privately and published corrections are audited', function () {
    $institution = domainInstitution('RES'); $subject = domainSubject($institution);
    $setter = domainUser('Question Setter',$institution);
    $moderator = domainUser('Moderator',$institution);
    $examiner = domainUser('Exam Controller',$institution);
    $student = domainUser('Student',$institution);
    $otherStudent = domainUser('Student',$institution);
    approvedDomainQuestion($this,$subject,$setter,$moderator);
    $bp = Blueprint::create(['institution_id'=>$institution->id,'subject_id'=>$subject->id,'title'=>'Grade test','question_count'=>1,'duration_minutes'=>45]);
    $this->actingAs($examiner)->post(route('exams.generate',$bp))->assertRedirect();
    $exam=Exam::firstOrFail();
    $this->actingAs($examiner)->post(route('exams.issue',$exam))->assertRedirect();
    $this->actingAs($examiner)->post(route('results.store'),['exam_id'=>$exam->id,'student_id'=>$student->id,'score'=>'11.00'])->assertSessionHasErrors('score');
    $this->actingAs($examiner)->post(route('results.store'),['exam_id'=>$exam->id,'student_id'=>$student->id,'score'=>'7.99'])->assertRedirect();
    $result=Result::firstOrFail();
    expect($result->grade)->toBe('B');
    $this->actingAs($student)->get(route('results.show',$result))->assertForbidden();
    $this->actingAs($examiner)->post(route('results.publish',$result))->assertRedirect();
    $this->actingAs($student)->get(route('results.show',$result))->assertOk();
    $this->actingAs($otherStudent)->get(route('results.show',$result))->assertForbidden();
    $this->actingAs($examiner)->put(route('results.update',$result),['score'=>'8.00'])->assertSessionHasErrors('reason');
    $this->actingAs($examiner)->put(route('results.update',$result),['score'=>'8.00','reason'=>'Validated recheck'])->assertRedirect();
    expect($result->fresh()->grade)->toBe('A')->and(ResultRevision::count())->toBe(1);
});

test('even a Super Admin cannot approve a question they authored', function () {
    $institution = domainInstitution('OWN');
    $subject = domainSubject($institution);
    $admin = domainUser('Super Admin');
    $this->actingAs($admin)->post(route('questions.store'),domainQuestionData($subject))->assertRedirect();
    $question = Question::firstOrFail();
    $this->actingAs($admin)->post(route('questions.submit',$question))->assertRedirect();
    $this->actingAs($admin)->post(route('questions.review',$question),['decision'=>'approved'])->assertForbidden();
});
