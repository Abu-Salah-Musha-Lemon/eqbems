<?php

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Sample academic catalog for local development / demonstration.
 * No real institution or previously edited subject is overwritten.
 */
class SubjectSeeder extends Seeder
{
    public const DEFAULT_INSTITUTION_CODE = 'EQBEMS-DEMO';

    public static function institutionCode(): string
    {
        return strtoupper(trim((string) env('DEMO_INSTITUTION_CODE', self::DEFAULT_INSTITUTION_CODE)));
    }

    public function run(): void
    {
        $code = self::institutionCode();

        if ($code === '' || strlen($code) > 50) {
            throw new RuntimeException('DEMO_INSTITUTION_CODE must contain between 1 and 50 characters.');
        }

        DB::transaction(function () use ($code): void {
            $institution = Institution::withTrashed()->where('code', $code)->first();

            if ($institution && ($institution->trashed() || ! $institution->status)) {
                throw new RuntimeException("Institution {$code} is deleted or inactive. Restore/activate it, or choose another DEMO_INSTITUTION_CODE.");
            }

            if (! $institution) {
                $institution = Institution::factory()->create([
                    'code' => $code,
                    'name' => 'EQBEMS Demo Institution',
                    'country' => 'Bangladesh',
                    'status' => true,
                ]);
            }

            foreach ($this->subjects() as $subjectData) {
                $subject = Subject::where('institution_id', $institution->id)
                    ->where('code', $subjectData['code'])->first();

                if (! $subject) {
                    $subject = Subject::factory()->for($institution)->create([
                        'code' => $subjectData['code'],
                        'name' => $subjectData['name'],
                        'description' => $subjectData['description'],
                    ]);
                }

                foreach ($subjectData['topics'] as $topicName) {
                    if (! Topic::where('subject_id', $subject->id)->where('name', $topicName)->exists()) {
                        Topic::factory()->for($subject)->create(['name' => $topicName]);
                    }
                }
            }
        });

        $this->command?->info("Sample subjects and topics ready for institution: {$code}");
    }

    private function subjects(): array
    {
        return [
            [
                'code' => 'CSE101',
                'name' => 'Programming Fundamentals',
                'description' => 'Variables, control flow, functions and foundational programming.',
                'topics' => ['Variables and Data Types', 'Control Flow', 'Functions', 'Arrays'],
            ],
            [
                'code' => 'CSE201',
                'name' => 'Data Structures and Algorithms',
                'description' => 'Linear and non-linear data structures and algorithm analysis.',
                'topics' => ['Arrays and Linked Lists', 'Stacks and Queues', 'Trees and Graphs', 'Algorithm Complexity'],
            ],
            [
                'code' => 'CSE301',
                'name' => 'Database Management Systems',
                'description' => 'Relational database design, SQL, keys and transactions.',
                'topics' => ['Relational Model', 'SQL Queries', 'Normalization', 'Transactions'],
            ],
            [
                'code' => 'CSE401',
                'name' => 'Computer Networks',
                'description' => 'Network layers, addressing, switching and transport protocols.',
                'topics' => ['OSI and TCP/IP Models', 'IP Addressing', 'Transport Protocols', 'Network Devices'],
            ],
            [
                'code' => 'CSE402',
                'name' => 'Software Engineering',
                'description' => 'Requirements, software process models, testing and maintenance.',
                'topics' => ['Requirements Engineering', 'Process Models', 'Software Testing', 'Version Control'],
            ],
        ];
    }
}
