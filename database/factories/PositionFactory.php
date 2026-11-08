<?php

namespace Database\Factories;

use App\Models\Position;
use App\Models\Election;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Position>
 */
class PositionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'election_id' => Election::factory(),
            'name' => fake()->unique()->words(2, true),
            'description' => fake()->optional()->sentence(),
            'max_selections' => 1,
            'can_abstain' => fake()->boolean(),
            'ordinality' => fake()->numberBetween(1, 10),
        ];
    }
}
