<?php

namespace Database\Seeders;

use App\Models\Election;
use App\Models\Position;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $election = Election::whereNull('started_at')->where('is_running', false)->first();

        if (!$election) {
            $election = Election::factory()->create([
                'name' => 'Positions Demo Election',
                'is_running' => false,
                'results_available' => false,
                'ballots_enabled' => false,
            ]);
        } 

        if ($election->positions()->exists()) {
            return;
        }

        $positions = [
            [
                'name' => 'President',
                'description' => 'Sya bida',
                'max_selections' => 1,
                'can_abstain' => true,
            ],
            [
                'name' => 'Vice President',
                'description' => 'Sya bida pero second',
                'max_selections' => 1,
                'can_abstain' => true,
            ],
            [
                'name' => 'Secretary',
                'description' => 'Maganda sulat or smthng',
                'max_selections' => 1,
                'can_abstain' => true,
            ],
            [
                'name' => 'Councilor',
                'description' => 'eme eme lang',
                'max_selections' => 12,
                'can_abstain' => true,
            ],
        ];

        foreach ($positions as $index => $data) {
            Position::factory()
                ->for($election)
                ->create(array_merge($data, [
                    'ordinality' => $index + 1,
                ]));
        }


    }
}
