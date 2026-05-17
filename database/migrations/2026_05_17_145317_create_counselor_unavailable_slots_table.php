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
        Schema::create('counselor_unavailable_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('counselor_id')->constrained('users')->cascadeOnDelete();
            $table->date('unavailable_date');
            $table->foreignId('time_slot_id')->constrained('time_slots')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['counselor_id', 'unavailable_date', 'time_slot_id'], 'counselor_slot_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counselor_unavailable_slots');
    }
};
