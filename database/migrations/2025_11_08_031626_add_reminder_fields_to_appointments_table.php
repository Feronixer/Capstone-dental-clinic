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
            $table->boolean('reminder_24h_sent')->default(false)->after('rescheduled_at')->comment('24-hour reminder sent');
            $table->boolean('reminder_3h_sent')->default(false)->after('reminder_24h_sent')->comment('3-hour reminder sent');
            $table->timestamp('reminder_24h_sent_at')->nullable()->after('reminder_24h_sent')->comment('When 24-hour reminder was sent');
            $table->timestamp('reminder_3h_sent_at')->nullable()->after('reminder_3h_sent')->comment('When 3-hour reminder was sent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn([
                'reminder_24h_sent',
                'reminder_3h_sent',
                'reminder_24h_sent_at',
                'reminder_3h_sent_at'
            ]);
        });
    }
};
