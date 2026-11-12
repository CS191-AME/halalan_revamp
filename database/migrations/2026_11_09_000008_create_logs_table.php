<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admins');
            $table->foreignId('voter_id')->nullable()->constrained('voters');
            $table->foreignId('block_id')->nullable()->constrained('blocks');
            $table->dateTime('logged_at');
            $table->string('ip_address', 45)->nullable();
            $table->string('description');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
