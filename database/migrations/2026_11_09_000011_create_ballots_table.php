<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Deliberately has no voter_id and no timestamps.
     * ballot_hash is the SHA-256 hash (64 hex chars) of a random receipt key.
     * The key itself is never stored.
     */
    public function up(): void
    {
        Schema::create('ballots', function (Blueprint $table) {
            $table->char('ballot_hash', 64)->primary();
            $table->foreignId('election_id')->constrained('elections');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ballots');
    }
};
