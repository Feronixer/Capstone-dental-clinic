<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffAccessControl extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        // Navigation Access Controls
        'access_dashboard',
        'access_appointments',
        'access_user_management',
        'access_content_management',
        'access_post_procedural',
        'access_toothtalk',
        'access_live_chat',
        'access_notifications',
        'access_profile',
        // Feature Access Controls
        'can_create_appointments',
        'can_edit_appointments',
        'can_delete_appointments',
        'can_update_appointment_status',
        'can_view_all_appointments',
        'can_create_users',
        'can_edit_users',
        'can_delete_users',
        'can_create_announcements',
        'can_edit_announcements',
        'can_delete_announcements',
        'can_manage_announcements',
        'can_delete_archives',
        'can_manage_services',
        'can_manage_events',
        'can_send_emails',
        'can_manage_mails',
        'can_view_patient_records',
        'can_create_patient_records',
        'can_edit_patient_records',
        'can_delete_patient_records',
        'can_respond_to_chat',
        'can_attach_files',
        'can_export_data',
    ];

    protected $casts = [
        'access_dashboard' => 'boolean',
        'access_appointments' => 'boolean',
        'access_user_management' => 'boolean',
        'access_content_management' => 'boolean',
        'access_post_procedural' => 'boolean',
        'access_toothtalk' => 'boolean',
        'access_live_chat' => 'boolean',
        'access_notifications' => 'boolean',
        'access_profile' => 'boolean',
        'can_create_appointments' => 'boolean',
        'can_edit_appointments' => 'boolean',
        'can_delete_appointments' => 'boolean',
        'can_update_appointment_status' => 'boolean',
        'can_view_all_appointments' => 'boolean',
        'can_create_users' => 'boolean',
        'can_edit_users' => 'boolean',
        'can_delete_users' => 'boolean',
        'can_create_announcements' => 'boolean',
        'can_edit_announcements' => 'boolean',
        'can_delete_announcements' => 'boolean',
        'can_manage_announcements' => 'boolean',
        'can_delete_archives' => 'boolean',
        'can_manage_services' => 'boolean',
        'can_manage_events' => 'boolean',
        'can_send_emails' => 'boolean',
        'can_manage_mails' => 'boolean',
        'can_view_patient_records' => 'boolean',
        'can_create_patient_records' => 'boolean',
        'can_edit_patient_records' => 'boolean',
        'can_delete_patient_records' => 'boolean',
        'can_respond_to_chat' => 'boolean',
        'can_attach_files' => 'boolean',
        'can_export_data' => 'boolean',
    ];

    /**
     * Get the staff user that owns this access control.
     */
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    /**
     * Check if staff has access to a specific navigation item
     */
    public function hasNavAccess($navItem)
    {
        $accessKey = 'access_' . $navItem;
        return $this->$accessKey ?? false;
    }

    /**
     * Check if staff can perform a specific action
     */
    public function can($action)
    {
        $actionKey = 'can_' . $action;
        return $this->$actionKey ?? false;
    }
}
