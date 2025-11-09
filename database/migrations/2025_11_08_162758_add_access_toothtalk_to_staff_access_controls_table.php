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
            $table->boolean('access_toothtalk')->default(true)->after('access_post_procedural');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_access_controls', function (Blueprint $table) {
            $table->dropColumn('access_toothtalk');
        });
    }
};
