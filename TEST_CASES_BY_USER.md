# Test Cases by User Role

Each table follows the clinic’s standard format. Use the last three columns during execution.

## Dentist / Admin Test Cases

### Authentication & Access Control
| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
| --- | --- | --- | --- | --- | --- | --- | --- |
| TC_Admin_001 | Admin Login | Confirm the administrator signs in with valid credentials. | System opens the admin dashboard. | Valid admin email, valid password |  |  |  |
| TC_Admin_002 | Admin Login – Invalid | Confirm invalid credentials are rejected. | Error message appears; access denied. | Invalid email or password |  |  |  |
| TC_Admin_003 | Admin Login – Empty Fields | Confirm empty fields are blocked. | Validation error displays. | Empty email and password |  |  |  |
| TC_Admin_004 | Admin Logout | Confirm the administrator can log out. | Session ends and login page shows. | Logged-in admin account |  |  |  |
| TC_Admin_005 | Change Password | Confirm the administrator can change the account password. | Password updates; new password works. | Current password, new strong password, confirmation |  |  |  |
| TC_Admin_006 | Forgot Password | Confirm the administrator can request a reset code. | Reset code email arrives. | Valid admin email |  |  |  |
| TC_Admin_007 | Reset Password | Confirm the administrator can reset password using the code. | Password resets; login succeeds. | Valid reset code, new password |  |  |  |
| TC_Admin_008 | Block Unauthorized Access | Confirm non-admin users cannot open admin routes. | System redirects to the correct login. | Staff or patient credentials |  |  |  |

### Dashboard
| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
| --- | --- | --- | --- | --- | --- | --- | --- |
| TC_Admin_009 | View Dashboard Stats | Confirm the dashboard shows today’s counts. | Totals for patients, appointments, staff, and today’s visits display. | Logged-in admin account |  |  |  |
| TC_Admin_010 | View Today’s Appointments | Confirm the daily appointment list appears. | Today’s appointments list with details displays. | Day with scheduled visits |  |  |  |
| TC_Admin_011 | View Calendar | Confirm the monthly calendar renders. | Current month highlights booked dates. | Current month |  |  |  |

### Account Management
| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
| --- | --- | --- | --- | --- | --- | --- | --- |
| TC_Admin_012 | Create User Account | Confirm admins can create any user role. | New account saves with auto-calculated age. | Username, email, role, gender, birthday, profile fields |  |  |  |
| TC_Admin_013 | Prevent Duplicate Email | Confirm duplicate emails are blocked. | Validation error displays. | Existing user email |  |  |  |
| TC_Admin_014 | Validate Required Fields | Confirm missing fields stop submission. | Validation messages show. | Incomplete user data |  |  |  |
| TC_Admin_015 | Edit User Account | Confirm admins can edit account details. | Updates save and success toast appears. | User ID, updated fields |  |  |  |
| TC_Admin_016 | View User Details | Confirm admins can view full user profiles. | Full profile information displays. | Valid user ID |  |  |  |
| TC_Admin_017 | Delete User Account | Confirm admins can delete accounts. | Account disappears from list. | User ID with delete permission |  |  |  |
| TC_Admin_018 | Change User Password | Confirm admins can reset any user password. | Password changes and new password works. | User ID, new password |  |  |  |

