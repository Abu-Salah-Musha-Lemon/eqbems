<?php

use App\Models\Institution;
use App\Models\OnlineExamAttempt;
use App\Models\Question;
use App\Models\QuestionVersion;
use App\Models\Subject;
use App\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->seed();
});

test('revising an approved creative question preserves its stimulus and all four rubric parts', function () {
    $institution = Institution::factory()->create();
    $subject = Subject::factory()->create(['institution_id' => $institution->id]);
    $setter = User::factory()->create(['institution_id' => $institution->id]);
    $setter->assignRole('Question Setter');

    $question = Question::factory()->create([
        'institution_id' => $institution->id,
        'subject_id' => $subject->id,
        'author_id' => $setter->id,
    ]);
    $original = QuestionVersion::factory()->for($question)->approvedDemo()->creative()->create([
        'created_by' => $setter->id,
    ]);

    $this->actingAs($setter)->post(route('questions.revise', $question))
        ->assertRedirect(route('questions.edit', $question));

    $revision = $question->fresh()->latestVersion;
    expect($revision->version_number)->toBe(2)
        ->and($revision->status)->toBe('draft')
        ->and($revision->format)->toBe('creative')
        ->and($revision->stimulus)->toBe($original->stimulus)
        ->and($revision->creative_parts)->toBe($original->creative_parts)
        ->and($revision->marks)->toBe('10.00');
});

test('the only Super Admin cannot delete their own profile', function () {
    $admin = User::factory()->create();
    Role::findOrCreate('Super Admin', 'web');
    $admin->assignRole('Super Admin');
    // Remove any other bootstrap administrator from this isolated test database.
    User::role('Super Admin')->where('id', '!=', $admin->id)->delete();

    $this->actingAs($admin)->from('/profile')->delete('/profile', ['password' => 'password'])
        ->assertRedirect('/profile')->assertSessionHasErrorsIn('userDeletion', 'password');
    expect($admin->fresh())->not->toBeNull();
    $this->assertAuthenticatedAs($admin);
});

test('students with online attempts cannot delete accounts or move institutions', function () {
    $institution = Institution::factory()->create();
    $otherInstitution = Institution::factory()->create();
    $student = User::factory()->create(['institution_id' => $institution->id]);
    $student->assignRole('Student');
    $admin = User::factory()->create();
    $admin->assignRole('Super Admin');

    $subject = Subject::factory()->create(['institution_id' => $institution->id]);
    $blueprint = \App\Models\Blueprint::create([
        'institution_id' => $institution->id,
        'subject_id' => $subject->id,
        'title' => 'Exam with history',
        'question_count' => 1,
        'duration_minutes' => 30,
    ]);
    $exam = \App\Models\Exam::create([
        'institution_id' => $institution->id,
        'subject_id' => $subject->id,
        'blueprint_id' => $blueprint->id,
        'title' => 'Started exam',
        'duration_minutes' => 30,
        'total_marks' => '1.00',
        'status' => 'issued',
    ]);
    OnlineExamAttempt::create([
        'exam_id' => $exam->id,
        'student_id' => $student->id,
        'started_at' => now(),
        'status' => 'in_progress',
    ]);

    $this->actingAs($student)->from('/profile')->delete('/profile', ['password' => 'password'])
        ->assertSessionHasErrorsIn('userDeletion', 'password');
    expect($student->fresh())->not->toBeNull();

    $this->actingAs($admin)->put(route('users.update', $student), [
        'name' => $student->name,
        'email' => $student->email,
        'institution_id' => $otherInstitution->id,
        'roles' => ['Student'],
    ])->assertSessionHasErrors('institution_id');
    expect((int) $student->fresh()->institution_id)->toBe($institution->id);

    $this->actingAs($admin)->delete(route('users.destroy', $student))->assertStatus(422);
    expect($student->fresh())->not->toBeNull();
});
