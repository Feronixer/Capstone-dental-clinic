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
        Schema::create('staff_access_controls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('users')->onDelete('cascade');
            
            // Navigation Access Controls
            $table->boolean('access_dashboard')->default(true);
            $table->boolean('access_appointments')->default(true);
            $table->boolean('access_user_management')->default(true);
            $table->boolean('access_content_management')->default(true);
            $table->boolean('access_post_procedural')->default(true);
            $table->boolean('access_live_chat')->default(true);
            $table->boolean('access_notifications')->default(true);
            $table->boolean('access_profile')->default(true);
            
            // Feature Access Controls
            $table->boolean('can_create_appointments')->default(true);
            $table->boolean('can_edit_appointments')->default(true);
            $table->boolean('can_delete_appointments')->default(true);
            $table->boolean('can_update_appointment_status')->default(true);
            $table->boolean('can_view_all_appointments')->default(true);
            $table->boolean('can_create_users')->default(true);
            $table->boolean('can_edit_users')->default(true);
            $table->boolean('can_delete_users')->default(true);
            $table->boolean('can_create_announcements')->default(true);
            $table->boolean('can_edit_announcements')->default(true);
            $table->boolean('can_delete_announcements')->default(true);
            $table->boolean('can_manage_services')->default(true);
            $table->boolean('can_manage_events')->default(true);
            $table->boolean('can_send_emails')->default(true);
            $table->boolean('can_view_patient_records')->default(true);
            $table->boolean('can_create_patient_records')->default(true);
            $table->boolean('can_edit_patient_records')->default(true);
            $table->boolean('can_delete_patient_records')->default(true);
            $table->boolean('can_respond_to_chat')->default(true);
            $table->boolean('can_export_data')->default(true);
            
            $table->timestamps();
            
            // Ensure one access control per staff
            $table->unique('staff_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_access_controls');
    }
};