### Appointment Management
| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
| --- | --- | --- | --- | --- | --- | --- | --- |
| TC_Admin_019 | Create Appointment | Confirm admins can book an appointment. | New appointment appears in calendar and table. | Patient, service, date/time, notes |  |  |  |
| TC_Admin_020 | Avoid Time Conflicts | Confirm overlapping bookings are blocked. | Conflict message displays; booking prevented. | Existing timeslot |  |  |  |
| TC_Admin_021 | Respect Blocked Time | Confirm blocked slots cannot be booked. | Blocked time warning shows. | Blocked datetime |  |  |  |
| TC_Admin_022 | View Appointment Details | Confirm full appointment details are accessible. | Detail modal/page shows patient, service, notes. | Appointment ID |  |  |  |
| TC_Admin_023 | Edit Appointment | Confirm admins can update appointment data. | Changes save and appear in calendar/table. | Appointment ID, new data |  |  |  |
| TC_Admin_024 | Update Status | Confirm admins can change appointment status. | Status changes with required notes for cancellations. | Appointment ID, new status |  |  |  |
| TC_Admin_025 | Cancel Appointment | Confirm admins can cancel instead of delete. | Status becomes “Cancelled”; slot opens. | Appointment ID, cancel reason |  |  |  |
| TC_Admin_026 | Search Patients | Confirm patient search works during booking. | Matching patients list shows. | Name or email query |  |  |  |
| TC_Admin_027 | Export Appointments | Confirm admins can export appointments to CSV. | CSV downloads with sequential numbers and 12-hour time. | Optional date filter |  |  |  |
| TC_Admin_028 | View Appointment Table | Confirm table lists all appointments with filters. | Table populates with pagination and filter controls. | Logged-in admin account |  |  |  |
| TC_Admin_029 | Block Time – Clinic Closed | Confirm admins can block full-day closures. | Blocked range shows and prevents booking. | Start/end date, reason |  |  |  |
| TC_Admin_030 | Block Time – Specific Hours | Confirm admins can block specific timeslots. | Blocked times display and block bookings. | Date, start time, end time |  |  |  |
| TC_Admin_031 | Clear Future Blocks | Confirm admins can clear upcoming blocked times. | Selected block is removed; slot becomes available. | Future blocked entry |  |  |  |

### Content Management
| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
| --- | --- | --- | --- | --- | --- | --- | --- |
| TC_Admin_032 | Update Announcement | Confirm admins can update homepage announcement. | New message shows for patients and staff. | Title, content, status |  |  |  |
| TC_Admin_033 | Create Announcement | Confirm admins can add fresh announcements. | New entry saves; prior version archived. | Title, content |  |  |  |
| TC_Admin_034 | View Announcement Archives | Confirm admins can review history. | Archive list displays past versions. | Logged-in admin account |  |  |  |
| TC_Admin_035 | Delete Archived Announcement | Confirm admins can remove an archive entry. | Archive item disappears. | Archive ID |  |  |  |
| TC_Admin_036 | Update Ticker Message | Confirm admins can change ticker text. | Ticker updates instantly. | Ticker text |  |  |  |
| TC_Admin_037 | Add Service | Confirm admins can add a service. | Service appears in catalog with price/duration. | Service details |  |  |  |
| TC_Admin_038 | Edit Service | Confirm admins can edit service information. | Updates reflect in booking forms. | Service ID, new data |  |  |  |
| TC_Admin_039 | Delete Service | Confirm admins can remove a service. | Service is removed from listings. | Service ID |  |  |  |
| TC_Admin_040 | Add Event | Confirm admins can post a clinic event. | Event shows in event list/calendar. | Event details |  |  |  |
| TC_Admin_041 | Edit Event | Confirm admins can update an event. | Event displays new details. | Event ID, new data |  |  |  |
| TC_Admin_042 | Delete Event | Confirm admins can delete events. | Event disappears from list. | Event ID |  |  |  |
| TC_Admin_043 | Update Mail Template | Confirm admins can edit email templates. | Template saves and is ready for next send. | Template type, content |  |  |  |
| TC_Admin_044 | Send Single Email | Confirm admins can send a one-off email. | Email queue records message to selected patient. | Patient ID, template type |  |  |  |
| TC_Admin_045 | Send Bulk Email | Confirm admins can send bulk messages. | Emails queue for all selected patients. | Filter criteria, message |  |  |  |

