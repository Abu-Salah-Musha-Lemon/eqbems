<?php

namespace Database\Factories;

use App\Models\Institution;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Institution> */
class InstitutionFactory extends Factory
{
    protected $model = Institution::class;

    public function definition(): array
    {
        return [
            // The Institution model generates its own UUID through HasUuids.
            'code' => fake()->unique()->bothify('DEMO-#####'),
            'name' => fake()->company() . ' Institute',
            'country' => 'Bangladesh',
            'status' => true,
        ];
    }
}
