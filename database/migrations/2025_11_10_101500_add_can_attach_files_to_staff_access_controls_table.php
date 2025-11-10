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
            if (!Schema::hasColumn('staff_access_controls', 'can_attach_files')) {
                $table->boolean('can_attach_files')->default(true)->after('can_respond_to_chat');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_access_controls', function (Blueprint $table) {
            if (Schema::hasColumn('staff_access_controls', 'can_attach_files')) {
                $table->dropColumn('can_attach_files');
            }
        });
    }
};

