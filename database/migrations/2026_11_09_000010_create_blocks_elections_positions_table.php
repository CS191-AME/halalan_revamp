<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Which positions a block can vote on (determines the ballot a voter gets).
     * election_id must match the position's own election and an election the
     * block belongs to; both are enforced by the composite FKs below.
     */
    public function up(): void
    {
        Schema::create('blocks_elections_positions', function (Blueprint $table) {
            $table->unsignedBigInteger('block_id');
            $table->unsignedBigInteger('position_id');
            $table->unsignedBigInteger('election_id');

            $table->primary(['block_id', 'position_id']);

            $table->foreign('block_id')->references('id')->on('blocks');

            // The block must belong to this election
            $table->foreign(['block_id', 'election_id'])
                ->references(['block_id', 'election_id'])
                ->on('blocks_elections');

            // The position must belong to this same election
            $table->foreign(['position_id', 'election_id'])
                ->references(['id', 'election_id'])
                ->on('positions');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocks_elections_positions');
    }
};
