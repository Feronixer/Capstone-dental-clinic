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
        Schema::create('blocked_times', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Blocked Time');
            $table->datetime('start_datetime');
            $table->datetime('end_datetime');
            $table->integer('duration_minutes');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Add index for faster queries
            $table->index(['start_datetime', 'end_datetime']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocked_times');
    }
};
