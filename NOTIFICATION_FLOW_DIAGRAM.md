# Notification System Flow Diagram

## System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    ADMIN/STAFF ACTIONS                       │
└─────────────────────────────────────────────────────────────┘
                            │
        ┌───────────────────┼───────────────────┐
        │                   │                   │
        ▼                   ▼                   ▼
┌───────────────┐  ┌───────────────┐  ┌───────────────┐
│  Appointment  │  │  Appointment  │  │ Patient Record│
│   Actions     │  │    Reschedule │  │    Updates    │
└───────────────┘  └───────────────┘  └───────────────┘
        │                   │                   │
        ▼                   ▼                   ▼
┌───────────────────────────────────────────────────────────┐
│              NotificationService                           │
│  ┌─────────────────────────────────────────────────────┐  │
│  │ • appointmentConfirmed()                            │  │
│  │ • appointmentRescheduled()                          │  │
│  │ • appointmentCancelled()                            │  │
│  │ • recordUpdated()                                   │  │
│  └─────────────────────────────────────────────────────┘  │
└───────────────────────────────────────────────────────────┘
                            │
                            ▼
                  ┌──────────────────┐
                  │   Notifications  │
                  │      Table       │
                  │   (Database)     │
                  └──────────────────┘
                            │
                            ▼
        ┌──────────────────┴──────────────────┐
        │                                      │
        ▼                                      ▼
┌──────────────────┐              ┌──────────────────┐
│ Patient Header   │              │  Notifications   │
│   Dropdown       │              │      Page        │
│ (5 most recent)  │              │  (Full List)     │
└──────────────────┘              └──────────────────┘
        │                                      │
        └──────────────────┬──────────────────┘
                           │
                           ▼
                  ┌──────────────────┐
                  │   Patient Views  │
                  │   Notification   │
                  └──────────────────┘
```

## Detailed Flow: Appointment Actions

### 1. Create Appointment
```
Admin/Staff → Creates Appointment
              ↓
       Save to Database
              ↓
       MailService sends confirmation email
              ↓
       NotificationService.appointmentConfirmed()
              ↓
       Create notification record
              ↓
       Patient sees notification in:
       - Header dropdown (red badge)
       - Notifications page (marked unread)
```

### 2. Reschedule Appointment
```
Admin/Staff → Updates Appointment DateTime
              ↓
       Capture old datetime
              ↓
       Update appointment in database
              ↓
       MailService sends reschedule email
              ↓
       NotificationService.appointmentRescheduled(appointment, oldDateTime)
              ↓
       Create notification with old & new times
              ↓
       Patient sees:
       - "Rescheduled from [old] to [new]"
       - Blue icon with arrow
```

### 3. Cancel Appointment
```
Admin/Staff → Deletes Appointment
              ↓
       MailService sends cancellation email
              ↓
       NotificationService.appointmentCancelled()
              ↓
       Create cancellation notification
              ↓
       Delete appointment from database
              ↓
       Patient sees:
       - "Appointment cancelled" message
       - Red X icon
```

## Detailed Flow: Record Updates

### 1. Patient Record Update
```
Admin/Staff → Updates Patient Record
              ↓
       Validate and save to database
              ↓
       NotificationService.recordUpdated(userId, 'medical record')
              ↓
       Create notification record
              ↓
       Patient sees:
       - "Your medical record has been updated"
       - Purple file icon
```

### 2. Patient History Update
```
Admin/Staff → Updates Patient History
              ↓
       Save history to database
              ↓
       Mark parent record as sent
              ↓
       NotificationService.recordUpdated(userId, 'medical history')
              ↓
       Patient sees:
       - "Your medical history has been updated"
       - Purple clock icon
```

### 3. Progress Note Added
```
Admin/Staff → Adds Progress Note
              ↓
       Save note to database
              ↓
       NotificationService.recordUpdated(userId, 'progress note')
              ↓
       Patient sees:
       - "A new progress note has been added"
       - Purple file text icon
