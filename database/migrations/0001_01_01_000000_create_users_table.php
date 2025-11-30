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
            // Core identity
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password')->nullable(); // nullable for Google-only accounts
            
            // Contact
            $table->string('phone_number')->unique();
            
            // Consumer info
            $table->integer('year_of_birth')->nullable();
            $table->string('zip_code')->nullable();
            $table->boolean('is_decision_maker')->default(false);
            $table->boolean('has_medicare_part_b')->default(false);
            
            // Auth provider fields
            $table->string('google_id')->nullable();
            $table->string('auth_provider')->nullable(); // "email" or "google"
            
            // Status flags
            $table->boolean('is_guest')->default(false);
            $table->boolean('is_admin')->default(false);
            
            // Email verification (OTP-based)
            $table->timestamp('email_verified_at')->nullable();
            $table->string('email_verification_code')->nullable();
            $table->timestamp('email_verification_expires_at')->nullable();
            
            // Forgot password (OTP-based)
            $table->string('password_reset_code')->nullable();
            $table->timestamp('password_reset_expires_at')->nullable();
            
            // Standard fields
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
