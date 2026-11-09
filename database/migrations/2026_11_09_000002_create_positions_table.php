<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained('elections');
            $table->string('name');
            $table->string('description')->nullable();
            $table->unsignedInteger('max_selections')->default(1);
            $table->unsignedInteger('ordinality')->default(0);
            $table->boolean('can_abstain')->default(false);

            // Needed so other tables can reference (id, election_id) as a composite FK
            $table->unique(['id', 'election_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
