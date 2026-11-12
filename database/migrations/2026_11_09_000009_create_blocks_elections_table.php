<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Which elections a block belongs to. A block can belong to an election
     * without voting on any of its positions.
     */
    public function up(): void
    {
        Schema::create('blocks_elections', function (Blueprint $table) {
            $table->foreignId('block_id')->constrained('blocks');
            $table->foreignId('election_id')->constrained('elections');

            $table->primary(['block_id', 'election_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocks_elections');
    }
};