### Patient Record & Post-Procedural Management
| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
| --- | --- | --- | --- | --- | --- | --- | --- |
| TC_Admin_046 | View Patient Records | Confirm admins can see all patient records. | Record list loads with pagination. | Logged-in admin account |  |  |  |
| TC_Admin_047 | Search Patient Records | Confirm patient search works in records. | Matching patients display. | Name or username query |  |  |  |
| TC_Admin_048 | Create Patient Record | Confirm admins can create a record. | Record saves with audit log. | Patient ID, info fields |  |  |  |
| TC_Admin_049 | View Patient Record Details | Confirm full record details display. | Record modal/page shows all fields. | Record ID |  |  |  |
| TC_Admin_050 | Edit Patient Record | Confirm admins can update a record. | Changes save and log entry created. | Record ID, updated info |  |  |  |
| TC_Admin_051 | Delete Patient Record | Confirm admins can delete a record. | Record removed; success message appears. | Record ID |  |  |  |
| TC_Admin_052 | Add Patient History | Confirm admins can add history entries. | Entry saved under history tab. | Record ID, history data |  |  |  |
| TC_Admin_053 | Edit Patient History | Confirm admins can edit history entries. | Updated entry displays with new values. | History ID, new data |  |  |  |
| TC_Admin_054 | Delete Patient History | Confirm admins can delete history entries. | Entry removed from list. | History ID |  |  |  |
| TC_Admin_055 | Add Progress Note | Confirm admins can add progress notes. | Note saved with status, response, next steps. | Record ID, note data |  |  |  |
| TC_Admin_056 | Edit Progress Note | Confirm admins can edit progress notes. | Updated note shows immediately. | Note ID, new data |  |  |  |
| TC_Admin_057 | Delete Progress Note | Confirm admins can delete progress notes. | Note removed from patient record. | Note ID |  |  |  |
| TC_Admin_058 | Send Record to Patient | Confirm admins can email completed records. | Email logs show message sent; record marked as sent. | Record ID |  |  |  |

### Chatbot (ToothTalk) Management
| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
| --- | --- | --- | --- | --- | --- | --- | --- |
| TC_Admin_059 | View Chatbot Settings | Confirm chatbot settings page loads. | Configuration fields display current values. | Logged-in admin account |  |  |  |
| TC_Admin_060 | Update Chatbot Settings | Confirm admins can save chatbot settings. | New welcome text and options apply instantly. | Updated settings |  |  |  |
| TC_Admin_061 | Add FAQ | Confirm admins can add chatbot FAQs. | New question/answer appears for patients. | FAQ question and answer |  |  |  |
| TC_Admin_062 | Edit FAQ | Confirm admins can edit chatbot FAQs. | Updated FAQ saves and shows in chatbot. | FAQ ID, new text |  |  |  |
| TC_Admin_063 | Delete FAQ | Confirm admins can delete chatbot FAQs. | FAQ disappears from list. | FAQ ID |  |  |  |

### Notifications & Appointment Requests
| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
| --- | --- | --- | --- | --- | --- | --- | --- |
| TC_Admin_064 | View Notifications | Confirm pending requests list loads. | Cards display with patient info and actions. | Logged-in admin account |  |  |  |
| TC_Admin_065 | Approve Appointment Request | Confirm admins can approve requests. | Appointment auto-creates and patient notified. | Request ID |  |  |  |
| TC_Admin_066 | Deny Appointment Request | Confirm admins can deny with reason. | Request updates to Denied; reason sent to patient. | Request ID, denial notes |  |  |  |

### Profile & Personal Settings
| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
| --- | --- | --- | --- | --- | --- | --- | --- |
| TC_Admin_067 | View Admin Profile | Confirm admins can view their profile. | Profile shows name, email, contact info. | Logged-in admin account |  |  |  |
| TC_Admin_068 | Update Profile Information | Confirm admins can update contact info. | Updated details save; age auto-recalculates. | New name, email, phone, address |  |  |  |
| TC_Admin_069 | Update Profile Picture | Confirm admins can upload profile photo. | New image displays immediately. | JPG/PNG file |  |  |  |
| TC_Admin_070 | Update Profile Password | Confirm admins can change password inside profile. | Password updates; login works with new password. | Current password, new password, confirmation |  |  |  |

### Activity Logs
| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
| --- | --- | --- | --- | --- | --- | --- | --- |
| TC_Admin_071 | View Activity Logs | Confirm admins can open activity logs. | Table lists all logged actions. | Logged-in admin account |  |  |  |
| TC_Admin_072 | Filter Activity Logs | Confirm filters by user/module/action/date work. | Table refreshes with filtered results. | Filter criteria |  |  |  |
| TC_Admin_073 | View Activity Log Details | Confirm admins can inspect log details. | Side-by-side change details appear. | Activity log ID |  |  |  |

## Staff Test Cases

