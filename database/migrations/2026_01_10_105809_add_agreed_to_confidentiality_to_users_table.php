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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('agreed_to_confidentiality')->default(false)->after('is_active');
            $table->timestamp('agreed_at')->nullable()->after('agreed_to_confidentiality');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['agreed_to_confidentiality', 'agreed_at']);
        });
    }
};
