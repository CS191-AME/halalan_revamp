<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // NULL = root election
            $table->foreignId('parent_id')->nullable()->constrained('elections');
            $table->boolean('is_running')->default(false);
            $table->boolean('results_available')->default(false);
            $table->boolean('ballots_enabled')->default(false);
            // Only changeable while the election is in draft (enforced in app code).
            // Not in the diagram, but referenced in the constraints text.
            $table->boolean('receipts_enabled')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('elections');
    }
};
