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
        Schema::table('chatbot_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('chatbot_settings', 'censorship_enabled')) {
                $table->boolean('censorship_enabled')
                    ->default(false)
                    ->after('is_online');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chatbot_settings', function (Blueprint $table) {
            if (Schema::hasColumn('chatbot_settings', 'censorship_enabled')) {
                $table->dropColumn('censorship_enabled');
            }
        });
    }
};

