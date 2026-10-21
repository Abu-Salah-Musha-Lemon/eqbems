<?php

namespace Database\Factories;

use App\Models\Institution;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Subject> */
class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    public function definition(): array
    {
        return [
            'institution_id' => Institution::factory(),
            'code' => fake()->unique()->bothify('CSE-####'),
            'name' => fake()->randomElement([
                'Programming Fundamentals', 'Database Systems',
                'Computer Networks', 'Data Structures', 'Software Engineering',
            ]),
            'description' => fake()->sentence(),
        ];
    }
}
