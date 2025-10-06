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
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('notes', 255)->nullable()->change();
            $table->index(['start_time']);
            $table->index(['end_time']);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
            Schema::table('appointments', function (Blueprint $table) {
            $table->integer('notes')->nullable()->change(); // rollback to old type
        });
    }
};
