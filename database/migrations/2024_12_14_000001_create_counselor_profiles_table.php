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
        Schema::create('counselor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('position')->nullable(); // e.g., "Head Psychologist"
            $table->string('picture_url')->nullable(); // Path to uploaded image
            $table->string('temp_password')->nullable(); // For initial setup
            $table->string('device_token')->nullable(); // Security Lock - SHA-256 hash
            $table->timestamp('device_bound_at')->nullable(); // When device was first bound
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counselor_profiles');
    }
};
