<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Written once, in one transaction, when tallying after the election ends.
     */
    public function up(): void
    {
        Schema::create('results', function (Blueprint $table) {
            $table->foreignId('election_id')->constrained('elections');
            $table->foreignId('candidate_id')->constrained('candidates');
            $table->unsignedInteger('vote_count')->default(0);

            $table->primary(['election_id', 'candidate_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
