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
        Schema::table('patient_histories', function (Blueprint $table) {
            // Dental History
            $table->string('previous_dentist')->nullable()->after('visit_date');
            $table->date('last_dental_visit')->nullable()->after('previous_dentist');
            $table->text('treatment_done')->nullable()->after('last_dental_visit');

            // Medical History
            $table->string('physician_name')->nullable()->after('treatment_done');
            $table->string('physician_specialty')->nullable()->after('physician_name');
            $table->text('physician_office_address')->nullable()->after('physician_specialty');
            $table->string('physician_contact')->nullable()->after('physician_office_address');

            // Health Questions
            $table->string('good_health')->nullable()->after('physician_contact');
            $table->string('under_treatment')->nullable()->after('good_health');
            $table->text('treatment_condition')->nullable()->after('under_treatment');
            $table->string('serious_illness')->nullable()->after('treatment_condition');
            $table->text('illness_details')->nullable()->after('serious_illness');
            $table->string('been_hospitalized')->nullable()->after('illness_details');
            $table->text('hospitalization_reason')->nullable()->after('been_hospitalized');
            $table->string('taking_drugs')->nullable()->after('hospitalization_reason');
            $table->text('medications')->nullable()->after('taking_drugs');
            $table->string('tobacco_use')->nullable()->after('medications');
            $table->string('alcohol_use')->nullable()->after('tobacco_use');
            $table->string('recreational_drugs')->nullable()->after('alcohol_use');

            // Allergies
            $table->boolean('allergy_anesthesia')->default(false)->after('recreational_drugs');
            $table->boolean('allergy_sulfa')->default(false)->after('allergy_anesthesia');
            $table->boolean('allergy_antibiotics')->default(false)->after('allergy_sulfa');
            $table->boolean('allergy_aspirin')->default(false)->after('allergy_antibiotics');
            $table->boolean('allergy_analgesics')->default(false)->after('allergy_aspirin');
            $table->boolean('allergy_latex')->default(false)->after('allergy_analgesics');
            $table->text('food_allergy_details')->nullable()->after('allergy_latex');
            $table->text('other_allergy_details')->nullable()->after('food_allergy_details');

            // For Women
            $table->string('is_pregnant')->nullable()->after('other_allergy_details');
            $table->string('is_nursing')->nullable()->after('is_pregnant');
            $table->string('birth_control')->nullable()->after('is_nursing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_histories', function (Blueprint $table) {
            // Dental History
            $table->dropColumn([
                'previous_dentist',
                'last_dental_visit',
                'treatment_done',
            ]);

            // Medical History
            $table->dropColumn([
                'physician_name',
                'physician_specialty',
                'physician_office_address',
                'physician_contact',
            ]);

            // Health Questions
            $table->dropColumn([
                'good_health',
                'under_treatment',
                'treatment_condition',
                'serious_illness',
                'illness_details',
                'been_hospitalized',
                'hospitalization_reason',
                'taking_drugs',
                'medications',
                'tobacco_use',
                'alcohol_use',
                'recreational_drugs',
            ]);

            // Allergies
            $table->dropColumn([
                'allergy_anesthesia',
                'allergy_sulfa',
                'allergy_antibiotics',
                'allergy_aspirin',
                'allergy_analgesics',
                'allergy_latex',
                'food_allergy_details',
                'other_allergy_details',
            ]);

            // For Women
            $table->dropColumn([
                'is_pregnant',
                'is_nursing',
                'birth_control',
            ]);
        });
    }
};
