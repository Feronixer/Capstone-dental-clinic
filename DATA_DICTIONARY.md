# Data Dictionary: Dental Clinic Management System

This document provides a comprehensive data dictionary for all database tables in the Dental Clinic Management System.

---

## Table of Contents

1. [User Management Tables](#user-management-tables)
2. [Appointment Management Tables](#appointment-management-tables)
3. [Patient Records Tables](#patient-records-tables)
4. [Service Management Tables](#service-management-tables)
5. [Content Management Tables](#content-management-tables)
6. [Chat System Tables](#chat-system-tables)
7. [Notification & Communication Tables](#notification--communication-tables)
8. [System & Configuration Tables](#system--configuration-tables)
9. [Laravel Framework Tables](#laravel-framework-tables)

---

## User Management Tables

### users

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| id | bigint(20) unsigned | Primary Key, unique user ID |
| name | varchar(255) | User's full name |
| role_id | int(11) | Foreign key to roles table, nullable |
| username | varchar(255) | Unique username for login |
| email | varchar(255) | Unique email address |
| email_verified_at | timestamp | Timestamp when email was verified, nullable |
| password | varchar(255) | Hashed password |
| profile_picture | varchar(255) | Path to profile picture file, nullable |
| must_change_password | boolean | Flag indicating if user must change password on next login, default: true |
| remember_token | varchar(100) | Token for "remember me" functionality, nullable |
| created_at | timestamp | Timestamp of when the user was created |
| updated_at | timestamp | Timestamp of when the user was last updated |

**Foreign Keys:**
- `role_id` → `roles.id`

---

### user_infos

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| id | bigint(20) unsigned | Primary Key, unique user info ID |
| user_id | int(11) | Foreign key to users table |
| first_name | varchar(255) | User's first name, nullable |
| middle_name | varchar(255) | User's middle name, nullable |
| last_name | varchar(255) | User's last name, nullable |
| phone | varchar(255) | Contact phone number, nullable |
| address | varchar(255) | User's address, nullable |
| age | int(11) | User's age, nullable |
| gender | varchar(255) | User's gender, nullable |
| birthday | date | User's date of birth, nullable |
| created_at | timestamp | Timestamp of when the record was created |
| updated_at | timestamp | Timestamp of when the record was last updated |

**Foreign Keys:**
- `user_id` → `users.id`

---

### roles

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| id | bigint(20) unsigned | Primary Key, unique role ID |
| role | varchar(255) | Role name (e.g., Admin, Staff, Patient) |
| created_by | int(11) unsigned | User ID who created the role, nullable |
| updated_by | int(11) unsigned | User ID who last updated the role, nullable |
| created_at | timestamp | Timestamp of when the role was created |
| updated_at | timestamp | Timestamp of when the role was last updated |

---

### staff_access_controls

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| id | bigint(20) unsigned | Primary Key, unique access control ID |
| staff_id | bigint(20) unsigned | Foreign key to users table (staff member) |
| access_dashboard | boolean | Access to dashboard, default: true |
| access_appointments | boolean | Access to appointments module, default: true |
| access_user_management | boolean | Access to user management, default: true |
| access_content_management | boolean | Access to content management, default: true |
| access_post_procedural | boolean | Access to post-procedural module, default: true |
| access_toothtalk | boolean | Access to ToothTalk chat system, default: true |
| access_live_chat | boolean | Access to live chat, default: true |
| access_notifications | boolean | Access to notifications, default: true |
| access_profile | boolean | Access to profile, default: true |
| can_create_appointments | boolean | Permission to create appointments, default: true |
| can_edit_appointments | boolean | Permission to edit appointments, default: true |
| can_delete_appointments | boolean | Permission to delete appointments, default: true |
| can_update_appointment_status | boolean | Permission to update appointment status, default: true |
| can_view_all_appointments | boolean | Permission to view all appointments, default: true |
| can_create_users | boolean | Permission to create users, default: true |
| can_edit_users | boolean | Permission to edit users, default: true |
| can_delete_users | boolean | Permission to delete users, default: true |
| can_create_announcements | boolean | Permission to create announcements, default: true |
| can_edit_announcements | boolean | Permission to edit announcements, default: true |
| can_delete_announcements | boolean | Permission to delete announcements, default: true |
| can_manage_announcements | boolean | Permission to manage announcements, default: true |
| can_delete_archives | boolean | Permission to delete archives, default: true |
| can_manage_services | boolean | Permission to manage services, default: true |
| can_manage_events | boolean | Permission to manage events, default: true |
| can_send_emails | boolean | Permission to send emails, default: true |
| can_manage_mails | boolean | Permission to manage mail templates, default: true |
| can_view_patient_records | boolean | Permission to view patient records, default: true |
| can_create_patient_records | boolean | Permission to create patient records, default: true |
| can_edit_patient_records | boolean | Permission to edit patient records, default: true |
| can_delete_patient_records | boolean | Permission to delete patient records, default: true |
| can_respond_to_chat | boolean | Permission to respond to chat messages, default: true |
| can_attach_files | boolean | Permission to attach files in chat, default: true |
| can_export_data | boolean | Permission to export data, default: true |
| created_at | timestamp | Timestamp of when the record was created |
| updated_at | timestamp | Timestamp of when the record was last updated |

**Foreign Keys:**
- `staff_id` → `users.id` (on delete: cascade)

**Unique Constraints:**
- `staff_id` (one access control per staff member)

---

## Appointment Management Tables

### appointments

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| id | bigint(20) unsigned | Primary Key, unique appointment ID |
| patient_id | bigint(20) unsigned | Foreign key to users table (patient), nullable |
| service_id | bigint(20) unsigned | Foreign key to services table, nullable |
| start_datetime | datetime | Appointment start date and time |
| duration_minutes | int(11) | Duration of appointment in minutes, default: 30 |
| end_datetime | datetime | Appointment end date and time |
| status | varchar(50) | Appointment status (Pending, Confirmed, Completed, Cancelled, etc.), default: 'Pending' |
| notes | text | Additional notes about the appointment, nullable |
| reason_for_visit | varchar(255) | Reason for the visit, nullable |
| is_new_patient | boolean | Flag indicating if this is a new patient, default: false |
| rating | tinyint(4) | Patient rating (1-5 stars), nullable |
| patient_feedback | text | Patient feedback/comments, nullable |
| rated_at | timestamp | Timestamp when patient submitted rating, nullable |
| feedback_comment | text | Additional feedback comment, nullable |
| feedback_submitted_at | timestamp | Timestamp when feedback was submitted, nullable |
| rescheduled_at | timestamp | Timestamp when appointment was rescheduled, nullable |
| original_datetime | datetime | Original appointment datetime before reschedule, nullable |
| reminder_24h_sent | boolean | Flag indicating if 24-hour reminder was sent, default: false |
| reminder_3h_sent | boolean | Flag indicating if 3-hour reminder was sent, default: false |
| reminder_24h_sent_at | timestamp | Timestamp when 24-hour reminder was sent, nullable |
| reminder_3h_sent_at | timestamp | Timestamp when 3-hour reminder was sent, nullable |
| created_at | timestamp | Timestamp of when the appointment was created |
| updated_at | timestamp | Timestamp of when the appointment was last updated |

**Foreign Keys:**
- `patient_id` → `users.id` (on delete: cascade)
- `service_id` → `services.id` (on delete: set null)

---

### appointment_requests

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| id | bigint(20) unsigned | Primary Key, unique appointment request ID |
| patient_id | bigint(20) unsigned | Foreign key to users table (patient) |
| service_id | bigint(20) unsigned | Foreign key to services table, nullable |
| other_concern | varchar(255) | Other concern/service not in list, nullable |
| existing_appointment_id | bigint(20) unsigned | Foreign key to appointments table (for reschedule requests), nullable |
| request_type | enum | Type of request: 'walk-in', 'reschedule', or 'book', default: 'walk-in' |
| requested_datetime | datetime | Requested appointment start date and time |
| requested_end_datetime | datetime | Requested appointment end date and time |
| duration_minutes | int(11) | Duration of appointment in minutes, default: 30 |
| reason | text | Reason for the request, nullable |
| notes | text | Additional notes, nullable |
| status | enum | Request status: 'Pending', 'Approved', or 'Denied', default: 'Pending' |
| reviewed_by | bigint(20) unsigned | Foreign key to users table (admin/staff who reviewed), nullable |
| review_notes | text | Notes explaining approval/denial, nullable |
| reviewed_at | datetime | Timestamp when request was reviewed, nullable |
| created_at | timestamp | Timestamp of when the request was created |
| updated_at | timestamp | Timestamp of when the request was last updated |

**Foreign Keys:**
- `patient_id` → `users.id` (on delete: cascade)
- `service_id` → `services.id` (on delete: set null)
- `existing_appointment_id` → `appointments.id` (on delete: cascade)
- `reviewed_by` → `users.id` (on delete: set null)

---

### blocked_times

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| id | bigint(20) unsigned | Primary Key, unique blocked time ID |
| title | varchar(255) | Title/description of blocked time, default: 'Blocked Time' |
| start_datetime | datetime | Start date and time of blocked period |
| end_datetime | datetime | End date and time of blocked period |
| duration_minutes | int(11) | Duration in minutes |
| notes | text | Additional notes about the blocked time, nullable |
| created_at | timestamp | Timestamp of when the record was created |
| updated_at | timestamp | Timestamp of when the record was last updated |

**Indexes:**
- `start_datetime`, `end_datetime` (for faster queries)

---

## Patient Records Tables

### patient_records

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| id | bigint(20) unsigned | Primary Key, unique patient record ID |
| user_id | bigint(20) unsigned | Foreign key to users table (patient) |
| appointment_id | bigint(20) unsigned | Foreign key to appointments table, nullable |
| patient_number | varchar(255) | Unique patient number/identifier |
| home_address | varchar(255) | Patient's home address, nullable |
| date_of_birth | date | Patient's date of birth, nullable |
| age | int(11) | Patient's age, nullable |
| sex | varchar(255) | Patient's sex, nullable |
| nickname | varchar(255) | Patient's nickname, nullable |
| religion | varchar(255) | Patient's religion, nullable |
| occupation | varchar(255) | Patient's occupation, nullable |
| contact | varchar(255) | Patient's contact information, nullable |
| guardian_name | varchar(255) | Guardian name (for minors), nullable |
| guardian_contact | varchar(255) | Guardian contact information, nullable |
| guardian_occupation | varchar(255) | Guardian occupation, nullable |
| medical_history | text | Patient's medical history, nullable |
| allergies | text | Patient's allergies, nullable |
| allergies_detail | json | Detailed allergy information in JSON format, nullable |
| current_medications | text | Current medications, nullable |
| chief_complaint | text | Chief complaint, nullable |
| diagnosis | text | Diagnosis, nullable |
| treatment_plan | text | Treatment plan, nullable |
| other_notes | text | Other notes, nullable |
| sent_to_patient | boolean | Flag indicating if record was sent to patient, default: false |
| sent_at | timestamp | Timestamp when record was sent to patient, nullable |
| previous_dentist | varchar(255) | Previous dentist name, nullable |
| last_dental_visit | date | Date of last dental visit, nullable |
| treatment_done | text | Treatment done previously, nullable |
| physician_name | varchar(255) | Physician name, nullable |
| physician_specialty | varchar(255) | Physician specialty, nullable |
| physician_office_address | varchar(255) | Physician office address, nullable |
| physician_contact | varchar(255) | Physician contact information, nullable |
| health_questions | json | Health questions in JSON format, nullable |
| is_pregnant | boolean | Flag for women's health: is pregnant, nullable |
| is_nursing | boolean | Flag for women's health: is nursing, nullable |
| takes_birth_control | boolean | Flag for women's health: takes birth control, nullable |
| created_at | timestamp | Timestamp of when the record was created |
| updated_at | timestamp | Timestamp of when the record was last updated |

**Foreign Keys:**
- `user_id` → `users.id` (on delete: cascade)
- `appointment_id` → `appointments.id` (on delete: set null)

**Unique Constraints:**
- `patient_number`

---

### patient_histories

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| id | bigint(20) unsigned | Primary Key, unique patient history ID |
| patient_record_id | bigint(20) unsigned | Foreign key to patient_records table |
| visit_date | date | Date of the visit |
| previous_dentist | varchar(255) | Previous dentist name, nullable |
| last_dental_visit | date | Date of last dental visit, nullable |
| treatment_done | text | Treatment done, nullable |
| physician_name | varchar(255) | Physician name, nullable |
| physician_specialty | varchar(255) | Physician specialty, nullable |
| physician_office_address | text | Physician office address, nullable |
| physician_contact | varchar(255) | Physician contact information, nullable |
| good_health | varchar(255) | Health status question: good health, nullable |
| under_treatment | varchar(255) | Health status question: under treatment, nullable |
| treatment_condition | text | Treatment condition details, nullable |
| serious_illness | varchar(255) | Health status question: serious illness, nullable |
| illness_details | text | Illness details, nullable |
| been_hospitalized | varchar(255) | Health status question: been hospitalized, nullable |
| hospitalization_reason | text | Hospitalization reason, nullable |
| taking_drugs | varchar(255) | Health status question: taking drugs, nullable |
| medications | text | Medications list, nullable |
| tobacco_use | varchar(255) | Tobacco use status, nullable |
| alcohol_use | varchar(255) | Alcohol use status, nullable |
| recreational_drugs | varchar(255) | Recreational drugs use status, nullable |
| allergy_anesthesia | boolean | Allergy to anesthesia, default: false |
| allergy_sulfa | boolean | Allergy to sulfa, default: false |
| allergy_antibiotics | boolean | Allergy to antibiotics, default: false |
| allergy_aspirin | boolean | Allergy to aspirin, default: false |
| allergy_analgesics | boolean | Allergy to analgesics, default: false |
| allergy_latex | boolean | Allergy to latex, default: false |
| food_allergy_details | text | Food allergy details, nullable |
| other_allergy_details | text | Other allergy details, nullable |
| is_pregnant | varchar(255) | Women's health: is pregnant, nullable |
| is_nursing | varchar(255) | Women's health: is nursing, nullable |
| birth_control | varchar(255) | Women's health: birth control, nullable |
| procedure_performed | text | Procedure performed during visit, nullable |
| materials_used | text | Materials used during procedure, nullable |
| anesthesia_used | text | Anesthesia used, nullable |
| complications | text | Complications encountered, nullable |
| post_operative_instructions | text | Post-operative instructions, nullable |
| follow_up_notes | text | Follow-up notes, nullable |
| sent_to_patient | boolean | Flag indicating if history was sent to patient, default: false |
| sent_at | timestamp | Timestamp when history was sent to patient, nullable |
| created_by_user_id | bigint(20) unsigned | Foreign key to users table (who created the record), nullable |
| created_by_role | varchar(255) | Role of creator ('admin' or 'staff'), nullable |
| created_at | timestamp | Timestamp of when the record was created |
| updated_at | timestamp | Timestamp of when the record was last updated |

**Foreign Keys:**
- `patient_record_id` → `patient_records.id` (on delete: cascade)
- `created_by_user_id` → `users.id`

---

### progress_notes

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| id | bigint(20) unsigned | Primary Key, unique progress note ID |
| patient_record_id | bigint(20) unsigned | Foreign key to patient_records table |
| appointment_id | bigint(20) unsigned | Foreign key to appointments table, nullable |
| note_date | date | Date of the progress note |
| progress_description | text | Description of patient progress |
| treatment_response | text | Patient's response to treatment, nullable |
| amount_paid | decimal(10,2) | Amount paid by patient, nullable |
| balance | decimal(10,2) | Remaining balance, nullable |
| conforme | varchar(255) | Patient signature/conforme, nullable |
| next_steps | text | Next steps in treatment, nullable |
| other_notes | text | Other notes, nullable |
| status | varchar(255) | Status: 'ongoing', 'completed', or 'followup_needed', default: 'ongoing' |
| created_by_user_id | bigint(20) unsigned | Foreign key to users table (who created the note), nullable |
| created_by_role | varchar(255) | Role of creator ('admin' or 'staff'), nullable |
| created_at | timestamp | Timestamp of when the note was created |
| updated_at | timestamp | Timestamp of when the note was last updated |

**Foreign Keys:**
- `patient_record_id` → `patient_records.id` (on delete: cascade)
- `appointment_id` → `appointments.id` (on delete: cascade)
- `created_by_user_id` → `users.id`

---

## Service Management Tables

### services

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| id | bigint(20) unsigned | Primary Key, unique service ID |
| service_name | varchar(255) | Name of the service |
| description | text | Service description, nullable |
| price | decimal(10,2) | Service price, nullable |
| price_notes | varchar(100) | Notes about pricing, nullable |
| default_duration_minutes | int(11) | Default duration in minutes, default: 30 |
| icon_class | varchar(100) | CSS icon class for display, nullable |
| is_active | boolean | Flag indicating if service is active, default: true |
| created_at | timestamp | Timestamp of when the service was created |
| updated_at | timestamp | Timestamp of when the service was last updated |

---

## Content Management Tables

### announcements

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| id | bigint(20) unsigned | Primary Key, unique announcement ID |
| title | varchar(255) | Announcement title |
| subheading | varchar(255) | Announcement subheading, nullable |
| content | text | Announcement content |
| image_path | varchar(255) | Path to announcement image, nullable |
| date_start | date | Start date of announcement, nullable |
| date_end | date | End date of announcement, nullable |
| time_start | time | Start time of announcement, nullable |
| time_end | time | End time of announcement, nullable |
| is_whole_day | boolean | Flag indicating if announcement is for whole day, default: false |
| ticker_text | text | Text to display in ticker, nullable |
| show_ticker | boolean | Flag indicating if ticker should be shown, default: true |
| is_active | boolean | Flag indicating if announcement is active, default: true |
| created_at | timestamp | Timestamp of when the announcement was created |
| updated_at | timestamp | Timestamp of when the announcement was last updated |

---

### announcement_archives

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| id | bigint(20) unsigned | Primary Key, unique archive ID |
| announcement_id | bigint(20) unsigned | Foreign key to announcements table, nullable |
| title | varchar(255) | Archived announcement title |
| subheading | varchar(255) | Archived announcement subheading, nullable |
| content | text | Archived announcement content |
| image_path | varchar(255) | Path to archived announcement image, nullable |
| date_start | date | Start date of archived announcement, nullable |
| date_end | date | End date of archived announcement, nullable |
| time_start | time | Start time of archived announcement, nullable |
| time_end | time | End time of archived announcement, nullable |
| is_whole_day | boolean | Flag indicating if archived announcement is for whole day, default: false |
| ticker_text | text | Text to display in ticker, nullable |
| show_ticker | boolean | Flag indicating if ticker should be shown, default: true |
| is_active | boolean | Flag indicating if archived announcement is active, default: true |
| archived_by | bigint(20) unsigned | Foreign key to users table (who archived it), nullable |
| archived_at | timestamp | Timestamp when announcement was archived |
| created_at | timestamp | Timestamp of when the archive was created |
| updated_at | timestamp | Timestamp of when the archive was last updated |

**Foreign Keys:**
- `announcement_id` → `announcements.id` (on delete: set null)
- `archived_by` → `users.id` (on delete: set null)

---

### events

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| id | bigint(20) unsigned | Primary Key, unique event ID |
| title | varchar(255) | Event title |
| description | text | Event description |
| event_date | date | Date of the event |
| event_time | time | Time of the event, nullable |
| location | varchar(255) | Event location, nullable |
| event_type | varchar(255) | Event type: 'upcoming', 'past', or 'featured', default: 'upcoming' |
| is_active | boolean | Flag indicating if event is active, default: true |
| image_path | varchar(255) | Path to event image, nullable |
| created_at | timestamp | Timestamp of when the event was created |
| updated_at | timestamp | Timestamp of when the event was last updated |

---

### mail_templates

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| id | bigint(20) unsigned | Primary Key, unique mail template ID |
| type | varchar(255) | Template type: 'initial_confirmation', 'reminder', 'cancellation', 'rescheduling', 'follow_up', unique |
| subject | varchar(255) | Email subject line |
| content | text | Email content/template |
| created_at | timestamp | Timestamp of when the template was created |
| updated_at | timestamp | Timestamp of when the template was last updated |

**Unique Constraints:**
- `type`

---

## Chat System Tables

### chatbot_settings

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| id | bigint(20) unsigned | Primary Key, unique chatbot setting ID |
| enabled | boolean | Flag indicating if chatbot is enabled, default: true |
| is_online | boolean | Flag indicating if chatbot is online, default: true |
| censorship_enabled | boolean | Flag indicating if censorship is enabled, default: false |
| welcome_message | varchar(255) | Welcome message displayed to users, default: "Hi! I'm your ToothTalk Assistant. How can I help today?" |
| quick_intents | json | Quick intent responses in JSON format, nullable |
| created_at | timestamp | Timestamp of when the setting was created |
| updated_at | timestamp | Timestamp of when the setting was last updated |

---

### chatbot_faqs

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| id | bigint(20) unsigned | Primary Key, unique FAQ ID |
| question | varchar(255) | FAQ question |
| answer | text | FAQ answer |
| is_active | boolean | Flag indicating if FAQ is active, default: true |
| order | int(11) unsigned | Display order, default: 0 |
| created_at | timestamp | Timestamp of when the FAQ was created |
| updated_at | timestamp | Timestamp of when the FAQ was last updated |

---

### chat_conversations

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| id | bigint(20) unsigned | Primary Key, unique conversation ID |
| patient_id | bigint(20) unsigned | Foreign key to users table (patient) |
| staff_id | bigint(20) unsigned | Foreign key to users table (staff member), nullable |
| admin_id | bigint(20) unsigned | Foreign key to users table (admin), nullable |
| status | enum | Conversation status: 'active', 'inactive', 'resolved', or 'closed', default: 'active' |
| last_message_at | timestamp | Timestamp of last message in conversation, nullable |
| created_at | timestamp | Timestamp of when the conversation was created |
| updated_at | timestamp | Timestamp of when the conversation was last updated |

**Foreign Keys:**
- `patient_id` → `users.id` (on delete: cascade)
- `staff_id` → `users.id` (on delete: set null)
- `admin_id` → `users.id` (on delete: set null)

**Indexes:**
- `patient_id`, `status`
- `last_message_at`

---

### chat_messages

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| id | bigint(20) unsigned | Primary Key, unique message ID |
| conversation_id | bigint(20) unsigned | Foreign key to chat_conversations table |
| sender_id | bigint(20) unsigned | Foreign key to users table (message sender) |
| sender_type | enum | Type of sender: 'patient', 'staff', or 'admin' |
| message | text | Message content |
| attachments | json | File attachments in JSON format, nullable |
| is_read | boolean | Flag indicating if message was read, default: false |
| read_at | timestamp | Timestamp when message was read, nullable |
| created_at | timestamp | Timestamp of when the message was created |
| updated_at | timestamp | Timestamp of when the message was last updated |

**Foreign Keys:**
- `conversation_id` → `chat_conversations.id` (on delete: cascade)
- `sender_id` → `users.id` (on delete: cascade)

**Indexes:**
- `conversation_id`, `created_at`
- `conversation_id`, `is_read`

---

### chat_censored_words

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| id | bigint(20) unsigned | Primary Key, unique censored word ID |
| word | varchar(255) | Censored word, unique |
| created_by_id | bigint(20) unsigned | ID of creator (polymorphic), nullable |
| created_by_type | varchar(255) | Type of creator (polymorphic), nullable |
| created_at | timestamp | Timestamp of when the word was added |
| updated_at | timestamp | Timestamp of when the word was last updated |

**Unique Constraints:**
- `word`

---

## Notification & Communication Tables

### notifications

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| id | bigint(20) unsigned | Primary Key, unique notification ID |
| user_id | bigint(20) unsigned | Foreign key to users table (recipient) |
| type | varchar(255) | Notification type (e.g., 'appointment_confirmed', 'appointment_reminder', 'record_updated') |
| title | varchar(255) | Notification title |
| message | text | Notification message |
| icon | varchar(255) | Icon class or type, nullable |
| data | json | Additional data (appointment_id, etc.) in JSON format, nullable |
| is_read | boolean | Flag indicating if notification was read, default: false |
| read_at | timestamp | Timestamp when notification was read, nullable |
| created_at | timestamp | Timestamp of when the notification was created |
| updated_at | timestamp | Timestamp of when the notification was last updated |

**Foreign Keys:**
- `user_id` → `users.id` (on delete: cascade)

**Indexes:**
- `user_id`
- `is_read`
- `created_at`

---

## System & Configuration Tables

### activity_logs

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| id | bigint(20) unsigned | Primary Key, unique activity log ID |
| user_id | bigint(20) unsigned | Foreign key to users table (staff member who performed action) |
| action | varchar(255) | Action performed (e.g., 'created', 'updated', 'deleted', 'viewed') |
| module | varchar(255) | Module affected (e.g., 'patient_record', 'appointment', 'patient_history') |
| description | varchar(255) | Human-readable description of the action |
| record_id | bigint(20) unsigned | ID of the affected record, nullable |
| record_type | varchar(255) | Type of record (model name), nullable |
| old_values | json | Previous values (for updates) in JSON format, nullable |
| new_values | json | New values (for creates/updates) in JSON format, nullable |
| ip_address | varchar(255) | IP address of the user, nullable |
| user_agent | text | User agent string, nullable |
| created_at | timestamp | Timestamp of when the activity was logged |
| updated_at | timestamp | Timestamp of when the activity log was last updated |

**Foreign Keys:**
- `user_id` → `users.id` (on delete: cascade)

**Indexes:**
- `user_id`
- `module`
- `created_at`

---

## Laravel Framework Tables

### password_reset_tokens

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| email | varchar(255) | Primary Key, email address |
| token | varchar(255) | Password reset token |
| created_at | timestamp | Timestamp when token was created, nullable |
| updated_at | timestamp | Timestamp when token was last updated, nullable |
| expires_at | timestamp | Timestamp when token expires, nullable |

---

### sessions

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| id | varchar(255) | Primary Key, session ID |
| user_id | bigint(20) unsigned | Foreign key to users table, nullable |
| ip_address | varchar(45) | IP address of the session, nullable |
| user_agent | text | User agent string, nullable |
| payload | longtext | Session payload data |
| last_activity | int(11) | Timestamp of last activity (indexed) |

**Foreign Keys:**
- `user_id` → `users.id`

**Indexes:**
- `user_id`
- `last_activity`

---

### cache

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| key | varchar(255) | Primary Key, cache key |
| value | mediumtext | Cached value |
| expiration | int(11) | Expiration timestamp |

---

### cache_locks

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| key | varchar(255) | Primary Key, cache lock key |
| owner | varchar(255) | Lock owner identifier |
| expiration | int(11) | Expiration timestamp |

---

### jobs

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| id | bigint(20) unsigned | Primary Key, unique job ID |
| queue | varchar(255) | Queue name (indexed) |
| payload | longtext | Job payload data |
| attempts | tinyint(4) unsigned | Number of attempts |
| reserved_at | int(11) unsigned | Timestamp when job was reserved, nullable |
| available_at | int(11) unsigned | Timestamp when job becomes available |
| created_at | int(11) unsigned | Timestamp when job was created |

**Indexes:**
- `queue`

---

### job_batches

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| id | varchar(255) | Primary Key, batch ID |
| name | varchar(255) | Batch name |
| total_jobs | int(11) | Total number of jobs in batch |
| pending_jobs | int(11) | Number of pending jobs |
| failed_jobs | int(11) | Number of failed jobs |
| failed_job_ids | longtext | IDs of failed jobs |
| options | mediumtext | Batch options, nullable |
| cancelled_at | int(11) | Timestamp when batch was cancelled, nullable |
| created_at | int(11) | Timestamp when batch was created |
| finished_at | int(11) | Timestamp when batch finished, nullable |

---

### failed_jobs

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| id | bigint(20) unsigned | Primary Key, unique failed job ID |
| uuid | varchar(255) | Unique job identifier |
| connection | text | Database connection name |
| queue | text | Queue name |
| payload | longtext | Job payload data |
| exception | longtext | Exception details |
| failed_at | timestamp | Timestamp when job failed (default: current timestamp) |

**Unique Constraints:**
- `uuid`

---

### personal_access_tokens

| Column Name | Data Type | Description |
|------------|-----------|-------------|
| id | bigint(20) unsigned | Primary Key, unique token ID |
| tokenable_type | varchar(255) | Type of tokenable model (polymorphic) |
| tokenable_id | bigint(20) unsigned | ID of tokenable model (polymorphic) |
| name | text | Token name |
| token | varchar(64) | Token value, unique |
| abilities | text | Token abilities, nullable |
| last_used_at | timestamp | Timestamp when token was last used, nullable |
| expires_at | timestamp | Timestamp when token expires, nullable (indexed) |
| created_at | timestamp | Timestamp of when the token was created |
| updated_at | timestamp | Timestamp of when the token was last updated |

**Unique Constraints:**
- `token`

**Indexes:**
- `expires_at`

---

## Notes

- All tables include `created_at` and `updated_at` timestamps unless otherwise specified.
- Foreign key relationships are documented with their cascade behavior (cascade, set null, etc.).
- Boolean fields default to `false` unless otherwise specified.
- Nullable fields are marked as such in the description.
- Enum fields list their possible values.
- JSON fields store structured data in JSON format.

---

**Document Version:** 1.0  
**Last Updated:** 2025  
**System:** Dental Clinic Management System

