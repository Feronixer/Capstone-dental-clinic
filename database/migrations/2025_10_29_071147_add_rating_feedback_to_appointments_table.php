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
            $table->unsignedTinyInteger('rating')->nullable()->after('status');
            $table->text('feedback_comment')->nullable()->after('rating');
            $table->timestamp('feedback_submitted_at')->nullable()->after('feedback_comment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['rating', 'feedback_comment', 'feedback_submitted_at']);
        });
    }
};
