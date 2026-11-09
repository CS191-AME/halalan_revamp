<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ballot_selections', function (Blueprint $table) {
            $table->char('ballot_hash', 64);
            $table->unsignedBigInteger('position_id');
            $table->unsignedBigInteger('candidate_id');

            // Blocks duplicate picks of the same candidate on one ballot
            $table->primary(['ballot_hash', 'position_id', 'candidate_id']);

            $table->foreign('ballot_hash')->references('ballot_hash')->on('ballots');
            $table->foreign('position_id')->references('id')->on('positions');

            // The chosen candidate must belong to the chosen position
            $table->foreign(['candidate_id', 'position_id'])
                ->references(['id', 'position_id'])
                ->on('candidates');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ballot_selections');
    }
};
