<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('password'); // store a hash
            $table->string('passkey');  // store a hash
            $table->string('first_name');
            $table->string('last_name');
            // NULL = super admin
            $table->foreignId('election_id')->nullable()->constrained('elections');
            $table->enum('role', ['super', 'election']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
