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
            if (!Schema::hasColumn('appointments', 'rating')) {
                $table->unsignedTinyInteger('rating')->nullable()->after('status');
            }

            if (!Schema::hasColumn('appointments', 'feedback_comment')) {
                $table->text('feedback_comment')->nullable()->after('rating');
            }

            if (!Schema::hasColumn('appointments', 'feedback_submitted_at')) {
                $table->timestamp('feedback_submitted_at')->nullable()->after('feedback_comment');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('appointments', 'rating')) {
                $columnsToDrop[] = 'rating';
            }
            if (Schema::hasColumn('appointments', 'feedback_comment')) {
                $columnsToDrop[] = 'feedback_comment';
            }
            if (Schema::hasColumn('appointments', 'feedback_submitted_at')) {
                $columnsToDrop[] = 'feedback_submitted_at';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
