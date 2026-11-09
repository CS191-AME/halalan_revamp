<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Records that a voter has voted in an election.
     * Holds no link to the ballot.
     */
    public function up(): void
    {
        Schema::create('votes_cast', function (Blueprint $table) {
            $table->foreignId('election_id')->constrained('elections');
            $table->foreignId('voter_id')->constrained('voters');
            $table->dateTime('cast_at');
            $table->dateTime('started_at');

            // One vote per voter per election
            $table->primary(['election_id', 'voter_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes_cast');
    }
};
