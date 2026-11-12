<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voters', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password'); // store a hash, never plain text
            $table->string('pin');      // store a hash, never plain text
            $table->string('first_name');
            $table->string('last_name');
            $table->foreignId('block_id')->constrained('blocks');
            $table->dateTime('login')->nullable();
            $table->dateTime('logout')->nullable();
            $table->string('ip_address', 45)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voters');
    }
};
