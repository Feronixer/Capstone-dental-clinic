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
        Schema::create('progress_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_record_id')->constrained()->onDelete('cascade');
            $table->date('note_date');
            $table->text('progress_description');
            $table->text('treatment_response')->nullable();
            $table->text('next_steps')->nullable();
            $table->string('status')->default('ongoing'); // ongoing, completed, followup_needed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_notes');
    }
};
