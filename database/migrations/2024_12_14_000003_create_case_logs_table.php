<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Security Note: progress_report and additional_notes are encrypted
     * at the application level using Laravel's built-in encryption (AES-256-CBC).
     */
    public function up(): void
    {
        Schema::create('case_logs', function (Blueprint $table) {
            $table->id();
            $table->string('case_log_id')->unique(); // Format: TUPV-{UUID}
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->foreignId('counselor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('start_time')->nullable(); // When session started
            $table->timestamp('end_time')->nullable(); // When session ended
            $table->integer('session_duration')->nullable(); // Minutes
            $table->text('progress_report')->nullable(); // [Encrypted] Session notes
            $table->text('additional_notes')->nullable(); // [Encrypted] Recommendations
            $table->timestamps();

            // Indexes for common queries
            $table->index(['counselor_id', 'created_at']);
            $table->index(['client_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_logs');
    }
};
