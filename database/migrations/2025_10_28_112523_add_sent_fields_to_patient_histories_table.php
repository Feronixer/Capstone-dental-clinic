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
            $table->boolean('sent_to_patient')->default(false)->after('follow_up_notes');
            $table->timestamp('sent_at')->nullable()->after('sent_to_patient');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_histories', function (Blueprint $table) {
            $table->dropColumn(['sent_to_patient', 'sent_at']);
        });
    }
};
