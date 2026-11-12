<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('alias')->nullable();
            // NULL = independent
            $table->foreignId('party_id')->nullable()->constrained('parties');
            // The election is derived through the position
            $table->foreignId('position_id')->constrained('positions');
            $table->string('description')->nullable();
            $table->string('picture')->nullable();

            // Needed so ballot_selections can reference (id, position_id) as a composite FK
            $table->unique(['id', 'position_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
