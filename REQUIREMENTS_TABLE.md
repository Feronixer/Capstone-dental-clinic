# System Requirements Table
## JValera Dental Clinic Management System

---

## System Requirements (General)

| FRNo. | Requirement Statements | Want/Need | Comments |
|-------|------------------------|-----------|----------|
| **System Requirements** | | | |
| FR-SYS-001 | The system shall provide multi-role access control with three distinct user roles: Patient, Staff, and Admin | Need | Core system architecture requirement |
| FR-SYS-002 | The system shall implement secure authentication using separate guards for admin, staff, and patient portals | Need | Security requirement for role-based access |
| FR-SYS-003 | The system shall provide real-time notification system with in-app notifications and email notifications | Need | Essential for appointment management and communication |
| FR-SYS-004 | The system shall implement activity logging to track all staff actions for audit purposes | Need | Compliance and accountability requirement |
| FR-SYS-005 | The system shall provide calendar functionality with multiple view options (month, week, day) | Need | Core scheduling feature required by all roles |
| FR-SYS-006 | The system shall support appointment status management (Pending, Confirmed, Completed, Cancelled) | Need | Essential workflow requirement |
| FR-SYS-007 | The system shall provide email notification system for appointment confirmations, reminders, cancellations, and rescheduling | Need | Patient communication requirement |
| FR-SYS-008 | The system shall implement chatbot system (ToothTalk) accessible to patients for inquiries | Want | Enhanced patient support feature |
| FR-SYS-009 | The system shall provide content management system for announcements, services, and events | Need | Information dissemination requirement |
| FR-SYS-010 | The system shall support file upload and download functionality for patient records and documents | Need | Document management requirement |
| FR-SYS-011 | The system shall provide export functionality for appointments and patient records (Excel/CSV) | Want | Reporting and data management feature |
| FR-SYS-012 | The system shall implement password reset functionality via email for all user roles | Need | Account security and recovery requirement |
| FR-SYS-013 | The system shall provide dashboard interface with statistics and overview for each role | Need | User experience and information access requirement |
| FR-SYS-014 | The system shall support appointment request system (walk-in and rescheduling requests) | Need | Patient convenience and flexibility requirement |
| FR-SYS-015 | The system shall implement notification filtering and management (All/Unread/Read status) | Want | Enhanced user experience for notification handling |

---

## Admin Requirements

