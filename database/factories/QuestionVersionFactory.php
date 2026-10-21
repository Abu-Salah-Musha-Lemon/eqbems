<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\QuestionVersion;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<QuestionVersion> */
class QuestionVersionFactory extends Factory
{
    protected $model = QuestionVersion::class;

    public function definition(): array
    {
        $examples = [
            [
                'body' => 'Which data structure processes elements in first-in, first-out order?',
                'options' => ['A' => 'Stack', 'B' => 'Queue', 'C' => 'Tree', 'D' => 'Graph'],
                'answer_text' => 'B',
                'explanation' => 'A queue uses first-in, first-out order.',
            ],
            [
                'body' => 'Which SQL command is used to retrieve rows?',
                'options' => ['A' => 'DELETE', 'B' => 'INSERT', 'C' => 'SELECT', 'D' => 'UPDATE'],
                'answer_text' => 'C',
                'explanation' => 'SELECT retrieves data from a database.',
            ],
            [
                'body' => 'Which protocol provides reliable, ordered byte-stream delivery?',
                'options' => ['A' => 'TCP', 'B' => 'UDP', 'C' => 'ARP', 'D' => 'ICMP'],
                'answer_text' => 'A',
                'explanation' => 'TCP provides reliable, ordered byte-stream delivery.',
            ],
        ];
        $sample = fake()->randomElement($examples);

        return [
            'question_id' => Question::factory(),
            'version_number' => 1,
            'type' => 'mcq',
            'difficulty' => fake()->randomElement(['easy', 'medium', 'hard']),
            'body' => $sample['body'],
            'options' => $sample['options'],
            'answer_text' => $sample['answer_text'],
            'explanation' => $sample['explanation'],
            'marks' => 1,
            'status' => 'draft',
            'created_by' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
            'review_note' => null,
        ];
    }

    public function trueFalse(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'true_false',
            'body' => 'IPv6 addresses are 128 bits long.',
            'options' => null,
            'answer_text' => 'True',
            'explanation' => 'IPv6 addresses contain 128 bits.',
            'marks' => 1,
        ]);
    }

    public function shortAnswer(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'short',
            'body' => 'Explain the purpose of a database primary key.',
            'options' => null,
            'answer_text' => 'A primary key uniquely identifies each row in a relational table.',
            'explanation' => 'The answer should mention uniqueness and row identification.',
            'marks' => 5,
        ]);
    }

    /** A structurally complete board-style creative question (1 + 2 + 3 + 4 marks). */
    public function creative(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'short', 'format' => 'creative',
            'body' => 'Read the stimulus and answer the four questions.',
            'stimulus' => 'A teacher demonstrates the binary representation of decimal numbers to a class.',
            'creative_parts' => [
                ['key'=>'ক','text'=>'What is a binary number system?','marks'=>1],
                ['key'=>'খ','text'=>'Explain the role of place value.','marks'=>2],
                ['key'=>'গ','text'=>'Convert the number 13 to binary.','marks'=>3],
                ['key'=>'ঘ','text'=>'Analyze why binary representation is useful in digital devices.','marks'=>4],
            ],
            'options'=>null,'marks'=>10,
            'answer_text'=>'Key concepts: base-2, positional weights; 13 = 1101₂; digital circuits represent two logical states.',
        ]);
    }

    /** Approved only for demonstration. This is not a human review workflow. */
    public function approvedDemo(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
            'reviewed_by' => null,
            'reviewed_at' => null,
            'review_note' => '[FACTORY-DEMO] Auto-approved for local testing; not human reviewed.',
        ]);
    }
}
