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
    Schema::create('appointments', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('patient_id'); // ✅ proper foreign key
        $table->string('service');
        $table->dateTime('start_time');
        $table->dateTime('end_time')->nullable();
        $table->text('notes')->nullable();
        $table->timestamps();

        // Add foreign key constraint
        $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
    });

    Schema::table('appointments', function (Blueprint $table) {
        $table->index(['start_time']);
        $table->index(['end_time']);
    });
}


    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
