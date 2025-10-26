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
        // Drop the existing appointments table
        Schema::dropIfExists('appointments');

        // Create the new appointments table with the correct structure
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('service_id')->nullable();
            $table->datetime('start_datetime');
            $table->integer('duration_minutes')->default(30);
            $table->datetime('end_datetime');
            $table->string('status', 50)->default('Pending');
            $table->text('notes')->nullable();
            $table->string('reason_for_visit', 255)->nullable();
            $table->boolean('is_new_patient')->default(false);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
