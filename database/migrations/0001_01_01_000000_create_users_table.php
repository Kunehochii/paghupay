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
            $table->string('tupv_id', 15)->nullable()->unique(); // TUPV-XX-XXXX for students
            $table->string('admin_id', 20)->nullable()->unique(); // ADMIN-XXX for admins
            $table->string('name');
            $table->string('email')->nullable()->unique(); // Nullable for students, required for counselors
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'client', 'counselor']);
            $table->string('nickname')->nullable();
            $table->string('course_year_section')->nullable(); // e.g., "BSIT-4A"
            $table->date('birthdate')->nullable();
            $table->string('birthplace')->nullable();
            $table->string('sex')->nullable(); // Male, Female
            $table->string('contact_number')->nullable();
            $table->string('fb_account')->nullable(); // Facebook Profile Link/Name
            $table->string('nationality')->nullable();
            $table->text('address')->nullable(); // Current Address
            $table->text('home_address')->nullable(); // Permanent Address
            $table->string('guardian_name')->nullable();
            $table->string('guardian_relationship')->nullable();
            $table->string('guardian_contact')->nullable();
            $table->boolean('is_active')->default(false); // Default false until profile completion
            $table->rememberToken();
            $table->timestamps();
            
            // Indexes for login performance
            $table->index('tupv_id', 'idx_users_tupv_id');
            $table->index('admin_id', 'idx_users_admin_id');
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