### Authentication & Access Control
| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
| --- | --- | --- | --- | --- | --- | --- | --- |
| TC_Staff_001 | Staff Login | Confirm staff can log in with valid credentials. | Staff dashboard opens. | Valid staff username, password |  |  |  |
| TC_Staff_002 | Staff Login – Invalid | Confirm invalid logins fail. | Error message shows; access denied. | Invalid username or password |  |  |  |
| TC_Staff_003 | Staff Login – Empty Fields | Confirm empty login fields are blocked. | Validation error displays. | Empty username and password |  |  |  |
| TC_Staff_004 | Staff Logout | Confirm staff can log out. | Session ends; login page returns. | Logged-in staff account |  |  |  |
| TC_Staff_005 | Change Password | Confirm staff can change their password. | Password updates; new password works. | Current password, new password, confirmation |  |  |  |
| TC_Staff_006 | Forgot Password | Confirm staff can request a reset code. | Reset email sent. | Valid staff email |  |  |  |
| TC_Staff_007 | Reset Password | Confirm staff can reset password via code. | Password resets successfully. | Reset code, new password |  |  |  |
| TC_Staff_008 | Block Patient Access | Confirm patients cannot open staff routes. | Access denied; redirected to patient login. | Patient credentials |  |  |  |

### Dashboard
| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
| --- | --- | --- | --- | --- | --- | --- | --- |
| TC_Staff_009 | View Dashboard Stats | Confirm staff dashboard shows key numbers. | Counts for patients, appointments, today’s visits display. | Logged-in staff |  |  |  |
| TC_Staff_010 | View Today’s Appointments | Confirm today’s list renders. | Today’s appointments list shows details. | Day with visits |  |  |  |
| TC_Staff_011 | View Pending Count | Confirm pending appointment count displays. | Pending number matches data. | System with pending items |  |  |  |

### Patient Account Management
| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
| --- | --- | --- | --- | --- | --- | --- | --- |
| TC_Staff_012 | Create Patient Account | Confirm staff can register patients. | New patient account saves with auto age. | Patient info fields |  |  |  |
| TC_Staff_013 | Prevent Duplicate Patient Email | Confirm duplicate patient email blocked. | Validation error displays. | Existing email |  |  |  |
| TC_Staff_014 | Validate Patient Fields | Confirm missing fields stop submission. | Required-field errors display. | Incomplete patient data |  |  |  |
| TC_Staff_015 | Edit Patient Account | Confirm staff can edit patient info. | Updates save with success message. | Patient ID, new info |  |  |  |
| TC_Staff_016 | View Patient Details | Confirm staff can view patient profiles. | Full profile shows. | Patient ID |  |  |  |
| TC_Staff_017 | Delete Patient Account | Confirm staff can delete patient accounts. | Patient disappears from list. | Patient ID |  |  |  |
| TC_Staff_018 | Reset Patient Password | Confirm staff can set a patient’s new password. | Patient can log in with new credentials. | Patient ID, new password |  |  |  |
| TC_Staff_019 | Block Non-Patient Roles | Confirm staff cannot create admin/staff accounts. | System rejects attempt. | Attempted non-patient role |  |  |  |

### Appointment Management
| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
| --- | --- | --- | --- | --- | --- | --- | --- |
| TC_Staff_020 | Create Appointment | Confirm staff can book appointments. | Appointment appears in table and calendar. | Patient, service, time, notes |  |  |  |
| TC_Staff_021 | Avoid Time Conflicts | Confirm staff cannot double-book slots. | Conflict message displays. | Existing timeslot |  |  |  |
| TC_Staff_022 | Respect Blocked Time | Confirm blocked time prevents booking. | Blocked warning displays. | Blocked datetime |  |  |  |
| TC_Staff_023 | View Appointment Details | Confirm staff can open appointment details. | Modal/page shows patient, service, status. | Appointment ID |  |  |  |
| TC_Staff_024 | Edit Appointment | Confirm staff can edit appointments. | Updates save and reflect in UI. | Appointment ID, new data |  |  |  |
| TC_Staff_025 | Update Status | Confirm staff can change statuses with rules enforced. | Status changes; notes required for cancellations only. | Appointment ID, new status |  |  |  |
| TC_Staff_026 | Prevent Delete | Confirm staff cannot delete appointments. | Delete action hidden or denied. | Appointment ID |  |  |  |
| TC_Staff_027 | Search Patients | Confirm patient search works during booking. | Matching patients list appears. | Name or email query |  |  |  |
| TC_Staff_028 | Export Appointments | Confirm staff can export CSV. | CSV downloads with proper format. | Optional date filter |  |  |  |
| TC_Staff_029 | View Appointment Table | Confirm table lists appointments with filters. | Table populated with pagination. | Logged-in staff |  |  |  |
| TC_Staff_030 | Block Time – Clinic Closed | Confirm staff can set closure blocks. | Blocked range shows up. | Start/end date, reason |  |  |  |
| TC_Staff_031 | Block Time – Specific Hours | Confirm staff can block specific hours. | Timeslots blocked and visible. | Date, start/end time |  |  |  |
| TC_Staff_032 | Clear Blocked Time | Confirm staff can clear future blocks. | Block removed; slot reopens. | Future blocked entry |  |  |  |

