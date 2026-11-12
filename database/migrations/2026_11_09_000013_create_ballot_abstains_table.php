<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ballot_abstains', function (Blueprint $table) {
            $table->char('ballot_hash', 64);
            $table->unsignedBigInteger('position_id');

            $table->primary(['ballot_hash', 'position_id']);

            $table->foreign('ballot_hash')->references('ballot_hash')->on('ballots');
            $table->foreign('position_id')->references('id')->on('positions');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ballot_abstains');
    }
};
