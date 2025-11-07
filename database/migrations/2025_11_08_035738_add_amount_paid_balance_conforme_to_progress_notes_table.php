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
        Schema::table('progress_notes', function (Blueprint $table) {
            $table->decimal('amount_paid', 10, 2)->nullable()->after('treatment_response');
            $table->decimal('balance', 10, 2)->nullable()->after('amount_paid');
            $table->string('conforme')->nullable()->after('balance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('progress_notes', function (Blueprint $table) {
            $table->dropColumn(['amount_paid', 'balance', 'conforme']);
        });
    }
};
