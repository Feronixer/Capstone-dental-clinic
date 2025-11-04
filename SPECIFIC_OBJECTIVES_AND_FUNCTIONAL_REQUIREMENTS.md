# Specific Objectives and Functional Requirements
## JValera Dental Clinic Management System

---

## Specific Objectives

To develop a web-based system for JValera Dental Clinic with the following capabilities for the users:

The system shall provide comprehensive management capabilities for three distinct user roles: **Patient**, **Staff**, and **Admin**. Each role will have access to specific features tailored to their needs, ensuring efficient clinic operations, improved patient experience, and streamlined administrative tasks.

---

## Functional Requirements

### Patient Functional Requirements

#### Authentication & Account Management
- **FR-P-001**: Patient can login/logout to the system
- **FR-P-002**: Patient can reset their password using the forgot password feature
- **FR-P-003**: Patient can modify personal information

#### Content Viewing
- **FR-P-004**: Patient can view the overview page of the clinic (dental services, photos, and video of the clinic)
- **FR-P-005**: Patient can view the announcement
- **FR-P-006**: Patient can view the about us page

#### Calendar & Appointments
- **FR-P-007**: Patient can access the calendar
- **FR-P-008**: Patient can track their individual appointments
- **FR-P-009**: Patient can see the description of the appointment
- **FR-P-010**: Patient can select different calendar views (month, week, and day view)
- **FR-P-011**: Patient can request a rescheduled appointment
- **FR-P-012**: Patient can request walk-in appointments for emergencies or urgent treatments
- **FR-P-013**: Patient can view appointment history (past, current, and upcoming appointments)

#### Records & Documents
- **FR-P-014**: Patient can access the record
- **FR-P-015**: Patient can see a preview of the file
- **FR-P-016**: Patient can view and download their post-procedural forms
- **FR-P-017**: Patient can view medical history and dental history
- **FR-P-018**: Patient can view progress notes
- **FR-P-019**: Patient can download patient records, history, and progress notes

#### Feedback & Notifications
- **FR-P-020**: Patient can provide user feedback
- **FR-P-021**: Patient can rate completed appointments
- **FR-P-022**: Patient can view feedback history
- **FR-P-023**: Patient can be notified on their appointment
- **FR-P-024**: Patient can view, filter (All/Unread/Read), mark as read/unread, and delete notifications
- **FR-P-025**: Patient can access notifications through notification bell icon with unread count
- **FR-P-026**: Patient can receive email notifications for appointment confirmations, reminders, rescheduling, and cancellations

#### Chatbot & Support
- **FR-P-027**: Patient can access the chatbot assistant (ToothTalk)
- **FR-P-028**: Patient can use chatbot for inquiries about clinic hours, services, appointments, and pricing
- **FR-P-029**: Patient can use quick action chips in chatbot for common queries

---

### Staff Functional Requirements

#### Authentication & Account Management
- **FR-S-001**: Staff can login/logout to the system
- **FR-S-002**: Staff can reset their password using the forgot password feature
- **FR-S-003**: Staff can modify personal information

#### Dashboard Access
- **FR-S-004**: Staff can access the dashboard

#### Appointment Management
- **FR-S-005**: Staff can access the appointment scheduler
- **FR-S-006**: Staff can see the visual calendar
- **FR-S-007**: Staff can select different calendar views (month, week, and day view)
- **FR-S-008**: Staff can see appointment status on the calendar
- **FR-S-009**: Staff can see the number of appointments per month, week, and day
- **FR-S-010**: Staff can block off time schedule
- **FR-S-011**: Staff can add, view, approve, edit, cancel, or delete appointments
- **FR-S-012**: Staff can set follow up appointments
- **FR-S-013**: Staff can set custom clinic availability hours for a week or month

#### Content Management
- **FR-S-014**: Staff can access the content management
- **FR-S-015**: Staff can update the announcement
- **FR-S-016**: Staff can add, view, edit, and delete dental services
- **FR-S-017**: Staff can edit patient mail templates (initial confirmation, reminders, cancellation, rescheduling, and follow ups)

