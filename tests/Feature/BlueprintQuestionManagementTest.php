<?php

use App\Models\Blueprint;
use App\Models\Exam;
use App\Models\Institution;
use App\Models\Question;
use App\Models\QuestionVersion;
use App\Models\Subject;
use App\Models\User;

beforeEach(function () {
    $this->seed();
});

function questionManagerFixture(): array
{
    $institution = Institution::factory()->create();
    $subject = Subject::factory()->create(['institution_id' => $institution->id]);
    $examiner = User::factory()->create(['institution_id' => $institution->id]);
    $examiner->assignRole('Exam Controller');
    $setter = User::factory()->create(['institution_id' => $institution->id]);
    $setter->assignRole('Question Setter');
    $moderator = User::factory()->create(['institution_id' => $institution->id]);
    $moderator->assignRole('Moderator');
    $blueprint = Blueprint::create([
        'institution_id' => $institution->id,
        'subject_id' => $subject->id,
        'title' => 'ICT MCQ',
        'question_count' => 2,
        'duration_minutes' => 60,
        'type' => 'mcq',
        'difficulty' => 'easy',
        'format' => 'standard',
        'created_by' => $examiner->id,
    ]);
    return compact('institution', 'subject', 'examiner', 'setter', 'moderator', 'blueprint');
}

function makeBlueprintVersion(Subject $subject, User $author, string $status = 'approved', array $extra = []): Question
{
    $q = Question::factory()->create([
        'institution_id' => $subject->institution_id,
        'subject_id' => $subject->id,
        'topic_id' => null,
        'author_id' => $author->id,
    ]);
    QuestionVersion::factory()->for($q)->create(array_merge([
        'version_number' => 1,
        'type' => 'mcq', 'difficulty' => 'easy', 'format' => 'standard',
        'status' => $status, 'created_by' => $author->id,
    ], $extra));
    return $q;
}

test('blueprint screen exposes create submit review and selection actions', function () {
    $d = questionManagerFixture();
    $q = makeBlueprintVersion($d['subject'], $d['setter'], 'submitted');
    $this->actingAs($d['examiner'])->get(route('blueprints.index'))
        ->assertOk()->assertSee('Manage questions');
    $this->actingAs($d['examiner'])->get(route('blueprints.questions.index', $d['blueprint']))
        ->assertOk()->assertSee('Add new question')->assertSee('Approve');
    $this->actingAs($d['setter'])->get(route('blueprints.questions.index', $d['blueprint']))
        ->assertForbidden(); // A setter can create questions but cannot manage the blueprint.
    $this->actingAs($d['moderator'])->get(route('blueprints.questions.index', $d['blueprint']))
        ->assertOk()->assertSee('Approve');
    $student = User::factory()->create(['institution_id' => $d['institution']->id]);
    $student->assignRole('Student');
    $this->actingAs($student)->get(route('blueprints.questions.index', $d['blueprint']))->assertForbidden();
    $this->actingAs($d['examiner'])->post(route('questions.review', $q), ['decision' => 'approved'])
        ->assertRedirect();
    expect($q->fresh()->latestVersion->status)->toBe('approved');
});

test('question created from blueprint is preselected and requires independent approval', function () {
    $d = questionManagerFixture();
    // Exam Controller can create because the role has question.create, and cannot self-approve.
    $this->actingAs($d['examiner'])->get(route('questions.create', ['blueprint_id' => $d['blueprint']->id]))
        ->assertOk()->assertSee('Creating a draft for blueprint');
    $this->actingAs($d['examiner'])->post(route('questions.store'), [
        'blueprint_id' => $d['blueprint']->id,
        'subject_id' => $d['subject']->id,
        'type' => 'mcq', 'format' => 'standard', 'difficulty' => 'easy',
        'body' => 'What does CPU stand for?', 'marks' => '1.00',
        'option_a' => 'Central Processing Unit', 'option_b' => 'Control Print Unit',
        'option_c' => 'Compute Process Utility', 'option_d' => 'Central Power Unit',
        'answer_text' => 'A',
    ])->assertRedirect(route('blueprints.questions.index', $d['blueprint']));
    $question = Question::latest('id')->firstOrFail();
    expect($question->latestVersion->status)->toBe('draft');
    $this->actingAs($d['examiner'])->post(route('questions.submit', $question))->assertRedirect();
    $this->actingAs($d['examiner'])->post(route('questions.review', $question), ['decision' => 'approved'])->assertForbidden();
    $this->actingAs($d['moderator'])->post(route('questions.review', $question), ['decision' => 'approved'])->assertRedirect();
    $this->actingAs($d['examiner'])->post(route('blueprints.questions.attach', [$d['blueprint'], $question]))->assertRedirect();
    expect($d['blueprint']->selectedQuestions()->count())->toBe(1);
});

