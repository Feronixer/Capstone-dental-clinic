# Complete Test Case Documentation
## JValera Dental Clinic System

---

## ADMIN TEST CASES

### Authentication & Access Control

| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
|--------------|-----------|------------------|-----------------|-----------|---------------|-----------------|----------|
| TC_Admin_001 | Admin Login | Verify the Administrator can log in with valid credentials. | System grants access and directs the user to the Admin Dashboard. | Valid Admin email, Valid Password | | | |
| TC_Admin_002 | Admin Login - Invalid Credentials | Verify the system rejects login with invalid credentials. | System displays error message and denies access. | Invalid email or password | | | |
| TC_Admin_003 | Admin Login - Empty Fields | Verify the system validates required fields. | System displays validation error for empty fields. | Empty email, Empty password | | | |
| TC_Admin_004 | Admin Logout | Verify the Administrator can successfully log out. | System logs out the user and redirects to login page. | Logged in Admin user | | | |
| TC_Admin_005 | Admin Change Password | Verify the Administrator can change their password. | System updates password and user can log in with new password. | Current password, New password (min 8 chars), Confirm new password | | | |
| TC_Admin_006 | Admin Forgot Password | Verify the Administrator can request password reset. | System sends verification code to admin email. | Valid Admin email | | | |
| TC_Admin_007 | Admin Reset Password | Verify the Administrator can reset password using verification code. | System resets password and redirects to login. | Valid verification code, New password | | | |
| TC_Admin_008 | Admin Access Control - Unauthorized | Verify non-admin users cannot access admin portal. | System denies access and redirects to appropriate login. | Patient or Staff credentials | | | |

### Dashboard

| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
|--------------|-----------|------------------|-----------------|-----------|---------------|-----------------|----------|
| TC_Admin_009 | Admin Dashboard - View Statistics | Verify the dashboard displays accurate statistics. | Dashboard shows total patients, appointments, staff members, and today's appointments. | Logged in Admin user | | | |
| TC_Admin_010 | Admin Dashboard - Today's Appointments | Verify today's appointments are displayed correctly. | Dashboard shows list of today's appointments with details. | Appointments scheduled for today | | | |
| TC_Admin_011 | Admin Dashboard - Calendar View | Verify the calendar displays current month. | Calendar shows current month with appointment dates highlighted. | Current month and year | | | |

### Account Management

| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
|--------------|-----------|------------------|-----------------|-----------|---------------|-----------------|----------|
| TC_Admin_012 | Create User Account | Verify the Administrator can create a new user account. | System creates user account and displays success message. Gender (Male/Female) and birthday are required for Admin and Staff roles. Age is auto-calculated from birthday. Form can be submitted using Enter key. | Username, Email, Password, Role (Admin/Staff/Patient), Gender (required for Admin/Staff), Birthday (required for Admin/Staff), User info fields | | | |
| TC_Admin_013 | Create User - Duplicate Email | Verify the system prevents duplicate email addresses. | System displays error message for duplicate email. | Existing email address | | | |
| TC_Admin_014 | Create User - Validation | Verify the system validates required fields. | System displays validation errors for missing required fields. | Incomplete user data | | | |
| TC_Admin_015 | Edit User Account | Verify the Administrator can edit user account details. | System updates user information and displays success message. Age is auto-calculated from birthday when birthday is updated. | Valid user ID, Updated user information | | | |
| TC_Admin_016 | View User Details | Verify the Administrator can view detailed user information. | System displays complete user profile information. | Valid user ID | | | |
| TC_Admin_017 | Delete User Account | Verify the Administrator can delete a user account. | System deletes user account and removes from list. | Valid user ID | | | |
| TC_Admin_018 | Change User Password | Verify the Administrator can change password for any user. | System updates user password and user can log in with new password. | Valid user ID, New password | | | |

### Appointment Management

| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
|--------------|-----------|------------------|-----------------|-----------|---------------|-----------------|----------|
| TC_Admin_019 | Create Appointment | Verify the Administrator can create a new appointment. | System creates appointment and displays in calendar/table. | Patient, Service, Date/Time, Notes | | | |
| TC_Admin_020 | Create Appointment - Time Conflict | Verify the system prevents scheduling conflicting appointments. | System displays error message for time conflict. | Existing appointment time slot | | | |
| TC_Admin_021 | Create Appointment - Blocked Time | Verify the system prevents scheduling during blocked time. | System displays error message for blocked time. | Date/time within blocked period | | | |
| TC_Admin_022 | View Appointment Details | Verify the Administrator can view complete appointment information. | System displays appointment details in modal or page. | Valid appointment ID | | | |
| TC_Admin_023 | Edit Appointment | Verify the Administrator can update appointment details. | System updates appointment and displays success message. | Valid appointment ID, Updated appointment data | | | |
| TC_Admin_024 | Update Appointment Status | Verify the Administrator can change appointment status. | System updates status and reflects in calendar/table. Confirmed appointments can only transition to Completed, not Cancelled. Notes field is required only when status is Cancelled. | Valid appointment ID, New status (Pending/Confirmed/Completed/Cancelled), Notes (required if Cancelled) | | | |
| TC_Admin_025 | Cancel Appointment | Verify the Administrator can cancel an appointment (not delete). | System updates appointment status to 'Cancelled' instead of deleting. Confirmed appointments cannot be cancelled. Cancelled appointment time slot becomes available. | Valid appointment ID, Cancellation notes (required) | | | |
| TC_Admin_026 | Search Patients for Appointment | Verify the Administrator can search for patients when creating appointment. | System displays matching patient list. | Patient name or email | | | |
| TC_Admin_027 | Export Appointments to CSV | Verify the Administrator can export appointments to CSV. | System generates and downloads CSV file with appointment data. Time fields are in 12-hour format (AM/PM). "No." column shows sequential numbers. | Date range filter (optional) | | | |
| TC_Admin_028 | View Appointment Table | Verify the appointment table displays all appointments correctly. | Table shows appointments with filtering and pagination. | Logged in Admin user | | | |
| TC_Admin_029 | Block Time - Clinic Closed | Verify the Administrator can block time for clinic closure. | System blocks time period and prevents appointment scheduling. | Start date, End date, Reason | | | |
| TC_Admin_030 | Block Time - Block Off Time | Verify the Administrator can block specific time slots. | System blocks time slots and prevents appointment scheduling. | Date, Start time, End time | | | |
| TC_Admin_031 | Clear Future Blocked Times | Verify the Administrator can clear future blocked time periods. | System removes blocked time and allows scheduling. | Future blocked time periods | | | |

### Content Management

| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
|--------------|-----------|------------------|-----------------|-----------|---------------|-----------------|----------|
| TC_Admin_032 | Update Announcement | Verify the Administrator can update homepage announcement. | System updates announcement and displays on homepage. | Announcement title, content, status | | | |
| TC_Admin_033 | Create New Announcement | Verify the Administrator can create a new announcement. | System creates announcement and archives old one if exists. | Announcement title, content, status | | | |
| TC_Admin_034 | View Announcement Archives | Verify the Administrator can view archived announcements. | System displays list of archived announcements. | Logged in Admin user | | | |
| TC_Admin_035 | Delete Archived Announcement | Verify the Administrator can delete archived announcements. | System removes announcement from archive. | Valid archive ID | | | |
| TC_Admin_036 | Update Ticker Message | Verify the Administrator can update ticker message. | System updates ticker and displays on homepage. | Ticker message text | | | |
| TC_Admin_037 | Add Service | Verify the Administrator can add a new service. | System creates service and adds to services list. | Service name, description, price | | | |
| TC_Admin_038 | Edit Service | Verify the Administrator can update service details. | System updates service information. | Valid service ID, Updated service data | | | |
| TC_Admin_039 | Delete Service | Verify the Administrator can delete a service. | System removes service from list. | Valid service ID | | | |
| TC_Admin_040 | Add Event | Verify the Administrator can add a new event. | System creates event and adds to events list. | Event title, description, date, time | | | |
| TC_Admin_041 | Edit Event | Verify the Administrator can update event details. | System updates event information. | Valid event ID, Updated event data | | | |
| TC_Admin_042 | Delete Event | Verify the Administrator can delete an event. | System removes event from list. | Valid event ID | | | |
| TC_Admin_043 | Update Mail Template | Verify the Administrator can update email templates. | System updates mail template for specified type. | Mail template type, Template content | | | |
| TC_Admin_044 | Send Patient Email | Verify the Administrator can send email to specific patient. | System sends email to patient's email address. | Patient ID, Appointment ID, Email type | | | |
| TC_Admin_045 | Send Bulk Email | Verify the Administrator can send bulk emails to patients. | System sends emails to multiple patients based on criteria. | Patient selection criteria, Email content | | | |

### Post-Procedural Management

| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
|--------------|-----------|------------------|-----------------|-----------|---------------|-----------------|----------|
| TC_Admin_046 | View Patient Records List | Verify the Administrator can view list of patient records. | System displays table/list of all patient records. | Logged in Admin user | | | |
| TC_Admin_047 | Search Patients for Record | Verify the Administrator can search for patients. | System displays matching patient list. | Patient name or username | | | |
| TC_Admin_048 | Create Patient Record | Verify the Administrator can create a new patient record. | System creates patient record and adds to list. | Patient ID, Record details (address, DOB, occupation, etc.) | | | |
| TC_Admin_049 | View Patient Record | Verify the Administrator can view complete patient record. | System displays patient record details in modal/page. | Valid record ID | | | |
| TC_Admin_050 | Edit Patient Record | Verify the Administrator can update patient record. | System updates record and displays success message. | Valid record ID, Updated record data | | | |
| TC_Admin_051 | Delete Patient Record | Verify the Administrator can delete a patient record (staff cannot delete). | System removes record from list. Only administrators have delete permissions. | Valid record ID | | | |
| TC_Admin_052 | Add Patient History | Verify the Administrator can add medical history entry. | System creates history entry and adds to patient history. | Record ID, History details (condition, date, notes) | | | |
| TC_Admin_053 | Edit Patient History | Verify the Administrator can update patient history entry. | System updates history entry. | Valid history ID, Updated history data | | | |
| TC_Admin_054 | Delete Patient History | Verify the Administrator can delete a history entry (staff cannot delete). | System removes history entry. Only administrators have delete permissions. | Valid history ID | | | |
| TC_Admin_055 | Add Progress Note | Verify the Administrator can add a progress note. | System creates progress note and adds to patient notes. | Record ID, Note details (description, status, response, next steps) | | | |
| TC_Admin_056 | Edit Progress Note | Verify the Administrator can update a progress note. | System updates progress note. | Valid note ID, Updated note data | | | |
| TC_Admin_057 | Delete Progress Note | Verify the Administrator can delete a progress note (staff cannot delete). | System removes progress note. Only administrators have delete permissions. | Valid note ID | | | |
| TC_Admin_058 | Send Record to Patient | Verify the Administrator can send completed record to patient. | System sends email with record to patient and marks as sent. | Valid record ID | | | |

### ToothTalk Management

| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
|--------------|-----------|------------------|-----------------|-----------|---------------|-----------------|----------|
| TC_Admin_059 | View ToothTalk Settings | Verify the Administrator can view chatbot settings. | System displays chatbot configuration options. | Logged in Admin user | | | |
| TC_Admin_060 | Update ToothTalk Settings | Verify the Administrator can update chatbot settings. | System saves settings and chatbot uses new configuration. | Settings values (welcome message, responses, etc.) | | | |
| TC_Admin_061 | Add FAQ | Verify the Administrator can add a new FAQ entry. | System creates FAQ and adds to chatbot knowledge base. | Question, Answer | | | |
| TC_Admin_062 | Edit FAQ | Verify the Administrator can update FAQ entry. | System updates FAQ information. | Valid FAQ ID, Updated question/answer | | | |
| TC_Admin_063 | Delete FAQ | Verify the Administrator can delete an FAQ entry. | System removes FAQ from knowledge base. | Valid FAQ ID | | | |

### Notifications

| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
|--------------|-----------|------------------|-----------------|-----------|---------------|-----------------|----------|
| TC_Admin_064 | View Notifications | Verify the Administrator can view notification requests. | System displays list of pending appointment requests. | Logged in Admin user | | | |
| TC_Admin_065 | Approve Appointment Request | Verify the Administrator can approve an appointment request. | System approves request, creates appointment, and notifies patient. | Valid notification/request ID | | | |
| TC_Admin_066 | Deny Appointment Request | Verify the Administrator can deny an appointment request. | System denies request and notifies patient. | Valid notification/request ID | | | |

### Profile Management

| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
|--------------|-----------|------------------|-----------------|-----------|---------------|-----------------|----------|
| TC_Admin_067 | View Admin Profile | Verify the Administrator can view their profile information. | System displays admin profile details. Birthday and gender are read-only (managed in user management). Age is auto-calculated from birthday. | Logged in Admin user | | | |
| TC_Admin_068 | Update Profile Information | Verify the Administrator can update profile details. | System updates profile and displays success message. Birthday and gender are preserved from user management and cannot be edited in profile. Age is automatically recalculated from birthday. | Updated name, email, phone, address | | | |
| TC_Admin_069 | Update Profile Picture | Verify the Administrator can upload/change profile picture. | System updates profile picture and displays new image. | Valid image file (jpg, png) | | | |
| TC_Admin_070 | Update Profile Password | Verify the Administrator can change password from profile. | System updates password and user can log in with new password. | Current password, New password, Confirm password | | | |

### Activity Logs

| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
|--------------|-----------|------------------|-----------------|-----------|---------------|-----------------|----------|
| TC_Admin_071 | View Activity Logs | Verify the Administrator can view activity logs. | System displays list of staff activities. | Logged in Admin user | | | |
| TC_Admin_072 | Filter Activity Logs | Verify the Administrator can filter activity logs. | System displays filtered results based on criteria. | Filter by staff member, module, action, date range | | | |
| TC_Admin_073 | View Activity Log Details | Verify the Administrator can view detailed activity log information. | System displays complete activity log details. | Valid activity log ID | | | |

---

## STAFF TEST CASES

### Authentication & Access Control

| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
|--------------|-----------|------------------|-----------------|-----------|---------------|-----------------|----------|
| TC_Staff_001 | Staff Login | Verify the Staff member can log in with valid credentials. | System grants access and directs the user to the Staff Dashboard. | Valid Staff username, Valid Password | | | |
| TC_Staff_002 | Staff Login - Invalid Credentials | Verify the system rejects login with invalid credentials. | System displays error message and denies access. | Invalid username or password | | | |
| TC_Staff_003 | Staff Login - Empty Fields | Verify the system validates required fields. | System displays validation error for empty fields. | Empty username, Empty password | | | |
| TC_Staff_004 | Staff Logout | Verify the Staff member can successfully log out. | System logs out the user and redirects to login page. | Logged in Staff user | | | |
| TC_Staff_005 | Staff Change Password | Verify the Staff member can change their password. | System updates password and user can log in with new password. | Current password, New password (min 8 chars), Confirm new password | | | |
| TC_Staff_006 | Staff Forgot Password | Verify the Staff member can request password reset. | System sends verification code to staff email. | Valid Staff email | | | |
| TC_Staff_007 | Staff Reset Password | Verify the Staff member can reset password using verification code. | System resets password and redirects to login. | Valid verification code, New password | | | |
| TC_Staff_008 | Staff Access Control - Patient Access | Verify patient users cannot access staff portal. | System denies access and redirects to patient login. | Patient credentials | | | |

### Dashboard

| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
|--------------|-----------|------------------|-----------------|-----------|---------------|-----------------|----------|
| TC_Staff_009 | Staff Dashboard - View Statistics | Verify the dashboard displays accurate statistics. | Dashboard shows total patients, appointments, and today's appointments. | Logged in Staff user | | | |
| TC_Staff_010 | Staff Dashboard - Today's Appointments | Verify today's appointments are displayed correctly. | Dashboard shows list of today's appointments with details. | Appointments scheduled for today | | | |
| TC_Staff_011 | Staff Dashboard - Pending Appointments | Verify pending appointments count is displayed. | Dashboard shows count of pending appointments. | Pending appointments in system | | | |

### Account Management (Patient Accounts Only)

| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
|--------------|-----------|------------------|-----------------|-----------|---------------|-----------------|----------|
| TC_Staff_012 | Create Patient Account | Verify the Staff member can create a new patient account. | System creates patient account and displays success message. Gender (Male/Female) and birthday are required. Age is auto-calculated from birthday. Form can be submitted using Enter key. | Username, Email, Password, Gender (required), Birthday (required), Patient info fields | | | |
| TC_Staff_013 | Create Patient - Duplicate Email | Verify the system prevents duplicate email addresses. | System displays error message for duplicate email. | Existing email address | | | |
| TC_Staff_014 | Create Patient - Validation | Verify the system validates required fields. | System displays validation errors for missing required fields. | Incomplete patient data | | | |
| TC_Staff_015 | Edit Patient Account | Verify the Staff member can edit patient account details. | System updates patient information and displays success message. Age is auto-calculated from birthday when birthday is updated. | Valid patient ID, Updated patient information | | | |
| TC_Staff_016 | View Patient Details | Verify the Staff member can view detailed patient information. | System displays complete patient profile information. | Valid patient ID | | | |
| TC_Staff_017 | Delete Patient Account | Verify the Staff member can delete a patient account. | System deletes patient account and removes from list. | Valid patient ID | | | |
| TC_Staff_018 | Change Patient Password | Verify the Staff member can change password for any patient. | System updates patient password and patient can log in with new password. | Valid patient ID, New password | | | |
| TC_Staff_019 | Staff Cannot Create Admin/Staff Account | Verify the Staff member cannot create admin or staff accounts. | System denies access or prevents creation of non-patient accounts. | Attempt to create admin/staff account | | | |

### Appointment Management

| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
|--------------|-----------|------------------|-----------------|-----------|---------------|-----------------|----------|
| TC_Staff_020 | Create Appointment | Verify the Staff member can create a new appointment. | System creates appointment and displays in calendar/table. | Patient, Service, Date/Time, Notes | | | |
| TC_Staff_021 | Create Appointment - Time Conflict | Verify the system prevents scheduling conflicting appointments. | System displays error message for time conflict. | Existing appointment time slot | | | |
| TC_Staff_022 | Create Appointment - Blocked Time | Verify the system prevents scheduling during blocked time. | System displays error message for blocked time. | Date/time within blocked period | | | |
| TC_Staff_023 | View Appointment Details | Verify the Staff member can view complete appointment information. | System displays appointment details in modal or page. | Valid appointment ID | | | |
| TC_Staff_024 | Edit Appointment | Verify the Staff member can update appointment details. | System updates appointment and displays success message. | Valid appointment ID, Updated appointment data | | | |
| TC_Staff_025 | Update Appointment Status | Verify the Staff member can change appointment status. | System updates status and reflects in calendar/table. Confirmed appointments can only transition to Completed, not Cancelled. Notes field is required only when status is Cancelled. | Valid appointment ID, New status (Pending/Confirmed/Completed/Cancelled), Notes (required if Cancelled) | | | |
| TC_Staff_026 | Staff Cannot Delete Appointment | Verify the Staff member cannot delete appointments. | System denies delete action or hides delete button. | Valid appointment ID | | | |
| TC_Staff_027 | Search Patients for Appointment | Verify the Staff member can search for patients when creating appointment. | System displays matching patient list. | Patient name or email | | | |
| TC_Staff_028 | Export Appointments to CSV | Verify the Staff member can export appointments to CSV. | System generates and downloads CSV file with appointment data. Time fields are in 12-hour format (AM/PM). "No." column shows sequential numbers. | Date range filter (optional) | | | |
| TC_Staff_029 | View Appointment Table | Verify the appointment table displays all appointments correctly. | Table shows appointments with filtering and pagination. | Logged in Staff user | | | |
| TC_Staff_030 | Block Time - Clinic Closed | Verify the Staff member can block time for clinic closure. | System blocks time period and prevents appointment scheduling. | Start date, End date, Reason | | | |
| TC_Staff_031 | Block Time - Block Off Time | Verify the Staff member can block specific time slots. | System blocks time slots and prevents appointment scheduling. | Date, Start time, End time | | | |
| TC_Staff_032 | Clear Future Blocked Times | Verify the Staff member can clear future blocked time periods. | System removes blocked time and allows scheduling. | Future blocked time periods | | | |

