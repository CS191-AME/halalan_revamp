<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->foreignid('election_id')->constrained('elections')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('max_selections')->default(1);
            $table->unsignedSmallInteger('ordinality');
            $table->boolean('can_abstain')->default(true);
            $table->timestamps();

            // For blocks_elections_positions
            $table->unique(['id', 'election_id']);
        });

        // Ensure that max_selections is at least 1
        DB::statement(
            'ALTER TABLE positions
            ADD CONSTRAINT chk_max_selections
            CHECK (max_selections >= 1)'
        );

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
