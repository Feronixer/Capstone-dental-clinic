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
            // Add rating column (1-5 stars) - nullable until patient provides feedback
            $table->tinyInteger('rating')->nullable()->after('notes')->comment('Patient rating: 1-5 stars');

            // Add feedback text column for patient comments
            $table->text('patient_feedback')->nullable()->after('rating')->comment('Patient feedback/comments');

            // Add timestamp for when rating was submitted
            $table->timestamp('rated_at')->nullable()->after('patient_feedback')->comment('When patient submitted rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['rating', 'patient_feedback', 'rated_at']);
        });
    }
};
