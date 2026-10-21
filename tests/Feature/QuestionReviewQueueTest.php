<?php

use App\Models\Blueprint;
use App\Models\Institution;
use App\Models\Question;
use App\Models\QuestionVersion;
use App\Models\Subject;
use App\Models\User;

beforeEach(function () {
    $this->seed();
});

function reviewQueueFixture(): array
{
    $institution = Institution::factory()->create();
    $subject = Subject::factory()->create(['institution_id' => $institution->id]);

    $setter = User::factory()->create(['institution_id' => $institution->id]);
    $setter->assignRole('Question Setter');
    $reviewer = User::factory()->create(['institution_id' => $institution->id]);
    $reviewer->assignRole('Moderator');
    $otherReviewer = User::factory()->create(['institution_id' => $institution->id]);
    $otherReviewer->assignRole('Moderator');

    return compact('institution', 'subject', 'setter', 'reviewer', 'otherReviewer');
}

function reviewQueueQuestion(Subject $subject, User $author, string $status, string $body): Question
{
    $question = Question::factory()->create([
        'institution_id' => $subject->institution_id,
        'subject_id' => $subject->id,
        'author_id' => $author->id,
        'topic_id' => null,
    ]);
    QuestionVersion::factory()->for($question)->create([
        'version_number' => 1,
        'type' => 'mcq',
        'difficulty' => 'easy',
        'status' => $status,
        'body' => $body,
        'created_by' => $author->id,
    ]);
    return $question;
}

test('review queue shows independent submitted questions, not drafts or own work', function () {
    $d = reviewQueueFixture();
    reviewQueueQuestion($d['subject'], $d['setter'], 'submitted', 'Queue-visible pending question');
    reviewQueueQuestion($d['subject'], $d['setter'], 'draft', 'Queue-invisible draft');
    reviewQueueQuestion($d['subject'], $d['setter'], 'approved', 'Queue-invisible approved');
    reviewQueueQuestion($d['subject'], $d['reviewer'], 'submitted', 'Queue-invisible own submission');

    $this->actingAs($d['reviewer'])->get(route('questions.review-queue'))
        ->assertOk()
        ->assertSee('Review / Approve / Reject')
        ->assertSee('Queue-visible pending question')
        ->assertDontSee('Queue-invisible draft')
        ->assertDontSee('Queue-invisible approved')
        ->assertDontSee('Queue-invisible own submission');

    $this->actingAs($d['setter'])->get(route('questions.review-queue'))->assertForbidden();
});

test('submitting then approving via review workflow removes question from queue', function () {
    $d = reviewQueueFixture();
    $question = reviewQueueQuestion($d['subject'], $d['setter'], 'draft', 'Review this question');

    $this->actingAs($d['setter'])->post(route('questions.submit', $question))->assertRedirect();
    $this->actingAs($d['reviewer'])->get(route('questions.review-queue'))
        ->assertOk()->assertSee('Review this question');
    $this->actingAs($d['reviewer'])->get(route('questions.show', $question))
        ->assertOk()->assertSee('Independent review')->assertSee('Approve')->assertSee('Reject');
    $this->actingAs($d['reviewer'])->post(route('questions.review', $question), [
        'decision' => 'approved', 'review_note' => 'Verified answer and wording.',
    ])->assertRedirect();

    expect($question->fresh()->latestVersion->status)->toBe('approved');
    $this->actingAs($d['reviewer'])->get(route('questions.review-queue'))
        ->assertOk()->assertDontSee('Review this question');
});

test('review queue cannot show questions belonging to another institution', function () {
    $d = reviewQueueFixture();
    $otherInstitution = Institution::factory()->create();
    $otherSubject = Subject::factory()->create(['institution_id' => $otherInstitution->id]);
    reviewQueueQuestion($otherSubject, $d['setter'], 'submitted', 'Restricted other institution pending question');

    $this->actingAs($d['reviewer'])->get(route('questions.review-queue'))
        ->assertOk()->assertDontSee('Restricted other institution pending question');
});

test('review queue subject filter limits visible submissions', function () {
    $d = reviewQueueFixture();
    $second = Subject::factory()->create(['institution_id' => $d['institution']->id]);
    reviewQueueQuestion($d['subject'], $d['setter'], 'submitted', 'Subject one submitted question');
    reviewQueueQuestion($second, $d['setter'], 'submitted', 'Subject two submitted question');

    $this->actingAs($d['reviewer'])->get(route('questions.review-queue', ['subject_id' => $d['subject']->id]))
        ->assertOk()->assertSee('Subject one submitted question')->assertDontSee('Subject two submitted question');
});

test('author cannot approve own question even through direct review post', function () {
    $d = reviewQueueFixture();
    $own = reviewQueueQuestion($d['subject'], $d['reviewer'], 'submitted', 'Needs a second reviewer');
    $this->actingAs($d['reviewer'])->get(route('questions.show', $own))
        ->assertOk()->assertSee('waiting for independent review')->assertDontSee('Independent review</h2>', false);
    $this->actingAs($d['reviewer'])->post(route('questions.review', $own), ['decision' => 'approved'])
        ->assertForbidden();
    $this->actingAs($d['otherReviewer'])->get(route('questions.review-queue'))
        ->assertOk()->assertSee('Needs a second reviewer');
});