#### Post-Procedure Forms & Patient Records
- **FR-S-018**: Staff can access the post-procedure form
- **FR-S-019**: Staff can add, view, edit, & delete forms
- **FR-S-020**: Staff can create, view, edit, and delete patient records
- **FR-S-021**: Staff can add, update, and delete medical history entries for patients
- **FR-S-022**: Staff can add, update, and delete dental history entries for patients
- **FR-S-023**: Staff can create, edit, and delete progress notes
- **FR-S-024**: Staff can send patient records to patients
- **FR-S-025**: Staff can search for patients by name, username, email, or patient ID
- **FR-S-026**: Staff can view and export patient records

#### Account Management
- **FR-S-027**: Staff can access the user list (patient accounts only)
- **FR-S-028**: Staff can add, view, edit, & delete patient accounts
- **FR-S-029**: Staff can change patient account passwords

#### Notifications
- **FR-S-030**: Staff can be notified if there is a rescheduled appointment
- **FR-S-031**: Staff can view appointment requests (walk-in and rescheduling requests)
- **FR-S-032**: Staff can approve or deny appointment requests through notifications

#### Content Management (Extended)
- **FR-S-033**: Staff can create new announcements
- **FR-S-034**: Staff can view and manage announcement archives
- **FR-S-035**: Staff can delete archived announcements
- **FR-S-036**: Staff can add, view, edit, and delete events
- **FR-S-037**: Staff can update ticker notifications
- **FR-S-038**: Staff can manage chatbot FAQ entries (ToothTalk)
- **FR-S-039**: Staff can configure chatbot settings (welcome messages, quick intents)
- **FR-S-040**: Staff can send individual patient emails
- **FR-S-041**: Staff can send bulk emails to patients based on situation

#### Reporting & Export
- **FR-S-042**: Staff can export appointments to Excel/CSV format
- **FR-S-043**: Staff can view appointment statistics on dashboard

---

### Admin Functional Requirements

#### Authentication & Account Management
- **FR-A-001**: Admin can login/logout to the system
- **FR-A-002**: Admin can reset their password using the forgot password feature
- **FR-A-003**: Admin can modify personal information

#### Dashboard Access
- **FR-A-004**: Admin can access the dashboard

#### Appointment Management
- **FR-A-005**: Admin can access the appointment scheduler
- **FR-A-006**: Admin can see the visual calendar
- **FR-A-007**: Admin can select different calendar views (month, week, and day view)
- **FR-A-008**: Admin can see appointment status on the calendar
- **FR-A-009**: Admin can see the number of appointments per month, week, and day
- **FR-A-010**: Admin can block off time schedule
- **FR-A-011**: Admin can add, view, approve, edit, cancel, or delete appointments
- **FR-A-012**: Admin can set follow up appointments
- **FR-A-013**: Admin can set custom clinic availability hours for a week or month

#### Content Management
- **FR-A-014**: Admin can access the content management
- **FR-A-015**: Admin can update the announcement
- **FR-A-016**: Admin can add, view, edit, and delete dental services
- **FR-A-017**: Admin can edit patient mail templates (initial confirmation, reminders, cancellation, rescheduling, and follow ups)

#### Post-Procedure Forms & Patient Records
- **FR-A-018**: Admin can access the post-procedure form
- **FR-A-019**: Admin can add, view, edit, & delete forms
- **FR-A-020**: Admin can create, view, edit, and delete patient records
- **FR-A-021**: Admin can add, update, and delete medical history entries for patients
- **FR-A-022**: Admin can add, update, and delete dental history entries for patients
- **FR-A-023**: Admin can create, edit, and delete progress notes
- **FR-A-024**: Admin can send patient records to patients
- **FR-A-025**: Admin can search for patients by name, username, email, or patient ID
- **FR-A-026**: Admin can view and export patient records

