<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Security Note: description is encrypted at the application level
     * using Laravel's built-in encryption (AES-256-CBC).
     */
    public function up(): void
    {
        Schema::create('treatment_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goal_id')->constrained('treatment_goals')->onDelete('cascade');
            $table->text('description'); // [Encrypted] Specific activity
            $table->date('activity_date'); // When this activity is set for
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_activities');
    }
};
