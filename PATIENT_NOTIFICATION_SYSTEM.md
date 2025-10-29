# Patient Notification System - Backend Documentation

## Overview
This document describes the complete backend implementation of the patient notification system for the dental clinic application.

## Database Schema

### Notifications Table
```sql
- id (bigint, primary key)
- user_id (foreign key to users table)
- type (string) - notification type
- title (string) - notification title
- message (text) - notification message
- icon (string, nullable) - custom icon class
- data (json, nullable) - additional data
- is_read (boolean, default: false)
- read_at (timestamp, nullable)
- created_at (timestamp)
- updated_at (timestamp)
```

## Models

### Notification Model (`app/Models/Notification.php`)

**Key Features:**
- Fillable fields for mass assignment
- JSON casting for `data` field
- Datetime casting for timestamps
- Notification type constants
- Relationship with User model
- Helper methods for read/unread management
- Query scopes for filtering
- Accessor attributes for time ago, icon class, and icon color

**Notification Types:**
- `TYPE_APPOINTMENT_CONFIRMED` - Appointment confirmation
- `TYPE_APPOINTMENT_REMINDER` - Appointment reminder
- `TYPE_APPOINTMENT_RESCHEDULED` - Appointment rescheduled
- `TYPE_APPOINTMENT_CANCELLED` - Appointment cancelled
- `TYPE_RECORD_UPDATED` - Patient record updated
- `TYPE_ANNOUNCEMENT` - General announcements
- `TYPE_GENERAL` - General notifications

**Methods:**
- `markAsRead()` - Mark notification as read
- `markAsUnread()` - Mark notification as unread
- `scopeUnread($query)` - Filter unread notifications
- `scopeRead($query)` - Filter read notifications
- `scopeRecent($query, $days)` - Filter recent notifications
- `getTimeAgoAttribute()` - Get human-readable time
- `getIconClassAttribute()` - Get icon class based on type
- `getIconColorAttribute()` - Get icon color based on type

## Controllers

### NotificationController (`app/Http/Controllers/Patient/NotificationController.php`)

**Endpoints:**

1. **`index()`** - Display all notifications (paginated)
   - Route: `GET /patient/notifications`
   - Returns: View with notifications

2. **`getRecent()`** - Get recent notifications for dropdown (AJAX)
   - Route: `GET /patient/notifications/recent`
   - Returns: JSON with last 5 notifications + unread count

3. **`getUnreadCount()`** - Get unread notification count
   - Route: `GET /patient/notifications/unread-count`
   - Returns: JSON with unread count

4. **`markAsRead($id)`** - Mark single notification as read
   - Route: `POST /patient/notifications/{id}/read`
   - Returns: JSON success response

5. **`markAsUnread($id)`** - Mark single notification as unread
   - Route: `POST /patient/notifications/{id}/unread`
   - Returns: JSON success response

6. **`markAllAsRead()`** - Mark all notifications as read
   - Route: `POST /patient/notifications/mark-all-read`
   - Returns: JSON success response

7. **`destroy($id)`** - Delete a notification
   - Route: `DELETE /patient/notifications/{id}`
   - Returns: JSON success response

8. **`clearRead()`** - Delete all read notifications
   - Route: `POST /patient/notifications/clear-read`
   - Returns: JSON success response

## Services

### NotificationService (`app/Services/NotificationService.php`)

This service provides static methods to create notifications for different events:

**Methods:**

1. **`appointmentConfirmed(Appointment $appointment)`**
   - Creates notification when appointment is confirmed
   - Includes appointment date and time in message

2. **`appointmentReminder(Appointment $appointment)`**
   - Creates reminder notification for upcoming appointment
   - Smart message based on time until appointment

3. **`appointmentRescheduled(Appointment $appointment, Carbon $oldDateTime)`**
   - Creates notification when appointment is rescheduled
   - Shows old and new date/time

4. **`appointmentCancelled(Appointment $appointment)`**
   - Creates notification when appointment is cancelled

5. **`recordUpdated(int $patientId, string $recordType)`**
   - Creates notification when patient record is updated

6. **`announcement(int $patientId, string $title, string $message, array $data = [])`**
   - Creates custom announcement notification

7. **`general(int $patientId, string $title, string $message, array $data = [])`**
   - Creates custom general notification

8. **`broadcastToAllPatients(string $title, string $message, array $data = [])`**
   - Sends notification to all patients

9. **`cleanupOldNotifications(int $daysOld = 90)`**
   - Deletes old read notifications (default: 90 days)

## Integration Examples

### 1. When Appointment is Confirmed

```php
use App\Services\NotificationService;

// In AppointmentController@store or update
$appointment = Appointment::create($appointmentData);

// Send notification
NotificationService::appointmentConfirmed($appointment);
```

### 2. When Appointment is Rescheduled

```php
use App\Services\NotificationService;

// In AppointmentController@update
$oldDateTime = $appointment->start_datetime->copy();
$appointment->update($newData);

// Send notification
NotificationService::appointmentRescheduled($appointment, $oldDateTime);
```

### 3. When Appointment is Cancelled

