<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Question> */
class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition(): array
    {
        return [
            'subject_id' => Subject::factory(),
            // Resolve after subject_id so the institution matches the subject.
            'institution_id' => fn (array $attributes): int =>
                (int) Subject::query()->findOrFail($attributes['subject_id'])->institution_id,
            'topic_id' => null,
            'author_id' => null,
        ];
    }
}
