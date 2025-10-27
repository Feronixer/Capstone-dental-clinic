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
        Schema::create('patient_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_record_id')->constrained()->onDelete('cascade');
            $table->date('visit_date');
            $table->text('procedure_performed')->nullable();
            $table->text('materials_used')->nullable();
            $table->text('anesthesia_used')->nullable();
            $table->text('complications')->nullable();
            $table->text('post_operative_instructions')->nullable();
            $table->text('follow_up_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_histories');
    }
};
