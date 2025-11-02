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
        Schema::table('announcement_archives', function (Blueprint $table) {
            $table->string('subheading')->nullable()->after('title');
            $table->date('date_start')->nullable()->after('subheading');
            $table->date('date_end')->nullable()->after('date_start');
            $table->time('time_start')->nullable()->after('date_end');
            $table->time('time_end')->nullable()->after('time_start');
            $table->boolean('is_whole_day')->default(false)->after('time_end');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcement_archives', function (Blueprint $table) {
            $table->dropColumn([
                'subheading',
                'date_start',
                'date_end',
                'time_start',
                'time_end',
                'is_whole_day'
            ]);
        });
    }
};