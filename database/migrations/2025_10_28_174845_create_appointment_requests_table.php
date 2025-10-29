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
        Schema::create('appointment_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('service_id')->nullable();
            $table->unsignedBigInteger('existing_appointment_id')->nullable(); // For reschedule requests
            $table->enum('request_type', ['walk-in', 'reschedule'])->default('walk-in');
            $table->datetime('requested_datetime');
            $table->datetime('requested_end_datetime');
            $table->integer('duration_minutes')->default(30);
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Denied'])->default('Pending');
            $table->unsignedBigInteger('reviewed_by')->nullable(); // Admin/Staff who reviewed
            $table->text('review_notes')->nullable(); // Why it was approved/denied
            $table->datetime('reviewed_at')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('set null');
            $table->foreign('existing_appointment_id')->references('id')->on('appointments')->onDelete('cascade');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_requests');
    }
};
