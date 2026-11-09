<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained('elections');
            $table->string('name');
            $table->string('alias')->nullable();
            $table->string('description')->nullable();
            $table->string('logo')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parties');
    }
};
