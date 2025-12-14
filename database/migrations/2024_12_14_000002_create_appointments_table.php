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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('counselor_id')->constrained('users')->onDelete('cascade');
            $table->string('status'); // pending, accepted, rescheduled, cancelled, completed
            $table->timestamp('scheduled_at'); // Combined Date and Time
            $table->text('reason'); // Brief description (not encrypted for filtering)
            $table->boolean('email_sent')->default(false); // Tracks if notification was sent
            $table->timestamps();

            // Indexes for common queries
            $table->index(['client_id', 'status']);
            $table->index(['counselor_id', 'status']);
            $table->index(['scheduled_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
