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
        Schema::table('patient_records', function (Blueprint $table) {
            // Dental History
            $table->string('previous_dentist')->nullable()->after('treatment_plan');
            $table->date('last_dental_visit')->nullable();
            $table->text('treatment_done')->nullable();

            // Physician Info
            $table->string('physician_name')->nullable();
            $table->string('physician_specialty')->nullable();

            // Health Questions (stored as JSON for flexibility)
            $table->json('health_questions')->nullable();

            // Allergies (stored as JSON)
            $table->json('allergies_detail')->nullable();

            // Women's Health
            $table->boolean('is_pregnant')->nullable();
            $table->boolean('is_nursing')->nullable();
            $table->boolean('takes_birth_control')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_records', function (Blueprint $table) {
            $table->dropColumn([
                'previous_dentist',
                'last_dental_visit',
                'treatment_done',
                'physician_name',
                'physician_specialty',
                'health_questions',
                'allergies_detail',
                'is_pregnant',
                'is_nursing',
                'takes_birth_control'
            ]);
        });
    }
};
