<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Makes appointment_id nullable because case logs can be created:
     * 1. From an appointment (has appointment_id)
     * 2. Manually by counselor (no appointment_id)
     */
    public function up(): void
    {
        Schema::table('case_logs', function (Blueprint $table) {
            // Drop the existing foreign key constraint first
            $table->dropForeign(['appointment_id']);
            
            // Make the column nullable
            $table->unsignedBigInteger('appointment_id')->nullable()->change();
            
            // Re-add foreign key with nullable support
            $table->foreign('appointment_id')
                ->references('id')
                ->on('appointments')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('case_logs', function (Blueprint $table) {
            // Drop the nullable foreign key
            $table->dropForeign(['appointment_id']);
            
            // Make column required again (will fail if NULL values exist)
            $table->unsignedBigInteger('appointment_id')->nullable(false)->change();
            
            // Re-add cascade delete constraint
            $table->foreign('appointment_id')
                ->references('id')
                ->on('appointments')
                ->onDelete('cascade');
        });
    }
};