### Content Management
| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
| --- | --- | --- | --- | --- | --- | --- | --- |
| TC_Staff_033 | Update Announcement | Confirm staff can edit announcements. | Homepage updates with new text. | Title, content |  |  |  |
| TC_Staff_034 | Create Announcement | Confirm staff can add new announcements. | New entry created; old version archived. | Title, content |  |  |  |
| TC_Staff_035 | View Announcement Archives | Confirm staff can see history. | Archive list shows snapshots. | Logged-in staff |  |  |  |
| TC_Staff_036 | Delete Archived Announcement | Confirm staff can remove archive entries. | Archive entry deleted. | Archive ID |  |  |  |
| TC_Staff_037 | Update Ticker | Confirm staff can change ticker text. | Ticker updates on front end. | Ticker message |  |  |  |
| TC_Staff_038 | Add Service | Confirm staff can add services. | Service appears in service list. | Service details |  |  |  |
| TC_Staff_039 | Edit Service | Confirm staff can edit services. | Updates show in list and booking form. | Service ID, new data |  |  |  |
| TC_Staff_040 | Delete Service | Confirm staff can delete services. | Service removed from catalog. | Service ID |  |  |  |
| TC_Staff_041 | Add Event | Confirm staff can add events. | Event appears in event list/calendar. | Event details |  |  |  |
| TC_Staff_042 | Edit Event | Confirm staff can update events. | Updated event displays. | Event ID, new data |  |  |  |
| TC_Staff_043 | Delete Event | Confirm staff can delete events. | Event removed. | Event ID |  |  |  |
| TC_Staff_044 | Update Mail Template | Confirm staff can edit email templates. | Template saves for future sends. | Template type, content |  |  |  |
| TC_Staff_045 | Send Patient Email | Confirm staff can send an email to one patient. | Email queued to recipient. | Patient ID, email type |  |  |  |
| TC_Staff_046 | Send Bulk Email | Confirm staff can send bulk emails. | Emails queued for all selected patients. | Filter criteria, content |  |  |  |

### Post-Procedural Management
| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
| --- | --- | --- | --- | --- | --- | --- | --- |
| TC_Staff_047 | View Patient Records List | Confirm staff can see all patient records. | Record list loads with pagination. | Logged-in staff |  |  |  |
| TC_Staff_048 | Search Patients | Confirm patient search works in records. | Matching patients display. | Name or username query |  |  |  |
| TC_Staff_049 | Create Patient Record | Confirm staff can create patient records. | Record saves with audit log. | Patient ID, record data |  |  |  |
| TC_Staff_050 | View Patient Record | Confirm staff can open record details. | Record modal/page shows full info. | Record ID |  |  |  |
| TC_Staff_051 | Edit Patient Record | Confirm staff can update records. | Changes save and success toast shows. | Record ID, new data |  |  |  |
| TC_Staff_052 | Prevent Record Delete | Confirm staff cannot delete records. | Delete action blocked with message. | Record ID |  |  |  |
| TC_Staff_053 | Add Patient History | Confirm staff can add history entries. | Entry saved to history tab. | Record ID, history data |  |  |  |
| TC_Staff_054 | Edit Patient History | Confirm staff can edit history entries. | Changes show immediately. | History ID, new data |  |  |  |
| TC_Staff_055 | Prevent History Delete | Confirm staff cannot delete history entries. | Delete action blocked. | History ID |  |  |  |
| TC_Staff_056 | Add Progress Note | Confirm staff can add progress notes. | Note saved with status/response. | Record ID, note data |  |  |  |
| TC_Staff_057 | Edit Progress Note | Confirm staff can edit progress notes. | Note updates and displays. | Note ID, new data |  |  |  |
| TC_Staff_058 | Prevent Progress Note Delete | Confirm staff cannot delete progress notes. | Delete blocked. | Note ID |  |  |  |
| TC_Staff_059 | Send Record to Patient | Confirm staff can email completed records. | Email queue shows message; record flagged as sent. | Record ID |  |  |  |

