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
            // Personal Information
            $table->string('home_address')->nullable()->after('patient_number');
            $table->date('date_of_birth')->nullable()->after('home_address');
            $table->integer('age')->nullable()->after('date_of_birth');
            $table->string('sex')->nullable()->after('age');
            $table->string('nickname')->nullable()->after('sex');
            $table->string('religion')->nullable()->after('nickname');
            $table->string('occupation')->nullable()->after('religion');
            $table->string('contact')->nullable()->after('occupation');

            // For Minors
            $table->string('guardian_name')->nullable()->after('contact');
            $table->string('guardian_contact')->nullable()->after('guardian_name');
            $table->string('guardian_occupation')->nullable()->after('guardian_contact');

            // Other Notes
            $table->text('other_notes')->nullable()->after('treatment_plan');

            // Sent to patient tracking
            $table->boolean('sent_to_patient')->default(false)->after('other_notes');
            $table->timestamp('sent_at')->nullable()->after('sent_to_patient');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_records', function (Blueprint $table) {
            $table->dropColumn([
                'home_address',
                'date_of_birth',
                'age',
                'sex',
                'nickname',
                'religion',
                'occupation',
                'contact',
                'guardian_name',
                'guardian_contact',
                'guardian_occupation',
                'other_notes',
                'sent_to_patient',
                'sent_at'
            ]);
        });
    }
};
