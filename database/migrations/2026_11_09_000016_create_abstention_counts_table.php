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
        Schema::create('abstention_counts', function (Blueprint $table) {
            $table->foreignId('election_id')->constrained('elections');
            $table->foreignId('position_id')->constrained('positions');
            $table->unsignedInteger('abstain_count')->default(0);

            $table->primary(['election_id', 'position_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abstention_counts');
    }
};
