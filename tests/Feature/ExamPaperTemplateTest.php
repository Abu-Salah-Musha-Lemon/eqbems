<?php

use App\Models\Blueprint;
use App\Models\Exam;
use App\Models\Institution;
use App\Models\Question;
use App\Models\QuestionVersion;
use App\Models\Result;
use App\Models\Subject;
use App\Models\User;
use App\Services\ExamGenerationService;

beforeEach(function () { $this->seed(); });

function paperSetup(bool $creative = false): array
{
    $institution=Institution::factory()->create(['code'=>'PAPER'.strtoupper(fake()->unique()->lexify('???'))]);
    $subject=Subject::factory()->create(['institution_id'=>$institution->id,'code'=>'PTEST']);
    $setter=User::factory()->create(['institution_id'=>$institution->id]); $setter->assignRole('Question Setter');
    $controller=User::factory()->create(['institution_id'=>$institution->id]); $controller->assignRole('Exam Controller');
    $teacher=User::factory()->create(['institution_id'=>$institution->id]); $teacher->assignRole('Teacher');
    $student=User::factory()->create(['institution_id'=>$institution->id]); $student->assignRole('Student');
    $q=Question::factory()->create(['institution_id'=>$institution->id,'subject_id'=>$subject->id,'topic_id'=>null]);
    $version=QuestionVersion::factory()->for($q)->approvedDemo();
    $version=$creative ? $version->creative()->create() : $version->create();
    $bp=Blueprint::create(['institution_id'=>$institution->id,'subject_id'=>$subject->id,'title'=>'Template assessment',
        'question_count'=>1,'duration_minutes'=>30]);
    $exam=app(ExamGenerationService::class)->generate($bp,$controller);
    $exam->update(['status'=>'issued','issued_by'=>$controller->id,'issued_at'=>now(),
        'online_enabled'=>true,'exam_label'=>'Second Term Examination 2026','class_label'=>'HSC']);
    return compact('institution','subject','setter','controller','teacher','student','exam','q','version');
}

test('print view uses board style and hides correct answers from students',function(){
    $d=paperSetup(true);
    $this->actingAs($d['controller'])->get(route('exams.print',$d['exam']))->assertOk()
        ->assertSee('উদ্দীপক')->assertSee('Second Term Examination 2026')->assertSee('Full Marks')
        ->assertDontSee($d['version']->answer_text);
    $this->actingAs($d['student'])->get(route('exams.print',$d['exam']))->assertForbidden();
});

test('student MCQ attempt is scored, private until published and restricted by institution',function(){
    $d=paperSetup();
    $outsideInstitution=Institution::factory()->create(['code'=>'FOREIGNTEST']);
    $outside=User::factory()->create(['institution_id'=>$outsideInstitution->id]);$outside->assignRole('Student');
    $this->actingAs($outside)->post(route('online-exams.start',$d['exam']))->assertForbidden();
    $this->actingAs($d['student'])->post(route('online-exams.start',$d['exam']))->assertRedirect();
    $this->actingAs($d['student'])->get(route('online-exams.take',$d['exam']))->assertOk()
        ->assertDontSee('Model answer (staff only)');
    $item=$d['exam']->questions()->first();
    $this->actingAs($d['student'])->post(route('online-exams.submit',$d['exam']),[
        'responses'=>[$item->id=>$d['version']->answer_text],
    ])->assertRedirect();
    expect(Result::where('exam_id',$d['exam']->id)->where('student_id',$d['student']->id)->firstOrFail()->score)->toBe('1.00');
    $this->actingAs($d['student'])->post(route('online-exams.submit',$d['exam']))->assertStatus(409);
});

test('creative answers use the four part rubric and require manual marking',function(){
    $d=paperSetup(true);
    $q=$d['exam']->questions()->first();
    $this->actingAs($d['student'])->post(route('online-exams.start',$d['exam']))->assertRedirect();
    $this->actingAs($d['student'])->post(route('online-exams.submit',$d['exam']),[
        'responses'=>[$q->id=>['ক'=>'answer A','খ'=>'answer B','গ'=>'answer C','ঘ'=>'answer D']],
    ])->assertRedirect();
    expect(Result::count())->toBe(0);
    $attempt=$d['exam']->onlineAttempts()->firstOrFail();
    expect($attempt->status)->toBe('submitted');
    $this->actingAs($d['teacher'])->post(route('online-attempts.grade.store',$attempt),[
        'marks'=>[$q->id=>['ক'=>'1','খ'=>'2','গ'=>'3','ঘ'=>'4']],
    ])->assertRedirect();
    expect($attempt->fresh()->status)->toBe('graded');
    expect(Result::firstOrFail()->score)->toBe('10.00');
    expect($attempt->answers()->firstOrFail()->awarded_parts['ঘ'])->toBe('4.00');
});
