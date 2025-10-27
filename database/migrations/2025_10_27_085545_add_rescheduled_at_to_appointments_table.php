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
        Schema::table('appointments', function (Blueprint $table) {
            $table->timestamp('rescheduled_at')->nullable()->after('rated_at')->comment('When appointment was rescheduled');
            $table->datetime('original_datetime')->nullable()->after('rescheduled_at')->comment('Original appointment datetime before reschedule');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['rescheduled_at', 'original_datetime']);
        });
    }
};