### Patient Record Access
| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
| --- | --- | --- | --- | --- | --- | --- | --- |
| TC_Staff_060 | Access Patient Search | Confirm record search page opens. | Search filters and tabs display. | Logged-in staff |  |  |  |
| TC_Staff_061 | Search by Name | Confirm searching by name works. | Matching names display. | First or last name query |  |  |  |
| TC_Staff_062 | Search by Email | Confirm searching by email works. | Matching email records show. | Email address |  |  |  |
| TC_Staff_063 | Filter Patient List | Confirm list filters (all, with records, etc.) work. | Filtered results display. | Filter option |  |  |  |
| TC_Staff_064 | View Medical Tab | Confirm medical tab shows structured data. | Medical info displays with formatting. | Patient ID |  |  |  |
| TC_Staff_065 | View Visit History Tab | Confirm visit history tab shows chronology. | Visits listed by date. | Patient ID |  |  |  |
| TC_Staff_066 | View Progress Notes Tab | Confirm progress notes tab lists entries. | Notes show with status. | Patient ID |  |  |  |
| TC_Staff_067 | View Appointments Tab | Confirm appointment tab lists visits. | Appointments show with status. | Patient ID |  |  |  |
| TC_Staff_068 | Export Patient Record | Confirm staff can export PDF. | PDF downloads with clinic styling. | Patient ID |  |  |  |

### Notifications & Requests
| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
| --- | --- | --- | --- | --- | --- | --- | --- |
| TC_Staff_069 | View Pending Requests | Confirm notification list loads. | Cards show request type, patient, actions. | Logged-in staff |  |  |  |
| TC_Staff_070 | Approve Request | Confirm staff can approve requests. | Appointment auto-creates; patient notified. | Request ID |  |  |  |
| TC_Staff_071 | Deny Request | Confirm staff can deny requests with reason. | Status becomes Denied; patient notified. | Request ID, denial notes |  |  |  |

### Profile Management
| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
| --- | --- | --- | --- | --- | --- | --- | --- |
| TC_Staff_072 | View Staff Profile | Confirm staff can view personal profile. | Profile shows contact info. | Logged-in staff |  |  |  |
| TC_Staff_073 | Update Profile Info | Confirm staff can update contact info. | Changes save; age auto-calculates. | New name, email, phone, address |  |  |  |
| TC_Staff_074 | Update Profile Picture | Confirm staff can upload photo. | New image renders. | JPG/PNG file |  |  |  |
| TC_Staff_075 | Update Profile Password | Confirm staff can change password from profile. | Password updates; next login works. | Current password, new password, confirmation |  |  |  |

## Patient Test Cases

