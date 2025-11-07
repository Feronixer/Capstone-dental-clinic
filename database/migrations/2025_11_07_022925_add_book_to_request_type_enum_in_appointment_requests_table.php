<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the ENUM to include 'book'
        DB::statement("ALTER TABLE appointment_requests MODIFY COLUMN request_type ENUM('walk-in', 'reschedule', 'book') DEFAULT 'walk-in'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original ENUM values
        DB::statement("ALTER TABLE appointment_requests MODIFY COLUMN request_type ENUM('walk-in', 'reschedule') DEFAULT 'walk-in'");
    }
};