### Content Management

| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
|--------------|-----------|------------------|-----------------|-----------|---------------|-----------------|----------|
| TC_Staff_033 | Update Announcement | Verify the Staff member can update homepage announcement. | System updates announcement and displays on homepage. | Announcement title, content, status | | | |
| TC_Staff_034 | Create New Announcement | Verify the Staff member can create a new announcement. | System creates announcement and archives old one if exists. | Announcement title, content, status | | | |
| TC_Staff_035 | View Announcement Archives | Verify the Staff member can view archived announcements. | System displays list of archived announcements. | Logged in Staff user | | | |
| TC_Staff_036 | Delete Archived Announcement | Verify the Staff member can delete archived announcements. | System removes announcement from archive. | Valid archive ID | | | |
| TC_Staff_037 | Update Ticker Message | Verify the Staff member can update ticker message. | System updates ticker and displays on homepage. | Ticker message text | | | |
| TC_Staff_038 | Add Service | Verify the Staff member can add a new service. | System creates service and adds to services list. | Service name, description, price | | | |
| TC_Staff_039 | Edit Service | Verify the Staff member can update service details. | System updates service information. | Valid service ID, Updated service data | | | |
| TC_Staff_040 | Delete Service | Verify the Staff member can delete a service. | System removes service from list. | Valid service ID | | | |
| TC_Staff_041 | Add Event | Verify the Staff member can add a new event. | System creates event and adds to events list. | Event title, description, date, time | | | |
| TC_Staff_042 | Edit Event | Verify the Staff member can update event details. | System updates event information. | Valid event ID, Updated event data | | | |
| TC_Staff_043 | Delete Event | Verify the Staff member can delete an event. | System removes event from list. | Valid event ID | | | |
| TC_Staff_044 | Update Mail Template | Verify the Staff member can update email templates. | System updates mail template for specified type. | Mail template type, Template content | | | |
| TC_Staff_045 | Send Patient Email | Verify the Staff member can send email to specific patient. | System sends email to patient's email address. | Patient ID, Appointment ID, Email type | | | |
| TC_Staff_046 | Send Bulk Email | Verify the Staff member can send bulk emails to patients. | System sends emails to multiple patients based on criteria. | Patient selection criteria, Email content | | | |

### Post-Procedural Management

| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
|--------------|-----------|------------------|-----------------|-----------|---------------|-----------------|----------|
| TC_Staff_047 | View Patient Records List | Verify the Staff member can view list of patient records. | System displays table/list of all patient records. | Logged in Staff user | | | |
| TC_Staff_048 | Search Patients for Record | Verify the Staff member can search for patients. | System displays matching patient list. | Patient name or username | | | |
| TC_Staff_049 | Create Patient Record | Verify the Staff member can create a new patient record. | System creates patient record and adds to list. | Patient ID, Record details (address, DOB, occupation, etc.) | | | |
| TC_Staff_050 | View Patient Record | Verify the Staff member can view complete patient record. | System displays patient record details in modal/page. | Valid record ID | | | |
| TC_Staff_051 | Edit Patient Record | Verify the Staff member can update patient record. | System updates record and displays success message. | Valid record ID, Updated record data | | | |
| TC_Staff_052 | Delete Patient Record | Verify the Staff member CANNOT delete a patient record. | System denies delete action and displays access denied message. Only administrators can delete records. | Valid record ID | | | |
| TC_Staff_053 | Add Patient History | Verify the Staff member can add medical history entry. | System creates history entry and adds to patient history. | Record ID, History details (condition, date, notes) | | | |
| TC_Staff_054 | Edit Patient History | Verify the Staff member can update patient history entry. | System updates history entry. | Valid history ID, Updated history data | | | |
| TC_Staff_055 | Delete Patient History | Verify the Staff member CANNOT delete a history entry. | System denies delete action and displays access denied message. Only administrators can delete history records. | Valid history ID | | | |
| TC_Staff_056 | Add Progress Note | Verify the Staff member can add a progress note. | System creates progress note and adds to patient notes. | Record ID, Note details (description, status, response, next steps) | | | |
| TC_Staff_057 | Edit Progress Note | Verify the Staff member can update a progress note. | System updates progress note. | Valid note ID, Updated note data | | | |
| TC_Staff_058 | Delete Progress Note | Verify the Staff member CANNOT delete a progress note. | System denies delete action and displays access denied message. Only administrators can delete progress notes. | Valid note ID | | | |
| TC_Staff_059 | Send Record to Patient | Verify the Staff member can send completed record to patient. | System sends email with record to patient and marks as sent. | Valid record ID | | | |

