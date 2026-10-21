<?php

use App\Models\Blueprint;
use App\Models\Exam;
use App\Models\Institution;
use App\Models\Question;
use App\Models\QuestionVersion;
use App\Models\Subject;
use App\Models\User;
use App\Services\BlueprintPoolService;

beforeEach(function () {
    $this->seed();
});

function blueprintFixtures(): array
{
    $institution = Institution::factory()->create();
    $subject = Subject::factory()->create(['institution_id' => $institution->id]);
    $manager = User::factory()->create(['institution_id' => $institution->id]);
    $manager->assignRole('Exam Controller');
    $otherInstitution = Institution::factory()->create();
    $foreignSubject = Subject::factory()->create(['institution_id' => $otherInstitution->id]);

    return compact('institution', 'subject', 'manager', 'otherInstitution', 'foreignSubject');
}

function approvedBlueprintQuestion(Subject $subject, array $overrides = []): Question
{
    $question = Question::factory()->create([
        'institution_id' => $subject->institution_id,
        'subject_id' => $subject->id,
        'topic_id' => null,
    ]);
    QuestionVersion::factory()->for($question)->approvedDemo()->create(array_merge([
        'version_number' => 1,
        'type' => 'mcq',
        'format' => 'standard',
        'difficulty' => 'easy',
    ], $overrides));
    return $question;
}

test('blueprint can be created edited and cleared of optional filters', function () {
    $d = blueprintFixtures();
    approvedBlueprintQuestion($d['subject']);

    $this->actingAs($d['manager'])->post(route('blueprints.store'), [
        'subject_id' => $d['subject']->id,
        'title' => 'ICT Midterm', 'question_count' => 1,
        'duration_minutes' => 60, 'type' => 'mcq',
        'difficulty' => 'easy', 'format' => 'standard',
    ])->assertRedirect(route('blueprints.index'));

    $blueprint = Blueprint::firstOrFail();
    $this->actingAs($d['manager'])->get(route('blueprints.edit', $blueprint))
        ->assertOk()->assertSee('Edit exam blueprint');
    $this->actingAs($d['manager'])->put(route('blueprints.update', $blueprint), [
        'subject_id' => $d['subject']->id,
        'title' => 'ICT Final', 'question_count' => 1,
        'duration_minutes' => 90,
        'type' => '', 'difficulty' => '', 'format' => '',
    ])->assertRedirect(route('blueprints.index'));

    $blueprint->refresh();
    expect($blueprint->title)->toBe('ICT Final')
        ->and($blueprint->duration_minutes)->toBe(90)
        ->and($blueprint->type)->toBeNull()
        ->and($blueprint->difficulty)->toBeNull()
        ->and($blueprint->format)->toBeNull();
});

test('blueprint index reports exact matching count and blocks insufficient generation', function () {
    $d = blueprintFixtures();
    approvedBlueprintQuestion($d['subject']);
    $blueprint = Blueprint::create([
        'institution_id' => $d['institution']->id, 'subject_id' => $d['subject']->id,
        'title' => 'Insufficient question pool', 'question_count' => 2,
        'duration_minutes' => 45, 'type' => 'mcq', 'difficulty' => 'easy',
        'format' => 'standard',
    ]);

    $this->actingAs($d['manager'])->get(route('blueprints.index'))
        ->assertOk()->assertSee('1 available')->assertSee('Need 1 more');
    $this->actingAs($d['manager'])->post(route('exams.generate', $blueprint))
        ->assertSessionHasErrors('blueprint');
    expect(Exam::count())->toBe(0);

    $this->actingAs($d['manager'])->getJson(route('blueprints.availability', [
        'subject_id' => $d['subject']->id,
        'type' => 'mcq', 'format' => 'standard', 'difficulty' => 'easy',
    ]))->assertOk()->assertJsonPath('available', 1);
});

test('latest approved version is counted even when a newer draft exists', function () {
    $d = blueprintFixtures();
    $q = approvedBlueprintQuestion($d['subject']);
    QuestionVersion::factory()->for($q)->create([
        'version_number' => 2, 'status' => 'draft',
        'type' => 'short', 'format' => 'creative',
    ]);
    $pool = app(BlueprintPoolService::class);
    expect($pool->query($d['institution']->id, $d['subject']->id, 'mcq', 'easy', 'standard')->count())->toBe(1)
        ->and($pool->query($d['institution']->id, $d['subject']->id, 'short', null, 'creative')->count())->toBe(0);
});

test('blueprint updates and availability requests cannot cross institutions', function () {
    $d = blueprintFixtures();
    $blueprint = Blueprint::create([
        'institution_id' => $d['institution']->id, 'subject_id' => $d['subject']->id,
        'title' => 'Private paper', 'question_count' => 1,
        'duration_minutes' => 30,
    ]);

    $this->actingAs($d['manager'])->getJson(route('blueprints.availability', [
        'subject_id' => $d['foreignSubject']->id,
    ]))->assertForbidden();
    $this->actingAs($d['manager'])->put(route('blueprints.update', $blueprint), [
        'subject_id' => $d['foreignSubject']->id,
        'title' => 'Escaped paper', 'question_count' => 1,
        'duration_minutes' => 30,
    ])->assertForbidden();
    expect($blueprint->fresh()->title)->toBe('Private paper');

    $foreignUser = User::factory()->create(['institution_id' => $d['otherInstitution']->id]);
    $foreignUser->assignRole('Exam Controller');
    $this->actingAs($foreignUser)->get(route('blueprints.edit', $blueprint))->assertForbidden();
});

test('creative format is rejected when paired with MCQ type', function () {
    $d = blueprintFixtures();
    $this->actingAs($d['manager'])->post(route('blueprints.store'), [
        'subject_id' => $d['subject']->id,
        'title' => 'Invalid format', 'question_count' => 1,
        'duration_minutes' => 30, 'type' => 'mcq', 'format' => 'creative',
    ])->assertSessionHasErrors('format');
    expect(Blueprint::count())->toBe(0);
});

test('editing an existing blueprint does not modify its generated exam', function () {
    $d = blueprintFixtures();
    approvedBlueprintQuestion($d['subject']);
    $blueprint = Blueprint::create([
        'institution_id' => $d['institution']->id, 'subject_id' => $d['subject']->id,
        'title' => 'Original exam', 'question_count' => 1,
        'duration_minutes' => 30,
    ]);
    $this->actingAs($d['manager'])->post(route('exams.generate', $blueprint))->assertRedirect();
    $exam = Exam::firstOrFail();
    $this->actingAs($d['manager'])->put(route('blueprints.update', $blueprint), [
        'subject_id' => $d['subject']->id,
        'title' => 'Updated blueprint', 'question_count' => 1,
        'duration_minutes' => 45,
    ])->assertRedirect();
    expect($exam->fresh()->title)->toBe('Original exam')
        ->and($exam->duration_minutes)->toBe(30);
});