| FRNo. | Requirement Statements | Want/Need | Comments |
|-------|------------------------|-----------|----------|
| **Admin Requirements** | | | |
| FR-A-001 | Admin can login/logout to the system | Need | Basic authentication requirement |
| FR-A-002 | Admin can reset their password using the forgot password feature | Need | Account security requirement |
| FR-A-003 | Admin can modify personal information | Need | Profile management requirement |
| FR-A-004 | Admin can access the dashboard | Need | Overview and statistics access |
| FR-A-005 | Admin can access the appointment scheduler | Need | Core appointment management feature |
| FR-A-006 | Admin can see the visual calendar | Need | Visual scheduling interface |
| FR-A-007 | Admin can select different calendar views (month, week, and day view) | Need | Flexible scheduling view options |
| FR-A-008 | Admin can see appointment status on the calendar | Need | Appointment status visibility |
| FR-A-009 | Admin can see the number of appointments per month, week, and day | Want | Statistics and planning feature |
| FR-A-010 | Admin can block off time schedule | Need | Schedule management for clinic unavailability |
| FR-A-011 | Admin can add, view, approve, edit, cancel, or delete appointments | Need | Complete appointment management control |
| FR-A-012 | Admin can set follow up appointments | Need | Patient care continuity requirement |
| FR-A-013 | Admin can set custom clinic availability hours for a week or month | Need | Schedule configuration requirement |
| FR-A-014 | Admin can access the content management | Need | Content administration access |
| FR-A-015 | Admin can update the announcement | Need | Information dissemination capability |
| FR-A-016 | Admin can add, view, edit, and delete dental services | Need | Service catalog management |
| FR-A-017 | Admin can edit patient mail templates (initial confirmation, reminders, cancellation, rescheduling, and follow ups) | Need | Communication template customization |
| FR-A-018 | Admin can access the post-procedure form | Need | Post-procedural documentation access |
| FR-A-019 | Admin can add, view, edit, & delete forms | Need | Form management capability |
| FR-A-020 | Admin can create, view, edit, and delete patient records | Need | Complete patient record management |
| FR-A-021 | Admin can add, update, and delete medical history entries for patients | Need | Medical history documentation |
| FR-A-022 | Admin can add, update, and delete dental history entries for patients | Need | Dental history documentation |
| FR-A-023 | Admin can create, edit, and delete progress notes | Need | Treatment progress tracking |
| FR-A-024 | Admin can send patient records to patients | Need | Patient record sharing capability |
| FR-A-025 | Admin can search for patients by name, username, email, or patient ID | Need | Patient search functionality |
| FR-A-026 | Admin can view and export patient records | Need | Record access and export capability |
| FR-A-027 | Admin can access the user list | Need | User management access |
| FR-A-028 | Admin can add, view, edit, & delete staff account | Need | Staff account management |
| FR-A-029 | Admin can add, view, edit, & delete patient account | Need | Patient account management |
| FR-A-030 | Admin can change user account passwords (staff and patient) | Need | Account security management |
| FR-A-031 | Admin can be notified if there is a rescheduled appointment | Need | Appointment change notification |
| FR-A-032 | Admin can view appointment requests (walk-in and rescheduling requests) | Need | Request review capability |
| FR-A-033 | Admin can approve or deny appointment requests through notifications | Need | Request approval workflow |
| FR-A-034 | Admin can create new announcements | Need | Announcement creation capability |
| FR-A-035 | Admin can view and manage announcement archives | Need | Announcement history management |
| FR-A-036 | Admin can delete archived announcements | Need | Archive cleanup capability |
| FR-A-037 | Admin can add, view, edit, and delete events | Need | Event management capability |
| FR-A-038 | Admin can update ticker notifications | Need | Ticker message management |
| FR-A-039 | Admin can manage chatbot FAQ entries (ToothTalk) | Need | Chatbot knowledge base management |
| FR-A-040 | Admin can configure chatbot settings (welcome messages, quick intents) | Need | Chatbot customization capability |
| FR-A-041 | Admin can send individual patient emails | Need | Individual communication capability |
| FR-A-042 | Admin can send bulk emails to patients based on situation | Need | Bulk communication capability |
| FR-A-043 | Admin can access activity logs | Need | System audit access |
| FR-A-044 | Admin can view all staff activities (appointments, patient records, content management) | Need | Activity monitoring requirement |
| FR-A-045 | Admin can filter activity logs by staff member, module, action type, and date range | Want | Enhanced audit trail search capability |
| FR-A-046 | Admin can view detailed activity log information (who, what, when, where, changes) | Need | Comprehensive audit trail requirement |
| FR-A-047 | Admin can export appointments to Excel/CSV format | Want | Data export and reporting feature |
| FR-A-048 | Admin can view comprehensive dashboard statistics (total patients, appointments, staff, today's appointments) | Need | System overview and analytics |
| FR-A-049 | Admin can access and manage staff access control settings | Need | Staff permission management requirement |
| FR-A-050 | Admin can configure navigation access controls for staff (dashboard, appointments, user management, content management, post-procedural, toothtalk, live chat, notifications, profile) | Need | Staff navigation permission control |
| FR-A-051 | Admin can configure feature access controls for staff (appointments, user management, announcements, services, events, emails, patient records, chat, file attachments, data export) | Need | Staff feature permission control |
| FR-A-052 | Admin can access live chat conversations | Need | Real-time patient communication access |
| FR-A-053 | Admin can view, send, and manage chat messages with patients | Need | Patient communication management |
| FR-A-054 | Admin can toggle online/offline status for live chat | Need | Chat availability management |
| FR-A-055 | Admin can toggle chat censorship and manage blocklist words | Need | Chat content moderation capability |
| FR-A-056 | Admin can delete chat conversations | Need | Chat conversation management |

---

## Staff Requirements

| FRNo. | Requirement Statements | Want/Need | Comments |
|-------|------------------------|-----------|----------|
| **Staff Requirements** | | | |
| FR-S-001 | Staff can login/logout to the system | Need | Basic authentication requirement |
| FR-S-002 | Staff can reset their password using the forgot password feature | Need | Account security requirement |
| FR-S-003 | Staff can modify personal information | Need | Profile management requirement |
| FR-S-004 | Staff can access the dashboard | Need | Overview and statistics access |
| FR-S-005 | Staff can access the appointment scheduler | Need | Core appointment management feature |
| FR-S-006 | Staff can see the visual calendar | Need | Visual scheduling interface |
| FR-S-007 | Staff can select different calendar views (month, week, and day view) | Need | Flexible scheduling view options |
| FR-S-008 | Staff can see appointment status on the calendar | Need | Appointment status visibility |
| FR-S-009 | Staff can see the number of appointments per month, week, and day | Want | Statistics and planning feature |
| FR-S-010 | Staff can block off time schedule | Need | Schedule management for clinic unavailability |
| FR-S-011 | Staff can add, view, approve, edit, cancel, or delete appointments | Need | Appointment management capability |
| FR-S-012 | Staff can set follow up appointments | Need | Patient care continuity requirement |
| FR-S-013 | Staff can set custom clinic availability hours for a week or month | Need | Schedule configuration requirement |
| FR-S-014 | Staff can access the content management | Need | Content administration access |
| FR-S-015 | Staff can update the announcement | Need | Information dissemination capability |
| FR-S-016 | Staff can add, view, edit, and delete dental services | Need | Service catalog management |
| FR-S-017 | Staff can edit patient mail templates (initial confirmation, reminders, cancellation, rescheduling, and follow ups) | Need | Communication template customization |
| FR-S-018 | Staff can access the post-procedure form | Need | Post-procedural documentation access |
| FR-S-019 | Staff can add, view, edit, & delete forms | Need | Form management capability |
| FR-S-020 | Staff can create, view, edit, and delete patient records | Need | Patient record management |
| FR-S-021 | Staff can add, update, and delete medical history entries for patients | Need | Medical history documentation |
| FR-S-022 | Staff can add, update, and delete dental history entries for patients | Need | Dental history documentation |
| FR-S-023 | Staff can create, edit, and delete progress notes | Need | Treatment progress tracking |
| FR-S-024 | Staff can send patient records to patients | Need | Patient record sharing capability |
| FR-S-025 | Staff can search for patients by name, username, email, or patient ID | Need | Patient search functionality |
| FR-S-026 | Staff can view and export patient records | Need | Record access and export capability |
| FR-S-027 | Staff can access the user list (patient accounts only) | Need | Limited user management (patients only) |
| FR-S-028 | Staff can add, view, edit, & delete patient accounts | Need | Patient account management |
| FR-S-029 | Staff can change patient account passwords | Need | Account security management for patients |
| FR-S-030 | Staff can be notified if there is a rescheduled appointment | Need | Appointment change notification |
| FR-S-031 | Staff can view appointment requests (walk-in and rescheduling requests) | Need | Request review capability |
| FR-S-032 | Staff can approve or deny appointment requests through notifications | Need | Request approval workflow |
| FR-S-033 | Staff can create new announcements | Need | Announcement creation capability |
| FR-S-034 | Staff can view and manage announcement archives | Need | Announcement history management |
| FR-S-035 | Staff can delete archived announcements | Need | Archive cleanup capability |
| FR-S-036 | Staff can add, view, edit, and delete events | Need | Event management capability |
| FR-S-037 | Staff can update ticker notifications | Need | Ticker message management |
| FR-S-038 | Staff can manage chatbot FAQ entries (ToothTalk) | Need | Chatbot knowledge base management |
| FR-S-039 | Staff can configure chatbot settings (welcome messages, quick intents) | Need | Chatbot customization capability |
| FR-S-040 | Staff can send individual patient emails | Need | Individual communication capability |
| FR-S-041 | Staff can send bulk emails to patients based on situation | Need | Bulk communication capability |
| FR-S-042 | Staff can export appointments to Excel/CSV format | Want | Data export and reporting feature |
| FR-S-043 | Staff can view appointment statistics on dashboard | Want | Statistics and analytics feature |
| FR-S-044 | Staff can access live chat conversations | Need | Real-time patient communication access |
| FR-S-045 | Staff can view conversations list and search conversations by patient name, username, or email | Need | Conversation management capability |
| FR-S-046 | Staff can view messages in conversations with patients | Need | Message viewing capability |
| FR-S-047 | Staff can send messages to patients in live chat (with permission) | Need | Patient communication capability |
| FR-S-048 | Staff can attach files to chat messages (with permission) | Need | File sharing capability in chat |
| FR-S-049 | Staff can update conversation status (active, resolved, closed) | Need | Conversation status management |
| FR-S-050 | Staff can view unread conversation count | Need | Unread message tracking |
| FR-S-051 | Staff can toggle online/offline status for live chat | Need | Chat availability management |
| FR-S-052 | Staff can toggle chat censorship on/off | Need | Chat content moderation capability |
| FR-S-053 | Staff can view blocklist words for chat censorship | Need | Blocklist management access |
| FR-S-054 | Staff can add words to chat blocklist | Need | Content filtering capability |
| FR-S-055 | Staff can remove words from chat blocklist | Need | Blocklist maintenance capability |
| FR-S-056 | Staff can save blocklist words in bulk | Need | Bulk blocklist management |

---

## Patient Requirements

| FRNo. | Requirement Statements | Want/Need | Comments |
|-------|------------------------|-----------|----------|
| **Patient Requirements** | | | |
| FR-PTN-001 | Patient can login/logout to the system | Need | Basic authentication requirement |
| FR-PTN-002 | Patient can reset their password using the forgot password feature | Need | Account security requirement |
| FR-PTN-003 | Patient can modify personal information | Need | Profile management requirement |
| FR-PTN-004 | Patient can view the overview page of the clinic (dental services, photos, and video of the clinic) | Need | Clinic information access |
| FR-PTN-005 | Patient can view the announcement | Need | Information access requirement |
| FR-PTN-006 | Patient can view the about us page | Need | Clinic information requirement |
| FR-PTN-007 | Patient can access the calendar | Need | Appointment scheduling access |
| FR-PTN-008 | Patient can track their individual appointments | Need | Appointment visibility requirement |
| FR-PTN-009 | Patient can see the description of the appointment | Need | Appointment detail access |
| FR-PTN-010 | Patient can select different calendar views (month, week, and day view) | Need | Flexible calendar view options |
| FR-PTN-011 | Patient can request a rescheduled appointment | Need | Appointment flexibility requirement |
| FR-PTN-012 | Patient can request walk-in appointments for emergencies or urgent treatments | Need | Emergency appointment capability |
| FR-PTN-013 | Patient can view appointment history (past, current, and upcoming appointments) | Need | Appointment history access |
| FR-PTN-014 | Patient can access the record | Need | Medical record access requirement |
| FR-PTN-015 | Patient can see a preview of the file | Need | File preview capability |
| FR-PTN-016 | Patient can view and download their post-procedural forms | Need | Post-procedural document access |
| FR-PTN-017 | Patient can view medical history and dental history | Need | History access requirement |
| FR-PTN-018 | Patient can view progress notes | Need | Treatment progress visibility |
| FR-PTN-019 | Patient can download patient records, history, and progress notes | Need | Document download capability |
| FR-PTN-020 | Patient can provide user feedback | Want | Feedback collection feature |
| FR-PTN-021 | Patient can rate completed appointments | Want | Service quality assessment feature |
| FR-PTN-022 | Patient can view feedback history | Want | Feedback history access |
| FR-PTN-023 | Patient can be notified on their appointment | Need | Appointment notification requirement |
| FR-PTN-024 | Patient can view, filter (All/Unread/Read), mark as read/unread, and delete notifications | Need | Notification management capability |
| FR-PTN-025 | Patient can access notifications through notification bell icon with unread count | Need | Notification access interface |
| FR-PTN-026 | Patient can receive email notifications for appointment confirmations, reminders, rescheduling, and cancellations | Need | Email communication requirement |
| FR-PTN-027 | Patient can access the chatbot assistant (ToothTalk) | Want | Self-service support feature |
| FR-PTN-028 | Patient can use chatbot for inquiries about clinic hours, services, appointments, and pricing | Want | Information inquiry capability |
| FR-PTN-029 | Patient can use quick action chips in chatbot for common queries | Want | Enhanced chatbot usability |

---

## Summary

### Total Requirements by Category:
- **System (General)**: 15 functional requirements
- **Admin**: 56 functional requirements
- **Staff**: 56 functional requirements
- **Patient**: 29 functional requirements

### Total: 156 Functional Requirements

### Requirements Classification:
- **Need**: Critical requirements essential for system operation (141 requirements)
- **Want**: Desirable features that enhance user experience (15 requirements)

---

*Document Version: 1.0*  
*Last Updated: 2024*  
*Based on JValera Dental Clinic Management System Implementation*


