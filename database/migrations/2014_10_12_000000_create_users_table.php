<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');

            $table->string('phone')->nullable();
            $table->string('profile_picture')->nullable();

            $table->timestamp('token_request_at')->nullable();
            $table->integer('token_request_count')->default(0);

            $table->integer('verify_attempt_count')->default(0);
            $table->timestamp('verify_attempt_at')->nullable();

            $table->string('verification_token')->nullable();
            $table->timestamp('email_verified_at')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