test('manually selected approved questions are guaranteed in generated papers', function () {
    $d = questionManagerFixture();
    $q1 = makeBlueprintVersion($d['subject'], $d['setter']);
    $q2 = makeBlueprintVersion($d['subject'], $d['setter']);
    $q3 = makeBlueprintVersion($d['subject'], $d['setter']);
    $this->actingAs($d['examiner'])->post(route('blueprints.questions.attach', [$d['blueprint'], $q1]))->assertRedirect();
    $this->actingAs($d['examiner'])->post(route('blueprints.questions.attach', [$d['blueprint'], $q1]))->assertRedirect();
    expect($d['blueprint']->selectedQuestions()->count())->toBe(1);
    $this->actingAs($d['examiner'])->post(route('exams.generate', $d['blueprint']))->assertRedirect();
    $exam = Exam::firstOrFail();
    expect($exam->questions()->count())->toBe(2)
        ->and($exam->questions()->pluck('question_version_id')->contains($q1->latestVersion->id))->toBeTrue();
    $this->actingAs($d['examiner'])->post(route('blueprints.questions.attach', [$d['blueprint'], $q2]))->assertRedirect();
    $this->actingAs($d['examiner'])->post(route('blueprints.questions.attach', [$d['blueprint'], $q3]))->assertSessionHasErrors('question');
    expect($d['blueprint']->selectedQuestions()->count())->toBe(2);
});

test('cannot attach draft or mismatched or cross institution question', function () {
    $d = questionManagerFixture();
    $draft = makeBlueprintVersion($d['subject'], $d['setter'], 'draft');
    $wrongDifficulty = makeBlueprintVersion($d['subject'], $d['setter'], 'approved', ['difficulty' => 'hard']);
    $this->actingAs($d['examiner'])->post(route('blueprints.questions.attach', [$d['blueprint'], $draft]))->assertSessionHasErrors('question');
    $this->actingAs($d['examiner'])->post(route('blueprints.questions.attach', [$d['blueprint'], $wrongDifficulty]))->assertSessionHasErrors('question');
    $other = Institution::factory()->create();
    $foreignSubject = Subject::factory()->create(['institution_id' => $other->id]);
    $foreign = makeBlueprintVersion($foreignSubject, $d['setter']);
    $this->actingAs($d['examiner'])->post(route('blueprints.questions.attach', [$d['blueprint'], $foreign]))->assertForbidden();
});

test('a pinned question survives a newer draft and cannot be discarded by filter edits', function () {
    $d = questionManagerFixture();
    $q = makeBlueprintVersion($d['subject'], $d['setter']);
    $this->actingAs($d['examiner'])->post(route('blueprints.questions.attach', [$d['blueprint'], $q]))->assertRedirect();
    makeBlueprintVersion($d['subject'], $d['setter']);
    QuestionVersion::factory()->for($q)->create([
        'version_number' => 2, 'type' => 'short',
        'format' => 'creative', 'difficulty' => 'hard', 'status' => 'draft',
    ]);
    $this->actingAs($d['examiner'])->post(route('exams.generate', $d['blueprint']))->assertRedirect();
    expect(Exam::firstOrFail()->questions()->pluck('question_version_id')
        ->contains($q->versions()->where('status', 'approved')->value('id')))->toBeTrue();
    $this->actingAs($d['examiner'])->put(route('blueprints.update', $d['blueprint']), [
        'subject_id' => $d['subject']->id, 'title' => 'Changed filters',
        'question_count' => 2, 'duration_minutes' => 60,
        'type' => 'short', 'difficulty' => 'hard', 'format' => 'creative',
    ])->assertSessionHasErrors('filters');
});