```

## Notification States

```
┌──────────────┐
│   Created    │  Initial state when notification is generated
└──────┬───────┘
       │
       ▼
┌──────────────┐
│   Unread     │  Appears in header with red badge
└──────┬───────┘  Shows in "Unread" filter
       │          Blue background highlight
       ▼
┌──────────────┐
│    Read      │  Badge count decreases
└──────┬───────┘  White background
       │          Shows in "Read" filter
       ▼
┌──────────────┐
│   Deleted    │  Removed from database
└──────────────┘  No longer visible
```

## User Interaction Flow

```
Patient logs in
       │
       ▼
Header loads → Fetch unread count → Display badge (e.g., "5")
       │
       ▼
Patient clicks bell icon
       │
       ▼
Dropdown opens → Fetch 5 recent notifications
       │
       ├─ Unread notifications (blue background)
       └─ Read notifications (white background)
       │
       ▼
Patient clicks "View All"
       │
       ▼
Notifications Page
       │
       ├─ Filter: All | Unread | Read
       ├─ Actions: Mark all read | Clear read
       └─ Per notification: Mark read/unread | Delete
       │
       ▼
Patient clicks notification
       │
       └─ Auto marks as read
          └─ Badge count updates
```

## Backend Controllers Modified

```
┌─────────────────────────────────────────────────────┐
│           Admin Controllers                         │
├─────────────────────────────────────────────────────┤
│ AppointmentController                               │
│  ├─ store() → appointmentConfirmed                  │
│  ├─ update() → appointmentRescheduled              │
│  └─ destroy() → appointmentCancelled               │
│                                                     │
│ PostProceduralController                            │
│  ├─ storePatientRecord() → recordUpdated           │
│  ├─ storePatientHistory() → recordUpdated          │
│  └─ storeProgressNote() → recordUpdated            │
└─────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────┐
│           Staff Controllers                         │
├─────────────────────────────────────────────────────┤
│ AppointmentController                               │
│  ├─ store() → appointmentConfirmed                  │
│  ├─ update() → appointmentRescheduled              │
│  └─ destroy() → appointmentCancelled               │
│                                                     │
│ PostProceduralController                            │
│  ├─ storePatientRecord() → recordUpdated           │
│  ├─ storePatientHistory() → recordUpdated          │
│  └─ storeProgressNote() → recordUpdated            │
└─────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────┐
│         Patient Controllers                         │
├─────────────────────────────────────────────────────┤
│ NotificationController                              │
│  ├─ index() → Display all notifications             │
│  ├─ getRecent() → Fetch for dropdown               │
│  ├─ getUnreadCount() → Badge count                 │
│  ├─ markAsRead() → Mark single as read             │
│  ├─ markAsUnread() → Mark single as unread         │
│  ├─ markAllAsRead() → Mark all as read             │
│  ├─ destroy() → Delete single notification         │
│  └─ clearRead() → Delete all read notifications    │
└─────────────────────────────────────────────────────┘
```

## Data Flow

```
Controller Action
       ↓
Business Logic Execution
       ↓
Database Update
       ↓
Email Notification (if applicable)
       ↓
In-App Notification Creation ← NotificationService
       ↓
Notification Saved to Database
       ↓
Patient UI Auto-Refreshes (on next page load)
       ↓
Patient Sees Notification
       ↓
Patient Interacts (read/delete)
       ↓
Notification State Updated
```

## Error Handling

```
Controller Method
       │
       ▼
    try {
       │
       ├─ Main Operation (appointment/record update)
       │
       ├─ Email Service (wrapped in try-catch)
       │  └─ Log error if fails, continue
       │
       └─ Notification Service (wrapped in try-catch)
          └─ Log error if fails, continue
    }
    catch {
       └─ Return error to user
    }

Note: Notification failures don't affect main operations
```

## Summary

✅ **6 notification types** implemented
✅ **4 controllers** integrated
✅ **8 trigger points** active
✅ **Error handling** for all notification calls
✅ **Email + In-app** notifications working
✅ **Frontend UI** fully connected
✅ **Database** schema ready

