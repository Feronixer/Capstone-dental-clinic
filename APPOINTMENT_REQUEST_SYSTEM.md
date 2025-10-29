# Appointment Request System Documentation

## Overview
This system allows patients to submit walk-in or reschedule appointment requests from their calendar, which admin/staff can approve or deny through a notification interface.

## System Flow

### 1. Patient Request Submission
**Location**: `resources/views/patient/calendar.blade.php`

Patients can submit two types of requests:
- **Emergency Walk-in**: Urgent dental care needed
- **Reschedule Request**: Change existing appointment

**Features**:
- Modern toggle buttons to switch between request types
- Form with reason, date, and time fields
- AJAX submission with loading states
- Success/error notifications

**Endpoint**: `POST /patient/calendar/submit-request`

### 2. Backend Processing
**Controller**: `app/Http/Controllers/Patient/CalendarController.php`

When a patient submits a request:
1. Validates the input (type, reason, date, time)
2. Creates an `AppointmentRequest` record in the database
3. Fetches all admin and staff users
4. Creates notifications for each admin/staff member
5. Returns success response to patient

**Database Table**: `appointment_requests`

Fields:
- `patient_id` - Who made the request
- `service_id` - Optional service selection
- `existing_appointment_id` - For reschedule requests
- `request_type` - 'walk-in' or 'reschedule'
- `requested_datetime` - Desired appointment date/time
- `requested_end_datetime` - Calculated end time
- `duration_minutes` - Default 30 minutes
- `reason` - Patient's explanation
- `status` - 'Pending', 'Approved', or 'Denied'
- `reviewed_by` - Admin/staff who processed it
- `review_notes` - Reason for denial (if denied)
- `reviewed_at` - When it was processed

### 3. Admin/Staff Notification View
**Views**: 
- `resources/views/admin/notification.blade.php`
- `resources/views/staff/notification.blade.php`

**Features**:
- Beautiful card-based layout for each request
- Color-coded badges for request type (walk-in vs reschedule)
- Patient information with avatar
- Request details (date, time, service)
- Patient's reason displayed prominently
- Approve/Deny action buttons

**Controllers**:
- `app/Http/Controllers/Admin/NotificationController.php`
- `app/Http/Controllers/Staff/NotificationController.php`

### 4. Approval Process
**Endpoint**: `POST /admin/notifications/approve/{id}` or `POST /staff/notifications/approve/{id}`

When admin/staff approves a request:
1. Validates the request is still pending
2. Creates a new `Appointment` with status 'Confirmed'
3. Updates the `AppointmentRequest` to 'Approved'
4. Sends notification to patient:
   - Title: "Appointment Request Approved"
   - Message: Details of approved appointment
   - Type: `appointment_confirmed`

**Result**: 
- Patient receives notification
- Appointment appears in patient's calendar
- Request card removed from admin/staff view

### 5. Denial Process
**Endpoint**: `POST /admin/notifications/deny/{id}` or `POST /staff/notifications/deny/{id}`

When admin/staff denies a request:
1. Shows modal asking for denial reason
2. Validates the request is still pending
3. Updates the `AppointmentRequest` to 'Denied' with reason
4. Sends notification to patient:
   - Title: "Appointment Request Denied"
   - Message: Details and reason for denial
   - Type: `appointment_cancelled`

**Result**:
- Patient receives notification with explanation
- Request card removed from admin/staff view
- No appointment created

## Routes

### Patient Routes
```php
Route::get('/patient/calendar', [CalendarController::class, 'index'])->name('patient-calendar');
Route::post('/patient/calendar/submit-request', [CalendarController::class, 'submitRequest'])->name('patient-calendar.submit-request');
```

### Admin Routes
```php
Route::get('/admin/notifications', [AdminNotificationController::class,'index'])->name('admin-notification');
Route::post('/admin/notifications/approve/{id}', [AdminNotificationController::class,'approveRequest'])->name('admin-notification.approve');
Route::post('/admin/notifications/deny/{id}', [AdminNotificationController::class,'denyRequest'])->name('admin-notification.deny');
```

### Staff Routes
```php
Route::get('/staff/notifications', [StaffNotificationController::class,'index'])->name('staff-notification');
Route::post('/staff/notifications/approve/{id}', [StaffNotificationController::class,'approveRequest'])->name('staff-notification.approve');
Route::post('/staff/notifications/deny/{id}', [StaffNotificationController::class,'denyRequest'])->name('staff-notification.deny');
```

## Models

### AppointmentRequest Model
**Location**: `app/Models/AppointmentRequest.php`

**Relationships**:
- `patient()` - BelongsTo User
- `service()` - BelongsTo Service
- `existingAppointment()` - BelongsTo Appointment
- `reviewedBy()` - BelongsTo User

**Helper Methods**:
- `isPending()` - Check if status is Pending
- `isApproved()` - Check if status is Approved
- `isDenied()` - Check if status is Denied
- `isWalkIn()` - Check if type is walk-in
- `isReschedule()` - Check if type is reschedule

