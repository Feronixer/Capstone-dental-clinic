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
        Schema::table('staff_access_controls', function (Blueprint $table) {
            $table->boolean('can_manage_announcements')->default(true)->after('can_delete_announcements');
            $table->boolean('can_delete_archives')->default(true)->after('can_manage_announcements');
            $table->boolean('can_manage_mails')->default(true)->after('can_send_emails');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_access_controls', function (Blueprint $table) {
            $table->dropColumn(['can_manage_announcements', 'can_delete_archives', 'can_manage_mails']);
        });
    }
};