### Authentication & Access Control
| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
| --- | --- | --- | --- | --- | --- | --- | --- |
| TC_Patient_001 | Patient Login | Confirm patient can log in with valid details. | Patient dashboard opens. | Valid patient email, password |  |  |  |
| TC_Patient_002 | Patient Login – Invalid | Confirm invalid credentials fail. | Error message displays. | Invalid email or password |  |  |  |
| TC_Patient_003 | Patient Login – Empty Fields | Confirm empty login fields are blocked. | Validation error appears. | Empty email and password |  |  |  |
| TC_Patient_004 | Patient Registration | Confirm patient can create an account. | Account saves and redirects to verification/login. | Username, email, password, profile info |  |  |  |
| TC_Patient_005 | Registration – Duplicate Email | Confirm duplicate emails are blocked. | Validation error displays. | Existing email |  |  |  |
| TC_Patient_006 | Registration – Validation | Confirm missing fields stop registration. | Required-field messages show. | Incomplete data |  |  |  |
| TC_Patient_007 | Patient Logout | Confirm patient can log out. | Session ends; login page returns. | Logged-in patient |  |  |  |
| TC_Patient_008 | Change Password | Confirm patient can change password. | Password updates; new password works. | Current password, new password, confirmation |  |  |  |
| TC_Patient_009 | Forgot Password | Confirm patient can request reset. | Reset code email sent. | Patient email |  |  |  |
| TC_Patient_010 | Reset Password | Confirm patient can reset password via code. | Password resets; login succeeds. | Reset code, new password |  |  |  |

### Dashboard
| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
| --- | --- | --- | --- | --- | --- | --- | --- |
| TC_Patient_011 | View Dashboard | Confirm dashboard shows key info. | Profile summary, upcoming visits, announcements display. | Logged-in patient |  |  |  |
| TC_Patient_012 | View Upcoming Appointments | Confirm upcoming list populates. | List shows appointment date, status, doctor. | Patient with upcoming visits |  |  |  |
| TC_Patient_013 | View Recent Announcements | Confirm latest announcements show on dashboard. | Announcement cards display. | Active announcements |  |  |  |

### Calendar & Appointment Requests
| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
| --- | --- | --- | --- | --- | --- | --- | --- |
| TC_Patient_014 | View Calendar | Confirm patient calendar loads. | Calendar shows available slots. | Logged-in patient |  |  |  |
| TC_Patient_015 | View Services | Confirm service list is visible. | Services show with description and price. | Logged-in patient |  |  |  |
| TC_Patient_016 | Submit Appointment Request | Confirm patient can request appointment. | Request saves and status shows Pending. | Service, preferred date/time, reason |  |  |  |
| TC_Patient_017 | Prevent Past Date | Confirm past dates are blocked. | Error message prevents past scheduling. | Past date/time |  |  |  |
| TC_Patient_018 | Respect Blocked Time | Confirm blocked time cannot be requested. | Message shows reason for block (e.g., clinic closed). | Blocked slot |  |  |  |
| TC_Patient_019 | Validate Request Fields | Confirm required fields enforced. | Validation errors show for missing entries. | Incomplete request |  |  |  |
| TC_Patient_020 | View Request Status | Confirm patient sees request statuses. | Pending/Approved/Denied statuses display with notes. | Patient with requests |  |  |  |
| TC_Patient_020A | Request Reschedule | Confirm patient can request reschedule. | Reschedule request saves for eligible appointments. | Appointment ID, new slot |  |  |  |
| TC_Patient_020B | Block Reschedule of Missed | Confirm missed appointments cannot be rescheduled. | Error message shows; option disabled. | Missed appointment |  |  |  |

### Profile Management
| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
| --- | --- | --- | --- | --- | --- | --- | --- |
| TC_Patient_021 | View Profile | Confirm patient can view profile info. | Profile shows personal details. | Logged-in patient |  |  |  |
| TC_Patient_022 | Update Profile Info | Confirm patient can update contact info. | Changes save; success message shows. | New name, phone, address |  |  |  |
| TC_Patient_023 | Update Profile Picture | Confirm patient can upload photo. | New image displays. | JPG/PNG file |  |  |  |
| TC_Patient_024 | Update Password in Profile | Confirm patient can change password from profile. | Password updates successfully. | Current password, new password, confirmation |  |  |  |