**Query Scopes**:
- `pending()` - Get only pending requests
- `approved()` - Get only approved requests
- `denied()` - Get only denied requests
- `walkIn()` - Get only walk-in requests
- `reschedule()` - Get only reschedule requests

## Database Migration
**File**: `database/migrations/2025_10_28_174845_create_appointment_requests_table.php`

Creates the `appointment_requests` table with:
- All necessary fields for tracking requests
- Foreign key constraints to users, services, and appointments
- Proper indexes for performance

## User Interface Highlights

### Patient Calendar Form
- **Toggle Buttons**: Switch between Emergency Walk-in and Reschedule Request
- **Beautiful Gradients**: Modern blue gradient design
- **Form Validation**: Client-side and server-side validation
- **Loading States**: Button shows "Submitting..." while processing
- **Success Notifications**: Bootstrap alerts with auto-dismiss
- **Error Handling**: Displays errors if submission fails

### Admin/Staff Notification Page
- **Header**: Purple gradient header with page title
- **Empty State**: Friendly message when no pending requests
- **Request Cards**: 
  - White cards with hover effects
  - Color-coded left border (purple)
  - Type badge (yellow for walk-in, blue for reschedule)
  - Patient avatar with initial
  - Patient name and ID
  - Request details in grid layout
  - Reason highlighted in yellow box
  - Green "Approve" button
  - Red "Deny" button
- **Smooth Animations**: Cards fade out when processed
- **Toast Notifications**: Success/error messages at top of screen
- **Denial Modal**: Clean modal asking for reason

## Technical Features

### Security
- CSRF token protection on all POST requests
- Authentication required for all routes
- Authorization: Only admin/staff can approve/deny
- Patient can only submit requests for themselves

### User Experience
- Real-time feedback with loading states
- Smooth animations and transitions
- Responsive design for mobile devices
- Auto-dismiss notifications
- Confirmation dialogs before critical actions

### Data Integrity
- Validates request is still pending before processing
- Prevents duplicate processing
- Tracks who reviewed each request
- Stores timestamps for audit trail

### Notifications
- All admin/staff receive notifications when patient submits request
- Patient receives notification when request is approved/denied
- Notifications include relevant appointment details
- Uses existing notification system integration

## Testing the System

### As a Patient:
1. Navigate to the calendar page
2. Scroll to the appointment form section
3. Choose "Emergency Walk-in" or "Request Reschedule"
4. Fill in reason, date, and time
5. Click "Submit Request"
6. See success message
7. Wait for admin/staff to process

### As Admin/Staff:
1. Navigate to the notifications page
2. See pending appointment requests
3. Review patient details and request information
4. Click "Approve" to create appointment (patient will be notified)
5. Or click "Deny", provide reason, and confirm (patient will be notified)
6. Card disappears from view
7. Refresh to see updated count

### Verify Notifications:
- Patient should receive notification in their notification dropdown
- Notification should show in patient notification page
- Notification badge should update count

## Future Enhancements (Optional)

1. **Email Notifications**: Send email when request is approved/denied
2. **SMS Notifications**: Send SMS for urgent walk-in requests
3. **Request History**: Show approved/denied requests in a history tab
4. **Batch Processing**: Approve/deny multiple requests at once
5. **Calendar Integration**: Show requested times on admin calendar
6. **Conflict Detection**: Warn if requested time conflicts with existing appointments
7. **Service Selection**: Let patient choose specific service when requesting
8. **Preferred Dentist**: Allow patient to request specific dentist
9. **Request Modification**: Let patient edit pending requests
10. **Auto-Denial**: Auto-deny requests after certain time period

## Files Modified/Created

### Created:
- `database/migrations/2025_10_28_174845_create_appointment_requests_table.php`
- `app/Models/AppointmentRequest.php`
- `APPOINTMENT_REQUEST_SYSTEM.md` (this file)

### Modified:
- `app/Http/Controllers/Patient/CalendarController.php` - Added submitRequest method
- `app/Http/Controllers/Admin/NotificationController.php` - Added approve/deny methods
- `app/Http/Controllers/Staff/NotificationController.php` - Added approve/deny methods
- `resources/views/patient/calendar.blade.php` - Updated form submission with AJAX
- `resources/views/admin/notification.blade.php` - Created full UI for request management
- `resources/views/staff/notification.blade.php` - Created full UI for request management
- `routes/web.php` - Added new routes for request submission and approval/denial

## Conclusion

This system provides a complete workflow for patients to request appointments and for admin/staff to review and process those requests. The implementation includes:

✅ Patient-facing form with modern UI
✅ Backend validation and processing
✅ Database storage of requests
✅ Notification system for admin/staff
✅ Beautiful admin/staff interface
✅ Approval creates appointment
✅ Denial with reason explanation
✅ Patient notifications for approval/denial
✅ Smooth animations and transitions
✅ Mobile-responsive design
✅ Complete error handling

The system is ready to use and can be extended with additional features as needed.

