<?php

namespace Database\Factories;

use App\Models\Election;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Election>
 */
class ElectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(3, true),
            'parent_id' => null,
            'is_running' => false,
            'results_available' => false,
            'ballots_enabled' => false,
        ];
    }
}