#### Account Management
- **FR-A-027**: Admin can access the user list
- **FR-A-028**: Admin can add, view, edit, & delete staff account
- **FR-A-029**: Admin can add, view, edit, & delete patient account
- **FR-A-030**: Admin can change user account passwords (staff and patient)

#### Notifications
- **FR-A-031**: Admin can be notified if there is a rescheduled appointment
- **FR-A-032**: Admin can view appointment requests (walk-in and rescheduling requests)
- **FR-A-033**: Admin can approve or deny appointment requests through notifications

#### Content Management (Extended)
- **FR-A-034**: Admin can create new announcements
- **FR-A-035**: Admin can view and manage announcement archives
- **FR-A-036**: Admin can delete archived announcements
- **FR-A-037**: Admin can add, view, edit, and delete events
- **FR-A-038**: Admin can update ticker notifications
- **FR-A-039**: Admin can manage chatbot FAQ entries (ToothTalk)
- **FR-A-040**: Admin can configure chatbot settings (welcome messages, quick intents)
- **FR-A-041**: Admin can send individual patient emails
- **FR-A-042**: Admin can send bulk emails to patients based on situation

#### Activity Logs & Monitoring
- **FR-A-043**: Admin can access activity logs
- **FR-A-044**: Admin can view all staff activities (appointments, patient records, content management)
- **FR-A-045**: Admin can filter activity logs by staff member, module, action type, and date range
- **FR-A-046**: Admin can view detailed activity log information (who, what, when, where, changes)

#### Reporting & Export
- **FR-A-047**: Admin can export appointments to Excel/CSV format
- **FR-A-048**: Admin can view comprehensive dashboard statistics (total patients, appointments, staff, today's appointments)

---

## Summary

### Total Functional Requirements by Role:
- **Patient**: 29 functional requirements
- **Staff**: 43 functional requirements
- **Admin**: 48 functional requirements

### Common Features Across Roles:
- Authentication and password reset functionality
- Personal information modification
- Appointment scheduling and calendar management
- Content management capabilities
- Notification system integration
- Calendar view options (month, week, day view)
- Appointment request handling (walk-in and rescheduling)

### Unique Features:

#### Patient:
- Feedback submission and rating system
- Record preview and download capabilities
- Post-procedural form viewing
- Chatbot access (ToothTalk)
- Notification management (filter, mark as read/unread)

#### Staff:
- Patient account management (limited to patient accounts)
- Patient record access and management
- Content management (announcements, services, events, mail templates)
- Chatbot FAQ and settings management
- Appointment export functionality
- Email sending capabilities (individual and bulk)

#### Admin:
- Complete user management (staff and patient accounts)
- Activity logs monitoring and tracking
- Comprehensive dashboard statistics
- Full system access and configuration
- All staff capabilities plus administrative oversight

### Additional System Features:

#### Chatbot System (ToothTalk):
- Accessible to all patients through dashboard
- FAQ management by Staff and Admin
- Customizable welcome messages and quick intents
- Context-aware responses for appointments, services, hours, and pricing

#### Notification System:
- Real-time in-app notifications
- Email notifications for appointments
- Notification dropdown with unread count
- Request approval/denial system
- Notification filtering and management

#### Activity Logging:
- Automatic tracking of all staff activities
- Logs for appointments, patient records, content management
- Detailed change tracking (before/after values)
- Filtering and search capabilities
- IP address and user agent tracking

#### Email Management:
- Customizable mail templates (confirmation, reminders, cancellation, rescheduling, follow-ups)
- Individual patient email sending
- Bulk email sending based on patient situation
- Template editing by Staff and Admin

#### Reporting & Export:
- Appointment export to Excel/CSV
- Patient record export capabilities
- Dashboard statistics and analytics
- Appointment statistics per month/week/day

---

*Document Version: 2.0*  
*Last Updated: 2024*  
*Based on actual system implementation*

