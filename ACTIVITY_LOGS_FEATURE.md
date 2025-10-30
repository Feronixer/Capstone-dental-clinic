# Staff Activity Logs Feature

## Overview
This feature allows administrators to track and monitor all actions performed by staff members in the system. Every create, update, and delete operation performed by staff is automatically logged and can be viewed by administrators.

## What Gets Logged

### Modules Tracked:
1. **Patient Records**
   - Creating new patient records
   - Updating existing patient records

2. **Patient History**
   - Creating medical history entries
   - Updating medical history entries

3. **Progress Notes**
   - Creating progress notes
   - Updating progress notes

### Information Captured:
- **Who**: Staff member who performed the action
- **What**: Type of action (created, updated, deleted, viewed)
- **When**: Date and time of the action
- **Where**: Module/feature affected
- **Details**: Description of what was changed
- **Changes**: Before and after values for updates
- **Technical Info**: IP address and user agent

## How to Access

### For Administrators:
1. Log in to the admin panel
2. Click on **"Activity Logs"** in the sidebar navigation
3. View, filter, and search through all staff activities

## Features

### 1. Comprehensive Filtering
- **By Staff Member**: See activities of specific staff members
- **By Module**: Filter by patient records, history, or progress notes
- **By Action**: View only creates, updates, or deletes
- **By Date Range**: Filter activities within a date range
- **By Search**: Search in activity descriptions

### 2. Detailed View
Click "View" on any activity log to see:
- Full details of the action
- Staff member information
- Timestamp and IP address
- Complete before/after values (for updates)

### 3. Real-time Logging
All staff activities are logged automatically in real-time. No manual intervention required.

## Database Structure

### Table: `activity_logs`
```php
- id: Primary key
- user_id: Staff member who performed the action
- action: Type of action (created, updated, deleted, viewed)
- module: Feature module (patient_record, patient_history, progress_note)
- description: Human-readable description
- record_id: ID of the affected record
- record_type: Model name of the affected record
- old_values: JSON of previous values (for updates)
- new_values: JSON of new values (for creates/updates)
- ip_address: IP address of the staff member
- user_agent: Browser information
- timestamps: created_at, updated_at
```

## Implementation Details

### Controllers Updated:
1. **`app/Http/Controllers/Staff/PostProceduralController.php`**
   - Added activity logging for patient records
   - Added activity logging for patient history
   - Added activity logging for progress notes

### New Files Created:
1. **Migration**: `database/migrations/2025_10_30_161604_create_activity_logs_table.php`
2. **Model**: `app/Models/ActivityLog.php`
3. **Controller**: `app/Http/Controllers/Admin/ActivityLogController.php`
4. **View**: `resources/views/admin/activity-logs.blade.php`

### Routes Added:
```php
GET  /admin/activity-logs           - View activity logs page
GET  /admin/activity-logs/data      - Get filtered logs (AJAX)
GET  /admin/activity-logs/{id}      - View specific log details
```

## Usage Example

### Logging an Activity (Automatic):
```php
ActivityLog::log(
    'updated',                    // Action
    'patient_record',            // Module
    'Updated patient record for John Doe',  // Description
    $record->id,                 // Record ID
    'PatientRecord',             // Record Type
    $oldValues,                  // Old Values (array)
    $newValues                   // New Values (array)
);
```

### Viewing Logs (Admin):
1. Navigate to Admin Dashboard
2. Click "Activity Logs" in sidebar
3. Use filters to narrow down results
4. Click "View" to see detailed information

## Benefits

1. **Accountability**: Track who made what changes
2. **Audit Trail**: Complete history of all modifications
3. **Transparency**: Admin can monitor staff activities
4. **Compliance**: Helps meet regulatory requirements for medical data
5. **Troubleshooting**: Identify when and how issues occurred
6. **Security**: Detect unauthorized or suspicious activities

## Security Features

- Only administrators (role_id = 1) can view activity logs
- Staff members (role_id = 2) cannot access or modify logs
- IP addresses are captured for additional security tracking
- Old and new values stored for complete audit trail

## Future Enhancements

Potential additions:
- Email notifications for specific activities
- Export logs to CSV/PDF
- More detailed change comparison view
- Dashboard widgets showing recent activities
- Retention policy for old logs
- Staff member activity summaries/reports