### Patient Records & Documents
| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
| --- | --- | --- | --- | --- | --- | --- | --- |
| TC_Patient_025 | View Record List | Confirm patient sees list of medical records. | Records list with sort options displays. | Records available |  |  |  |
| TC_Patient_026 | View Record Details | Confirm patient can open record details. | Page shows full medical record with friendly layout. | Record ID |  |  |  |
| TC_Patient_027 | Download Record PDF | Confirm patient can download record PDF. | PDF downloads with clinic branding. | Record ID |  |  |  |
| TC_Patient_028 | View History List | Confirm patient can see medical history entries. | History entries appear chronologically. | History available |  |  |  |
| TC_Patient_029 | Download History PDF | Confirm patient can download history PDF. | PDF downloads with proper formatting. | History ID |  |  |  |
| TC_Patient_030 | View Progress Notes | Confirm patient can see progress notes. | Notes list with status and comments displays. | Progress notes available |  |  |  |
| TC_Patient_031 | Download Progress Note | Confirm patient can download progress note PDF. | PDF downloads with clinic styling. | Progress note ID |  |  |  |
| TC_Patient_032 | Handle No Records | Confirm system shows friendly empty state. | Message indicates no records available. | Patient with no records |  |  |  |

### Announcements & Content
| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
| --- | --- | --- | --- | --- | --- | --- | --- |
| TC_Patient_033 | View Announcements | Confirm patient can view active announcements. | List of current announcements displays. | Logged-in patient |  |  |  |
| TC_Patient_034 | View Announcement Details | Confirm patient can open announcement card. | Detail view shows full message. | Announcement ID |  |  |  |
| TC_Patient_035 | View Archived Announcements | Confirm patient can read archive history. | Archived announcement list displays. | Archive items available |  |  |  |

### Notifications
| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
| --- | --- | --- | --- | --- | --- | --- | --- |
| TC_Patient_036 | View Notifications | Confirm notification center loads. | List of notifications shows with status badges. | Logged-in patient |  |  |  |
| TC_Patient_037 | View Recent Notifications | Confirm recent notifications filter works. | Recent list shows latest entries. | Notifications present |  |  |  |
| TC_Patient_038 | Mark as Read | Confirm patient can mark notification as read. | Status updates and unread count decreases. | Notification ID |  |  |  |
| TC_Patient_039 | Mark as Unread | Confirm patient can mark notification as unread. | Status changes back to unread. | Notification ID |  |  |  |
| TC_Patient_040 | Mark All Read | Confirm “Mark all as read” works. | All notifications become read. | Multiple unread notifications |  |  |  |
| TC_Patient_041 | Delete Notification | Confirm patient can delete a notification. | Item removed from list. | Notification ID |  |  |  |
| TC_Patient_042 | Clear Read Notifications | Confirm patient can clear all read items. | Read notifications removed. | Read notifications available |  |  |  |
| TC_Patient_043 | View Unread Count | Confirm unread badge updates correctly. | Badge shows accurate count. | Unread notifications present |  |  |  |

### Feedback & Communication
| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
| --- | --- | --- | --- | --- | --- | --- | --- |
| TC_Patient_044 | View Feedback Page | Confirm feedback page loads. | Feedback form and eligible appointments display. | Logged-in patient |  |  |  |
| TC_Patient_045 | View Completed Appointments | Confirm completed appointments appear for feedback. | List shows eligible visits. | Patient with completed visits |  |  |  |
| TC_Patient_046 | Submit Feedback | Confirm patient can submit feedback. | Feedback saves; success message displays. | Appointment ID, rating, comments |  |  |  |
| TC_Patient_047 | Feedback Validation | Confirm required feedback fields enforced. | Validation errors show for missing rating/comments. | Incomplete feedback data |  |  |  |
| TC_Patient_048 | View Feedback History | Confirm patient can review submitted feedback. | Feedback history list displays. | Submitted feedback exists |  |  |  |
| TC_Patient_049 | Prevent Duplicate Feedback | Confirm system blocks duplicate submissions. | Message explains feedback already submitted. | Appointment with existing feedback |  |  |  |

### General Experience
| Test Case ID | Case Name | Case Description | Expected Result | Test Data | Actual Result | Passed / Failed | Remarks |
| --- | --- | --- | --- | --- | --- | --- | --- |
| TC_Patient_050 | View About Us Page | Confirm About Us page loads. | Clinic summary page appears. | Logged-in patient |  |  |  |
| TC_Patient_051 | Navigation Menu | Confirm menu links work. | Each menu item opens correct page. | Menu items |  |  |  |
| TC_Patient_052 | Responsive Design | Confirm portal works on multiple devices. | Layout adapts for mobile, tablet, desktop. | Different screen widths |  |  |  |