```php
use App\Services\NotificationService;

// In AppointmentController@destroy
NotificationService::appointmentCancelled($appointment);
$appointment->delete();
```

### 4. When Patient Record is Updated

```php
use App\Services\NotificationService;

// In Patient Record Controller
$record->update($data);

// Send notification
NotificationService::recordUpdated($patientId, 'dental record');
```

### 5. Scheduled Appointment Reminders

```php
// Create a scheduled command (app/Console/Commands/SendAppointmentReminders.php)
use App\Services\NotificationService;
use Carbon\Carbon;

$tomorrow = Carbon::tomorrow();
$appointments = Appointment::whereDate('start_datetime', $tomorrow)
    ->whereIn('status', ['Pending', 'Confirmed'])
    ->get();

foreach ($appointments as $appointment) {
    NotificationService::appointmentReminder($appointment);
}
```

## Routes

All routes are protected by the `auth` middleware:

```php
// Get all notifications
GET /patient/notifications

// Get recent notifications (AJAX)
GET /patient/notifications/recent

// Get unread count (AJAX)
GET /patient/notifications/unread-count

// Mark as read
POST /patient/notifications/{id}/read

// Mark as unread
POST /patient/notifications/{id}/unread

// Mark all as read
POST /patient/notifications/mark-all-read

// Delete notification
DELETE /patient/notifications/{id}

// Clear read notifications
POST /patient/notifications/clear-read
```

## Frontend Integration

### Fetching Notifications

```javascript
// Get recent notifications for dropdown
fetch('/patient/notifications/recent')
    .then(response => response.json())
    .then(data => {
        console.log(data.notifications);
        console.log(data.unread_count);
    });

// Get unread count
fetch('/patient/notifications/unread-count')
    .then(response => response.json())
    .then(data => {
        console.log(data.count);
    });
```

### Marking as Read

```javascript
// Mark single notification as read
fetch(`/patient/notifications/${notificationId}/read`, {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Content-Type': 'application/json',
    }
})
.then(response => response.json())
.then(data => {
    console.log(data.message);
    console.log(data.unread_count);
});

// Mark all as read
fetch('/patient/notifications/mark-all-read', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    }
})
.then(response => response.json())
.then(data => {
    console.log(data.message);
});
```

### Deleting Notifications

```javascript
// Delete single notification
fetch(`/patient/notifications/${notificationId}`, {
    method: 'DELETE',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    }
})
.then(response => response.json())
.then(data => {
    console.log(data.message);
});
```

## Notification Data Structure (JSON Response)

```json
{
    "id": 1,
    "type": "appointment_confirmed",
    "title": "Appointment Confirmed",
    "message": "Your appointment on April 15 has been confirmed",
    "icon_class": "bi-calendar-check",
    "icon_color": "bg-success",
    "is_read": false,
    "time_ago": "2 minutes ago",
    "created_at": "2025-10-28T16:50:46.000000Z",
    "data": {
        "appointment_id": 123,
        "appointment_date": "April 15, 2025",
        "appointment_time": "10:00 AM"
    }
}
```

## Icon Classes and Colors

Each notification type has predefined icons and colors:

| Type | Icon Class | Color Class |
|------|-----------|-------------|
| Appointment Confirmed | `bi-calendar-check` | `bg-success` |
| Appointment Reminder | `bi-bell` | `bg-warning` |
| Appointment Rescheduled | `bi-calendar-event` | `bg-info` |
| Appointment Cancelled | `bi-calendar-x` | `bg-danger` |
| Record Updated | `bi-file-earmark-medical` | `bg-primary` |
| Announcement | `bi-megaphone` | `bg-info` |
| General | `bi-info-circle` | `bg-secondary` |

## Database Seeder

Run the seeder to create sample notifications:

```bash
php artisan db:seed --class=NotificationSeeder
```

## Best Practices

1. **Always use NotificationService** - Don't create notifications directly
2. **Include relevant data** - Store appointment_id or other IDs in the data field
3. **Keep messages concise** - Short, clear messages work best
4. **Clean up old notifications** - Run cleanup periodically
5. **Handle errors gracefully** - Notification creation shouldn't break main flow

## Future Enhancements

1. **Real-time notifications** using WebSockets or Pusher
2. **Email notifications** in addition to in-app
3. **SMS notifications** for important updates
4. **Notification preferences** - Let patients choose notification types
5. **Push notifications** for mobile app
6. **Notification sounds** and visual indicators
7. **Grouped notifications** for better organization

## Testing

```php
// Create test notification
use App\Services\NotificationService;
use App\Models\User;

$patient = User::where('role_id', 3)->first();

NotificationService::general(
    $patient->id,
    'Test Notification',
    'This is a test notification message'
);
```

## Maintenance

### Cleanup Old Notifications

```php
// Delete read notifications older than 90 days
use App\Services\NotificationService;

NotificationService::cleanupOldNotifications(90);
```

Create a scheduled task in `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    $schedule->call(function () {
        NotificationService::cleanupOldNotifications(90);
    })->monthly();
}
```

## Support

For issues or questions about the notification system:
1. Check the logs in `storage/logs/laravel.log`
2. Verify database connection
3. Ensure migrations have run
4. Check route permissions and middleware