### Patient Record Access

| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
|--------------|-----------|------------------|-----------------|-----------|---------------|-----------------|----------|
| TC_Staff_060 | View Patient Records | Verify the Staff member can access patient record search page. | System displays patient search interface. | Logged in Staff user | | | |
| TC_Staff_061 | Search Patients by Name | Verify the Staff member can search patients by name. | System displays matching patient list. | Patient first name or last name | | | |
| TC_Staff_062 | Search Patients by Email | Verify the Staff member can search patients by email. | System displays matching patient list. | Patient email address | | | |
| TC_Staff_063 | Filter Patients | Verify the Staff member can filter patient list. | System displays filtered patient results. | Filter option (All/With Appointments/With Records/Active) | | | |
| TC_Staff_064 | View Patient Medical Record | Verify the Staff member can view patient medical record tab. | System displays patient medical information. | Valid patient ID | | | |
| TC_Staff_065 | View Patient Visit History | Verify the Staff member can view patient visit history tab. | System displays chronological list of visits. | Valid patient ID | | | |
| TC_Staff_066 | View Patient Progress Notes | Verify the Staff member can view patient progress notes tab. | System displays list of progress notes. | Valid patient ID | | | |
| TC_Staff_067 | View Patient Appointments | Verify the Staff member can view patient appointments tab. | System displays list of all patient appointments. | Valid patient ID | | | |
| TC_Staff_068 | Export Patient Record | Verify the Staff member can export patient record to PDF. | System generates and downloads PDF file. | Valid patient ID | | | |

### Notifications

| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
|--------------|-----------|------------------|-----------------|-----------|---------------|-----------------|----------|
| TC_Staff_069 | View Notifications | Verify the Staff member can view notification requests. | System displays list of pending appointment requests. | Logged in Staff user | | | |
| TC_Staff_070 | Approve Appointment Request | Verify the Staff member can approve an appointment request. | System approves request, creates appointment, and notifies patient. | Valid notification/request ID | | | |
| TC_Staff_071 | Deny Appointment Request | Verify the Staff member can deny an appointment request. | System denies request and notifies patient. | Valid notification/request ID | | | |

### Profile Management

| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
|--------------|-----------|------------------|-----------------|-----------|---------------|-----------------|----------|
| TC_Staff_072 | View Staff Profile | Verify the Staff member can view their profile information. | System displays staff profile details. | Logged in Staff user | | | |
| TC_Staff_073 | Update Profile Information | Verify the Staff member can update profile details (same functionality as admin). | System updates profile and displays success message. Birthday and gender are read-only (managed in user management). Age is auto-calculated from birthday. | Updated name, email, phone, address | | | |
| TC_Staff_074 | Update Profile Picture | Verify the Staff member can upload/change profile picture. | System updates profile picture and displays new image. | Valid image file (jpg, png) | | | |
| TC_Staff_075 | Update Profile Password | Verify the Staff member can change password from profile. | System updates password and user can log in with new password. | Current password, New password, Confirm password | | | |

---

## PATIENT TEST CASES

### Authentication & Access Control

| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
|--------------|-----------|------------------|-----------------|-----------|---------------|-----------------|----------|
| TC_Patient_001 | Patient Login | Verify the Patient can log in with valid credentials. | System grants access and directs the user to the Patient Dashboard. | Valid Patient email, Valid Password | | | |
| TC_Patient_002 | Patient Login - Invalid Credentials | Verify the system rejects login with invalid credentials. | System displays error message and denies access. | Invalid email or password | | | |
| TC_Patient_003 | Patient Login - Empty Fields | Verify the system validates required fields. | System displays validation error for empty fields. | Empty email, Empty password | | | |
| TC_Patient_004 | Patient Registration | Verify a new patient can register an account. | System creates patient account and redirects to login or dashboard. | Username, Email, Password, Confirm Password, Patient info fields | | | |
| TC_Patient_005 | Patient Registration - Duplicate Email | Verify the system prevents duplicate email addresses. | System displays error message for duplicate email. | Existing email address | | | |
| TC_Patient_006 | Patient Registration - Validation | Verify the system validates required registration fields. | System displays validation errors for missing required fields. | Incomplete registration data | | | |
| TC_Patient_007 | Patient Logout | Verify the Patient can successfully log out. | System logs out the user and redirects to login page. | Logged in Patient user | | | |
| TC_Patient_008 | Patient Change Password | Verify the Patient can change their password. | System updates password and user can log in with new password. | Current password, New password (min 8 chars), Confirm new password | | | |
| TC_Patient_009 | Patient Forgot Password | Verify the Patient can request password reset. | System sends verification code to patient email. | Valid Patient email | | | |
| TC_Patient_010 | Patient Reset Password | Verify the Patient can reset password using verification code. | System resets password and redirects to login. | Valid verification code, New password | | | |

### Dashboard

| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
|--------------|-----------|------------------|-----------------|-----------|---------------|-----------------|----------|
| TC_Patient_011 | Patient Dashboard - View | Verify the patient dashboard displays correctly. | Dashboard shows patient information, upcoming appointments, and announcements. | Logged in Patient user | | | |
| TC_Patient_012 | Patient Dashboard - Upcoming Appointments | Verify upcoming appointments are displayed. | Dashboard shows list of upcoming appointments with details. | Patient has upcoming appointments | | | |
| TC_Patient_013 | Patient Dashboard - Recent Announcements | Verify recent announcements are displayed. | Dashboard shows latest announcements from clinic. | Active announcements exist | | | |

### Calendar & Appointment Requests

| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
|--------------|-----------|------------------|-----------------|-----------|---------------|-----------------|----------|
| TC_Patient_014 | View Calendar | Verify the Patient can view the appointment calendar. | System displays calendar with available time slots. | Logged in Patient user | | | |
| TC_Patient_015 | View Available Services | Verify the Patient can view list of available services. | System displays services with descriptions and prices. | Logged in Patient user | | | |
| TC_Patient_016 | Submit Appointment Request | Verify the Patient can submit an appointment request. | System creates appointment request and sends to admin/staff. Patient cannot reschedule missed appointments. | Service, Preferred date/time, Notes | | | |
| TC_Patient_017 | Submit Appointment Request - Past Date | Verify the system prevents scheduling appointments in the past. | System displays error message for past date. | Date in the past | | | |
| TC_Patient_018 | Submit Appointment Request - Blocked Time | Verify the system prevents requesting appointments during blocked time. | System displays error message for blocked time. Notes for clinic closure are displayed when available. | Date/time within blocked period | | | |
| TC_Patient_019 | Submit Appointment Request - Validation | Verify the system validates required fields. | System displays validation errors for missing required fields. | Incomplete appointment request data | | | |
| TC_Patient_020 | View Appointment Request Status | Verify the Patient can view status of their appointment requests. | System displays pending/approved/denied status. Cancelled appointments are displayed with strikethrough styling and notes. | Patient has appointment requests | | | |
| TC_Patient_020A | Reschedule Appointment Request | Verify the Patient can request to reschedule an appointment. | System allows reschedule request for pending and confirmed appointments only. Missed and cancelled appointments cannot be rescheduled. | Valid appointment ID, New preferred date/time | | | |
| TC_Patient_020B | Reschedule - Prevent Missed Appointment | Verify the system prevents rescheduling missed appointments. | System displays error message and disables reschedule option for missed appointments. | Missed appointment ID | | | |

### Profile Management

| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
|--------------|-----------|------------------|-----------------|-----------|---------------|-----------------|----------|
| TC_Patient_021 | View Patient Profile | Verify the Patient can view their profile information. | System displays patient profile details. | Logged in Patient user | | | |
| TC_Patient_022 | Update Profile Information | Verify the Patient can update profile details. | System updates profile and displays success message. | Updated name, email, phone, address, date of birth | | | |
| TC_Patient_023 | Update Profile Picture | Verify the Patient can upload/change profile picture. | System updates profile picture and displays new image. | Valid image file (jpg, png) | | | |
| TC_Patient_024 | Update Profile Password | Verify the Patient can change password from profile. | System updates password and user can log in with new password. | Current password, New password, Confirm password | | | |

### Patient Records

| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
|--------------|-----------|------------------|-----------------|-----------|---------------|-----------------|----------|
| TC_Patient_025 | View Patient Records List | Verify the Patient can view list of their medical records. | System displays list of patient records with sort options (newest first, oldest first). | Patient has medical records | | | |
| TC_Patient_026 | View Patient Record Details | Verify the Patient can view detailed medical record with proper conditional field display. | System displays complete medical record information. Conditional fields (e.g., "If Under Treatment (Yes), Condition:") are clearly labeled and indented with visual indicators. | Valid record ID | | | |
| TC_Patient_027 | Download Patient Record | Verify the Patient can download medical record as PDF. | System generates and downloads PDF file formatted for legal-size bond paper (8.5" x 14") with visually appealing design, gradient headers, color-coded sections, and clinic footer. | Valid record ID | | | |
| TC_Patient_028 | View Patient History | Verify the Patient can view their medical history. | System displays list of medical history entries. | Patient has history entries | | | |
| TC_Patient_029 | Download Patient History | Verify the Patient can download medical history as PDF. | System generates and downloads PDF file formatted for legal-size bond paper (8.5" x 14") with visually appealing design, gradient headers, color-coded sections, conditional field styling, and clinic footer. | Valid history ID | | | |
| TC_Patient_030 | View Progress Notes | Verify the Patient can view their progress notes. | System displays list of progress notes. | Patient has progress notes | | | |
| TC_Patient_031 | Download Progress Note | Verify the Patient can download progress note as PDF. | System generates and downloads PDF file formatted for legal-size bond paper (8.5" x 14") with visually appealing design, gradient headers, color-coded sections, and clinic footer. | Valid progress note ID | | | |
| TC_Patient_032 | View Record - No Records | Verify the system handles case when patient has no records. | System displays message indicating no records available. | Patient has no medical records | | | |

### Announcements

| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
|--------------|-----------|------------------|-----------------|-----------|---------------|-----------------|----------|
| TC_Patient_033 | View Announcements | Verify the Patient can view clinic announcements. | System displays list of active announcements. | Logged in Patient user | | | |
| TC_Patient_034 | View Announcement Details | Verify the Patient can view announcement details. | System displays full announcement content. | Valid announcement ID | | | |
| TC_Patient_035 | View Archived Announcements | Verify the Patient can view archived announcements. | System displays list of archived announcements. | Archived announcements exist | | | |

### Notifications

| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
|--------------|-----------|------------------|-----------------|-----------|---------------|-----------------|----------|
| TC_Patient_036 | View Notifications | Verify the Patient can view their notifications. | System displays list of patient notifications. | Logged in Patient user | | | |
| TC_Patient_037 | View Recent Notifications | Verify the Patient can view recent notifications. | System displays recent notifications list. | Patient has notifications | | | |
| TC_Patient_038 | Mark Notification as Read | Verify the Patient can mark notification as read. | System updates notification status to read. | Valid notification ID | | | |
| TC_Patient_039 | Mark Notification as Unread | Verify the Patient can mark notification as unread. | System updates notification status to unread. | Valid notification ID | | | |
| TC_Patient_040 | Mark All Notifications as Read | Verify the Patient can mark all notifications as read. | System updates all notifications to read status. | Patient has multiple notifications | | | |
| TC_Patient_041 | Delete Notification | Verify the Patient can delete a notification. | System removes notification from list. | Valid notification ID | | | |
| TC_Patient_042 | Clear Read Notifications | Verify the Patient can clear all read notifications. | System removes all read notifications. | Patient has read notifications | | | |
| TC_Patient_043 | View Unread Count | Verify the Patient can see unread notification count. | System displays badge with unread count. | Patient has unread notifications | | | |

### Feedback

| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
|--------------|-----------|------------------|-----------------|-----------|---------------|-----------------|----------|
| TC_Patient_044 | View Feedback Page | Verify the Patient can access feedback page. | System displays feedback interface. | Logged in Patient user | | | |
| TC_Patient_045 | View Completed Appointments for Feedback | Verify the Patient can view completed appointments available for feedback. | System displays list of completed appointments. | Patient has completed appointments | | | |
| TC_Patient_046 | Submit Feedback | Verify the Patient can submit feedback for an appointment. | System saves feedback and displays success message. | Appointment ID, Rating, Comments | | | |
| TC_Patient_047 | Submit Feedback - Validation | Verify the system validates feedback form. | System displays validation errors for missing required fields. | Incomplete feedback data | | | |
| TC_Patient_048 | View Feedback History | Verify the Patient can view their feedback history. | System displays list of submitted feedback. | Patient has submitted feedback | | | |
| TC_Patient_049 | Submit Feedback - Already Submitted | Verify the system prevents duplicate feedback submission. | System displays message that feedback already submitted. | Appointment with existing feedback | | | |

### General Features

| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
|--------------|-----------|------------------|-----------------|-----------|---------------|-----------------|----------|
| TC_Patient_050 | View About Us Page | Verify the Patient can view about us page. | System displays clinic information page. | Logged in Patient user | | | |
| TC_Patient_051 | Navigation Menu | Verify all navigation links work correctly. | System navigates to correct pages when clicking menu items. | Various menu items | | | |
| TC_Patient_052 | Responsive Design | Verify the patient portal works on different screen sizes. | System adapts layout for mobile, tablet, and desktop views. | Different screen resolutions | | | |

---

## END OF TEST CASE DOCUMENTATION

