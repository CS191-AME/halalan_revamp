<?php

namespace Database\Seeders;

use App\Models\Election;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ElectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $root = Election::factory()->create([
            'name' => 'University Student Council Election',
            'parent_id' => null,
            'is_running' => true,
            'results_available' => false,
            'ballots_enabled' => true,
            'started_at' => null,
        ]);

        Election::factory()->create([
            'name' => 'College of Engineering Election',
            'parent_id' => $root->id,
            'is_running' => true,
            'results_available' => false,
            'ballots_enabled' => true,
            'started_at' => null,
        ]);

        Election::factory()->create([
            'name' => 'College of Science Election',
            'parent_id' => $root->id,
            'is_running' => false,
            'results_available' => true,
            'ballots_enabled' => true,
            'started_at' => '2025-11-01 10:00:00',
        ]);

        Election::factory()->create([
            'name' => 'College of Arts and Letters Election',
            'parent_id' => $root->id,
            'is_running' => false,
            'results_available' => false,
            'ballots_enabled' => false,
            'started_at' => null,
        ]);

        Election::factory()->count(5)->create();
    }
}
