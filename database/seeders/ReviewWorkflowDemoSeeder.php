<?php

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\Question;
use App\Models\QuestionVersion;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * OPTIONAL local-only demonstration of the review workflow.
 * Not included in DatabaseSeeder; run explicitly when testing review controls.
 * Does not create any known password or change existing question approvals.
 */
class ReviewWorkflowDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment('local')) {
            throw new RuntimeException('ReviewWorkflowDemoSeeder can only run in APP_ENV=local.');
        }

        $this->call(SubjectSeeder::class);
        $institution = Institution::where('code', SubjectSeeder::institutionCode())->firstOrFail();
        $subject = Subject::where('institution_id', $institution->id)->orderBy('id')->firstOrFail();

        DB::transaction(function () use ($institution, $subject) {
            $author = User::firstOrCreate(
                ['email' => 'demo-review-author@eqbems.invalid'],
                [
                    'name' => 'EQBEMS Review Demo Author',
                    'institution_id' => $institution->id,
                    'password' => Hash::make(Str::random(48)),
                ]
            );
            // No real credentials for the demo author are created or distributed.
            // If a matching user already exists, do not change its password or role.
            if ((int) $author->institution_id !== (int) $institution->id) {
                throw new RuntimeException('The reserved demo author already belongs to another institution.');
            }

            $examples = [
                [
                    'marker' => '[EQBEMS-REVIEW-DEMO:01]',
                    'body' => 'Which of these is a valid example of an input device?',
                    'options' => ['A'=>'Monitor', 'B'=>'Keyboard', 'C'=>'Printer', 'D'=>'Speaker'],
                    'answer' => 'B',
                ],
                [
                    'marker' => '[EQBEMS-REVIEW-DEMO:02]',
                    'body' => 'What does RAM stand for in computing?',
                    'options' => ['A'=>'Read All Memory', 'B'=>'Rapid Access Module', 'C'=>'Random Access Memory', 'D'=>'Real Application Memory'],
                    'answer' => 'C',
                ],
            ];
            foreach ($examples as $example) {
                $body = $example['marker'].' '.$example['body'];
                if (QuestionVersion::where('body', $body)->exists()) {
                    continue; // Rerunning must not create duplicate demo questions.
                }
                $question = Question::factory()->create([
                    'institution_id' => $institution->id,
                    'subject_id' => $subject->id,
                    'topic_id' => null,
                    'author_id' => $author->id,
                ]);
                QuestionVersion::factory()->for($question)->create([
                    'version_number' => 1,
                    'type' => 'mcq',
                    'format' => 'standard',
                    'difficulty' => 'easy',
                    'status' => 'submitted',
                    'body' => $body,
                    'options' => $example['options'],
                    'answer_text' => $example['answer'],
                    'marks' => 1,
                    'created_by' => $author->id,
                ]);
            }
        });
        $this->command?->info('Created (or reused) two local-only submitted demo questions. Log in as a different reviewer with access to the demo institution.');
    }
}
