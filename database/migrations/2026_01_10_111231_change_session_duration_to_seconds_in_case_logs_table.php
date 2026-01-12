<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration converts session_duration from minutes to seconds.
     * Existing data is multiplied by 60 to preserve accuracy.
     */
    public function up(): void
    {
        // Convert existing data from minutes to seconds
        DB::table('case_logs')
            ->whereNotNull('session_duration')
            ->update(['session_duration' => DB::raw('session_duration * 60')]);

        // Update column comment for documentation
        Schema::table('case_logs', function (Blueprint $table) {
            $table->integer('session_duration')->nullable()->comment('Duration in seconds')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert data back from seconds to minutes
        DB::table('case_logs')
            ->whereNotNull('session_duration')
            ->update(['session_duration' => DB::raw('FLOOR(session_duration / 60)')]);

        // Revert column comment
        Schema::table('case_logs', function (Blueprint $table) {
            $table->integer('session_duration')->nullable()->comment('Duration in minutes')->change();
        });
    }
};
