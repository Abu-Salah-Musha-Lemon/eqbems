<?php

use App\Models\Institution;
use App\Models\Question;
use App\Models\QuestionVersion;
use App\Models\Subject;
use App\Models\Topic;
use Database\Seeders\QuestionSeeder;
use Database\Seeders\SubjectSeeder;

// RefreshDatabase and the Laravel TestCase are configured in tests/Pest.php.
test('subject seeder creates a demo institution and five distinct subjects', function () {
    $this->seed(SubjectSeeder::class);

    $institution = Institution::where('code', SubjectSeeder::DEFAULT_INSTITUTION_CODE)->firstOrFail();
    expect(Subject::where('institution_id', $institution->id)->count())->toBe(5)
        ->and(Topic::whereIn('subject_id', Subject::where('institution_id', $institution->id)->pluck('id'))->count())->toBe(20);
});

test('question seeder makes 40 valid, pre-approved demo questions and is repeatable', function () {
    $this->seed(QuestionSeeder::class);

    expect(Question::count())->toBe(40)
        ->and(QuestionVersion::count())->toBe(40)
        ->and(QuestionVersion::where('type', 'mcq')->count())->toBe(20)
        ->and(QuestionVersion::where('type', 'true_false')->count())->toBe(10)
        ->and(QuestionVersion::where('type', 'short')->count())->toBe(10)
        ->and(QuestionVersion::where('status', 'approved')->count())->toBe(40);

    $mcq = QuestionVersion::where('type', 'mcq')->firstOrFail();
    expect(array_keys($mcq->options))->toBe(['A', 'B', 'C', 'D']);

    // Calling directly re-runs SubjectSeeder. Both must be idempotent.
    $this->seed(QuestionSeeder::class);
    expect(Question::count())->toBe(40)
        ->and(QuestionVersion::count())->toBe(40)
        ->and(Subject::count())->toBe(5);
});


test('factories create consistent parent relationships and valid question versions', function () {
    $subject = Subject::factory()->create();
    $topic = Topic::factory()->for($subject)->create();

    $question = Question::factory()->create([
        'institution_id' => $subject->institution_id,
        'subject_id' => $subject->id,
        'topic_id' => $topic->id,
    ]);

    $mcq = QuestionVersion::factory()->for($question)->create();
    $tf = QuestionVersion::factory()->for($question)->trueFalse()->create(['version_number' => 2]);
    $short = QuestionVersion::factory()->for($question)->shortAnswer()->create(['version_number' => 3]);

    expect($question->institution_id)->toBe($subject->institution_id)
        ->and($mcq->type)->toBe('mcq')
        ->and($mcq->status)->toBe('draft')
        ->and(array_keys($mcq->options))->toBe(['A', 'B', 'C', 'D'])
        ->and($tf->type)->toBe('true_false')
        ->and($tf->answer_text)->toBe('True')
        ->and($tf->options)->toBeNull()
        ->and($short->type)->toBe('short')
        ->and($short->options)->toBeNull();
});

test('standalone question factory associates itself with the generated subject institution', function () {
    $question = Question::factory()->create();
    expect($question->institution_id)->toBe($question->subject->institution_id);
});
