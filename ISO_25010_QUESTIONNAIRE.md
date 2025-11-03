# ISO 25010 Quality Assessment Questionnaire
## JValera Dental Clinic Management System

**Instructions:** Please rate each statement using the Likert scale:
- **1 = Strongly Disagree**
- **2 = Disagree**
- **3 = Neutral**
- **4 = Agree**
- **5 = Strongly Agree**

---

## 1. Functional Suitability (Domain)

### a. Functional Completeness
**The system encompasses all required functions to be performed.**

**+ Dentist (Admin):** "I can manage all aspects of the clinic, including creating/editing staff accounts, configuring system settings, managing appointments, viewing comprehensive dashboard statistics (total patients, appointments, staff members, today's appointments), managing content (announcements, services, mail templates, chatbot FAQ), viewing activity logs of all staff actions, accessing patient records and history, and generating reports on clinic performance."

**+ Staff:** "I can efficiently manage appointment scheduling (booking, rescheduling, canceling, updating status), create and manage patient records including medical history, dental history, and progress notes, search for patients and view their complete records, send patient records to patients, manage notifications and appointment requests, access the dashboard with today's appointments and statistics, and perform all post-procedural documentation tasks."

**+ Patient:** "I can easily view my appointment schedule in calendar view, request new appointments (walk-in requests) or reschedule existing appointments through the system, access my personal information and medical records sent by staff, view my appointment history, receive and manage notifications (mark as read/unread, delete, filter), interact with the chatbot FAQ for help, and view clinic announcements and services."

---

### b. Functional Correctness
**The system provides accurate and precise results when performing functions.**

**+ Dentist (Admin):** "The system accurately prevents appointment scheduling conflicts, validates data entry correctly (such as enforcing required fields like gender and birthday for staff accounts), prevents duplicate email addresses when creating users, displays accurate dashboard statistics (patient counts, appointment counts), and correctly enforces appointment status transitions (e.g., confirmed appointments can only become completed, not cancelled without proper notes)."

**+ Staff:** "The system accurately calculates appointment end times based on duration, prevents scheduling during blocked time periods, validates all form inputs correctly and shows appropriate error messages, accurately filters and displays notifications (All/Unread/Read), correctly auto-calculates patient age from birthday when updating records, and prevents data entry errors through validation rules."

**+ Patient:** "The system accurately displays my appointment schedule and details, correctly filters my notifications (showing accurate unread counts), accurately calculates appointment durations and times, displays my personal information correctly, and provides accurate responses from the chatbot FAQ system."

---

### c. Functional Appropriateness
**The system functions facilitate efficient accomplishment of tasks and objectives.**

**+ Dentist (Admin):** "The separate admin portal with username/email login is appropriate for administrative workflow, the dashboard provides quick access to key statistics and today's appointments, the user management interface allows efficient account creation with auto-calculation of age from birthday, and the activity log system provides appropriate audit trail for all clinic operations."

**+ Staff:** "The staff portal with username-based login is appropriate for clinic staff workflow, the appointment management system matches real-world scheduling operations, the post-procedural form allows efficient patient record management with auto-fill capabilities, and the notification system efficiently handles appointment requests (approve/deny workflow) matching clinic operations."

**+ Patient:** "The patient portal interface is appropriate for self-service appointment management, the calendar view provides intuitive appointment scheduling, the appointment request system (walk-in and reschedule) matches my needs, and the notification system provides relevant updates about appointment status changes and record updates."

---

## 2. Performance Efficiency (Domain)

### a. Time Behavior
**The system responds quickly and processes functions within acceptable time.**

**+ Dentist (Admin):** "The dashboard loads quickly (within 2 seconds), appointment management operations respond promptly, user account management functions process rapidly, content management pages load efficiently, and activity log queries execute quickly without noticeable delays."

**+ Staff:** "The appointment management interface responds quickly when creating or updating appointments, patient record searches complete promptly, notification pages load within 2 seconds, post-procedural forms submit and process efficiently, and dashboard statistics display quickly without waiting."

**+ Patient:** "The calendar view loads quickly, appointment request submissions process immediately via AJAX, notification pages load within acceptable time (under 2 seconds), dashboard displays promptly, and chatbot responses appear quickly after typing."

---

### b. Resource Utilization
**The system uses system resources efficiently.**

**+ Dentist (Admin):** "The system efficiently handles database queries for statistics and reports, pagination works smoothly for large lists (users, appointments), notification filtering processes efficiently on the server side, and the system doesn't experience memory issues when managing multiple operations simultaneously."

**+ Staff:** "The system efficiently handles patient searches and filtering, pagination of notifications (20 per page) works smoothly, appointment queries execute efficiently, and the system maintains good performance even when managing multiple patient records and appointments simultaneously."

**+ Patient:** "The notification pagination loads efficiently with 20 items per page, calendar view renders quickly even with multiple appointments, notification dropdown auto-refreshes every 30 seconds without slowing down the system, and the chatbot interface responds efficiently without browser lag."

---

### c. Capacity
**The system handles maximum limits and growing data volumes effectively.**

**+ Dentist (Admin):** "The system handles growing numbers of patients, appointments, and staff accounts efficiently, activity logs can scale to handle extensive audit trails, dashboard statistics calculate accurately even with large datasets, and the system supports concurrent access by multiple administrators."

**+ Staff:** "The system efficiently manages increasing numbers of patient records and appointments, notification pagination handles large volumes of notifications smoothly, patient search works efficiently even with many registered patients, and the system supports multiple staff members working simultaneously."

**+ Patient:** "The notification system handles growing numbers of notifications efficiently through pagination, the appointment history displays correctly even with many past appointments, and the system maintains performance as my appointment and notification data grows over time."

---

## 3. Compatibility (Domain)

### a. Coexistence
**The system operates efficiently alongside other products without interference.**

**+ Dentist (Admin):** "The system works well with other software applications running on the server, database operations don't interfere with other database-dependent applications, mail system integration works alongside other email services, and the system can coexist with other Laravel applications on the same server."

**+ Staff:** "The system operates smoothly when I have other browser applications open, email notifications are sent reliably alongside other email communications, and system operations don't interfere with my other work applications running simultaneously."

**+ Patient:** "The system works well alongside other web applications I use, notification emails are delivered reliably alongside other emails, and I can access the patient portal without conflicts with other browser-based applications."

---

### b. Interoperability
**The system can exchange information with other systems effectively.**

**+ Dentist (Admin):** "The system successfully sends email notifications using SMTP/mail services, the database can export data for integration with other systems, the API routes structure allows for future external system integrations, and the system uses standard data formats (JSON for AJAX) that are compatible with other systems."

**+ Staff:** "Email notifications are sent reliably to external email services, patient data can be accessed through standard database queries for potential integration, and the system communicates effectively with mail servers for appointment confirmations and notifications."

**+ Patient:** "I receive email notifications reliably from the system, appointment confirmations and updates are delivered to my email provider, and the system communicates effectively with external email services for password reset functionality."

---

## 4. Interaction Capability (Domain)

### a. Appropriateness Recognizability
**I can easily recognize whether the system is appropriate for my needs.**

**+ Dentist (Admin):** "The separate admin portal with clear branding and shield icon makes it obvious this is for administrators, the dashboard clearly shows clinic management statistics indicating administrative purpose, the menu structure (account management, appointments, content management, activity logs) clearly indicates comprehensive clinic management capabilities, and the interface design makes it immediately clear this system is appropriate for administrative tasks."

**+ Staff:** "The staff portal with distinct branding makes it clear this is for staff members, the navigation menu (appointments, post-procedural, notifications) clearly indicates staff-specific functions, the interface design with appointment management and patient record features clearly shows this system is appropriate for clinic staff operations, and visual indicators (status colors, badges) help me understand the system's purpose quickly."

**+ Patient:** "The patient portal clearly shows this is for patients through the interface design, the calendar view and appointment request options make it immediately obvious this system is for managing my appointments, notification badges and appointment history clearly indicate patient-specific functionality, and the chatbot and services display make it clear this system serves patient needs."

---

### b. Learnability
**I can learn to use the system functions effectively within a reasonable time.**

**+ Dentist (Admin):** "The consistent interface patterns across all admin pages (similar forms, buttons, layouts) make it easy to learn, familiar web conventions (navigation menus, tables, modals) help me understand the system quickly, the dashboard provides a clear overview that helps me learn system capabilities, and form assistance (auto-calculation, validation messages) guides me in learning proper usage."

**+ Staff:** "The consistent use of Bootstrap components and familiar interface patterns make the system easy to learn, auto-fill capabilities in patient record forms help me learn efficient data entry, clear form labels and validation messages guide my learning, and the navigation structure is intuitive and helps me learn system functions quickly."

**+ Patient:** "The calendar interface is intuitive and easy to learn, consistent button styles and form patterns help me understand the system quickly, the chatbot provides helpful guidance that aids learning, and notification badges and visual feedback help me learn system features effectively."

---

### c. Operability
**The system has attributes that make it easy to operate and control.**

**+ Dentist (Admin):** "I can navigate efficiently using keyboard (Enter key submits forms, Tab navigation), bulk operations like viewing multiple appointments are straightforward, filter controls for users and appointments work intuitively, search functionality helps me quickly find information, and the dashboard provides quick access to key functions."

**+ Staff:** "Keyboard accessibility (Enter key for form submission) makes operations efficient, filter controls for notifications (All/Unread/Read) with persistent state are easy to use, patient search functionality allows quick access to records, bulk operations like marking all notifications as read are straightforward, and the calendar interface makes appointment management easy to control."

**+ Patient:** "The Enter key submits appointment requests making operations convenient, filter controls for notifications are easy to use, the calendar interface allows easy appointment viewing and requesting, quick actions like requesting appointments are straightforward, and the notification dropdown provides easy access to recent updates."

---

### d. User Error Protection
**The system prevents me from making operational errors.**

**+ Dentist (Admin):** "Input validation prevents me from entering invalid data (like duplicate emails, missing required fields), the system prevents scheduling conflicting appointments automatically, status transition validation prevents inappropriate status changes (e.g., confirmed to cancelled requires notes), and form validation messages clearly guide me to correct errors."

**+ Staff:** "The system prevents appointment scheduling during blocked times, validation rules prevent invalid data entry (required fields, email formats), duplicate prevention stops me from creating duplicate records, status validation ensures proper appointment status transitions, and clear validation messages guide me to correct input errors."

**+ Patient:** "The system prevents me from scheduling conflicting appointments, validation prevents submitting forms with invalid data (required fields, proper date formats), the system shows clear error messages when I make mistakes, and input validation guides me to enter correct information for appointment requests."

---

### e. User Engagement
**The system presents information in an inviting and motivating manner encouraging continued interaction.**

**+ Dentist (Admin):** "The modern UI with Bootstrap components and color-coded status badges is visually appealing, the dashboard with statistics and calendar provides engaging overview, toast notifications and success messages provide positive feedback, and the clean interface design encourages continued use of the system."

**+ Staff:** "The interactive calendar and appointment management interface is engaging, color-coded request types (walk-in: blue, reschedule: teal) and status badges make the interface visually interesting, notification badges and real-time updates encourage interaction, and the modern UI design motivates continued system use."

**+ Patient:** "The interactive chatbot with welcome messages and quick action chips is engaging, the calendar view with visual appointment displays is motivating, notification badges and color-coded status updates encourage interaction, the modern responsive design is visually appealing, and the chatbot's typing indicators and interactive responses make the experience engaging."

---

### f. User Assistance
**The system provides assistance that helps me achieve my goals effectively.**

**+ Dentist (Admin):** "The activity log system provides comprehensive audit trail assistance, dashboard statistics help me understand clinic performance, form validation messages provide guidance for correct data entry, and the system documentation guides me through administrative tasks."

**+ Staff:** "The chatbot FAQ system provides 24/7 assistance for common questions, help pattern recognition responds to 'help', 'assist', 'support' queries, comprehensive patient record management tools assist in my daily tasks, and notification system assists me in managing appointment requests effectively."

**+ Patient:** "The chatbot help system provides assistance for clinic hours, services, appointments, and pricing, help patterns ('help', 'assist', 'support') trigger helpful responses, FAQ database provides searchable answers, quick intent chips guide me to common queries, and form labels and validation messages assist me in completing tasks correctly."

---

## 5. Reliability (Domain)

### a. Maturity
**The system meets reliability needs under normal operation.**

**+ Dentist (Admin):** "The system handles errors gracefully without crashing, database operations complete successfully, multiple validation layers prevent data corruption, activity logging provides reliable audit trail, and the system operates stably even with extensive use throughout the day."

**+ Staff:** "The system reliably processes appointment management operations, patient record creation and updates complete successfully, error handling prevents system failures during normal operations, validation rules reliably prevent invalid data entry, and the system performs consistently during daily clinic operations."

**+ Patient:** "The system reliably displays my appointment information, notification updates are delivered consistently, appointment requests are processed reliably, the chatbot responds consistently, and the system maintains reliable performance for my daily interactions."

---

### b. Availability
**The system is operational and accessible when I need to use it.**

**+ Dentist (Admin):** "The system is accessible when I need to manage clinic operations, session management reliably maintains my login state, database connections remain stable during use, error recovery prevents complete system failure, and the system remains available throughout my workday."

**+ Staff:** "The system is reliably available when I need to manage appointments and patient records, session persistence maintains my login reliably, the system remains accessible during clinic hours, and error recovery ensures the system stays operational even if minor errors occur."

**+ Patient:** "The system is accessible when I need to check appointments or request changes, my session remains active reliably, the patient portal is available when I need to access my information, and the system remains accessible for my appointment management needs."

---

### c. Fault Tolerance
**The system operates correctly despite the presence of hardware or software faults.**

**+ Dentist (Admin):** "The system handles input errors gracefully without crashing, database constraint violations are handled properly, transaction rollback ensures data integrity during partial failures, error boundaries prevent single request failures from affecting the entire system, and graceful error handling ensures system continues operating even when issues occur."

**+ Staff:** "Input validation prevents faults from causing system failures, database constraints maintain data integrity even when errors occur, error handling prevents cascading failures, and the system continues operating correctly even when encountering validation errors or constraint violations."

**+ Patient:** "The system handles my input errors gracefully (shows validation messages instead of crashing), form submission errors are handled properly without data loss, and the system continues operating correctly even when I make mistakes or enter invalid data."

---

### d. Recoverability
**The system can recover data and re-establish desired state after interruptions or failures.**

**+ Dentist (Admin):** "Activity logs enable recovery of audit trail information, database migration system allows schema recovery, password recovery system enables account recovery, and foreign key constraints ensure data integrity during recovery operations."

**+ Staff:** "Activity logs track changes enabling recovery of modifications, password recovery allows account restoration, and database transactions ensure data can be recovered after interruptions."

**+ Patient:** "Password recovery system allows me to regain account access, appointment history is preserved enabling recovery of past information, and my personal data remains accessible for recovery after any interruptions."

---

## 6. Security (Domain)

### a. Confidentiality
**The system ensures data is accessible only to authorized users.**

**+ Dentist (Admin):** "I have access to all clinic data through secure authentication, separate admin portal prevents unauthorized access, password protection with hashing ensures only authorized administrators can access the system, role-based access control ensures only admins can manage user accounts and view activity logs, and CSRF protection prevents unauthorized requests to administrative functions."

**+ Staff:** "I can only access patient records and appointments through secure staff portal authentication, password protection ensures only authorized staff members can log in, role-based access control prevents patients from accessing staff functions, and session management ensures my access remains secure throughout my session."

**+ Patient:** "I can only access my own personal information and appointment data, separate patient portal with authentication prevents unauthorized access to my records, password protection ensures only I can access my account, role-based access control prevents others from viewing my patient information, and the system ensures other patients cannot access my data."

---

### b. Integrity
**The system prevents unauthorized access to or modification of data.**

**+ Dentist (Admin):** "Input validation prevents malicious data injection, SQL injection is prevented through Eloquent ORM, XSS attacks are prevented through Blade template escaping, file upload validation ensures only authorized images are uploaded, database constraints (foreign keys, unique constraints) maintain data integrity, and transaction support ensures atomic operations preventing partial updates."

**+ Staff:** "Input validation prevents me from entering malicious data, validation rules prevent unauthorized data modification, database constraints ensure data integrity during updates, and file upload validation ensures secure image handling."

**+ Patient:** "Input validation prevents me from submitting malicious data in appointment requests, the system validates my data entry ensuring integrity, and database constraints maintain the integrity of my personal information even if I make mistakes."

---

### c. Non-repudiation
**The system can prove that actions or events have taken place and cannot be denied later.**

**+ Dentist (Admin):** "Activity logs record all administrative actions with timestamps and user identification, all database records include created_at and updated_at timestamps, action details in logs (e.g., 'Admin created user', 'Admin updated appointment') provide proof of actions, and activity logging tracks all modifications with before/after values proving what was changed."

**+ Staff:** "Activity logs record all my staff actions (creating patient records, updating appointments) with my user identification and timestamps, appointment status changes are tracked with my user ID and timestamp, patient record modifications are logged with details proving what I changed, and all actions include IP address and user agent information."

**+ Patient:** "Appointment requests are recorded with timestamps proving when I made requests, notification read/unread status is tracked with timestamps, and appointment history provides proof of all my appointments and their status changes."

---

### d. Accountability
**Actions in the system can be traced uniquely to the user who performed them.**

**+ Dentist (Admin):** "All administrative actions are tracked with my user identification in activity logs, user account management actions (create, update, delete) are logged with my username, appointment modifications show who made the changes, and the system tracks which administrator performed each action through user_id in logs."

**+ Staff:** "All my patient record actions are tracked with my user identification, appointment management actions are logged with my username, activity logs include my user_id proving I performed each action, and the system tracks which staff member made each modification."

**+ Patient:** "My appointment requests are tracked with my patient identification, notification interactions are associated with my user account, and the system tracks my user_id for all patient actions."

---

### e. Authenticity
**The system can prove the identity of users and verify they are who they claim to be.**

**+ Dentist (Admin):** "Username/email and password authentication verifies my identity, secure password hashing (bcrypt) protects my credentials, session regeneration on login ensures secure session establishment, email verification codes for password reset verify my identity, and role verification ensures only administrators can access admin functions."

**+ Staff:** "Username and password authentication verifies my identity as staff, secure password hashing protects my credentials, session regeneration ensures secure authentication, 6-digit verification codes for password reset verify my email identity, and role verification (role_id 2) ensures only staff members can access staff portal."

**+ Patient:** "Email/username and password authentication verifies my identity, secure password hashing protects my credentials, password reset with email verification codes verifies my identity, session management ensures secure authentication, and the system verifies I am a registered patient before granting access."

---

## 7. Maintainability (Domain)

### a. Modularity
**The system is composed of discrete components where changes to one component have minimal impact on others.**

**+ Dentist (Admin):** "The MVC architecture (Models, Views, Controllers) allows changes to views without affecting business logic, service classes (MailService, NotificationService) can be modified without affecting controllers, separate controllers for different features (AppointmentController, AccountManagementController) allow isolated changes, and component-based views allow UI changes without affecting functionality."

**+ Staff:** "The modular structure allows appointment management to work independently from patient record management, notification system functions separately from appointment scheduling, and changes to one feature don't disrupt other features I use."

**+ Patient:** "The modular design means changes to notifications don't affect calendar functionality, chatbot improvements don't impact appointment management, and updates to one feature don't disrupt other patient features I use."

---

### b. Reusability
**System components can be reused effectively.**

**+ Dentist (Admin):** "Blade components are reused across admin pages providing consistent UI, MailService is reused for all email notifications, reusable validation rules ensure consistent data validation, and service classes can be used across multiple controllers."

**+ Staff:** "Form components are reused providing consistent patient record entry, notification system components are reused across staff pages, and reusable service classes ensure consistent functionality across features."

**+ Patient:** "Notification components are reused providing consistent notification experience, calendar components are reused for appointment views, and reusable chatbot components provide consistent help experience."

---

### c. Analysability
**System deficiencies or failures can be diagnosed effectively.**

**+ Dentist (Admin):** "Activity logs help diagnose what actions were performed, error logging captures issues for troubleshooting, clear code organization (separate controllers, models) helps locate problems, database migrations track schema changes aiding diagnosis, and comprehensive documentation helps analyze system behavior."

**+ Staff:** "Activity logs track my actions helping diagnose any issues, error messages clearly indicate what went wrong, validation messages help identify input problems, and the organized system structure helps locate functionality."

**+ Patient:** "Clear error messages help me understand what went wrong, validation feedback identifies input issues, notification status helps me understand system state, and organized interface structure helps me navigate and understand system behavior."

---

### d. Modifiability
**The system can be effectively modified without introducing defects.**

**+ Dentist (Admin):** "Environment configuration (.env file) allows changes without code modification, database migrations allow schema changes easily, service classes allow changing email implementation without affecting controllers, mail templates stored in database can be modified through admin interface, and config files allow easy system behavior modifications."

**+ Staff:** "The system structure allows new features to be added without disrupting existing functionality, form modifications can be made without affecting data processing, and the modular design allows improvements without breaking current operations."

**+ Patient:** "System updates don't break my existing appointments or data, interface improvements are made without losing functionality, and modifications maintain backward compatibility with my patient information."

---

### e. Testability
**Test criteria can be established and tests can be performed effectively.**

**+ Dentist (Admin):** "Comprehensive test case documentation (362 test cases) enables systematic testing, PHPUnit framework supports unit and feature testing, isolated components (MVC) enable component-level testing, database factories allow test data generation, and separate test database configuration enables safe testing."

**+ Staff:** "The modular structure allows testing of individual features (appointments, patient records), validation rules are testable independently, and the system structure supports testing of staff-specific functionality."

**+ Patient:** "Patient portal features can be tested independently, appointment request system is testable separately from other features, and the modular design allows comprehensive testing of patient functionality."

---

## 8. Portability (Domain)

### a. Adaptability
**The system can be effectively adapted for different environments.**

**+ Dentist (Admin):** "Environment configuration (.env file) allows adaptation to different environments (dev, staging, production), database abstraction supports multiple database systems (MySQL, PostgreSQL), storage system supports different storage drivers (local, S3), mail system supports multiple mail drivers (SMTP, Mailgun, SES), and config files allow easy adaptation to different server setups."

**+ Staff:** "The system works across different devices and browsers, mail integration adapts to different email services, and the system adapts to different deployment environments without disrupting functionality."

**+ Patient:** "The responsive design adapts to different devices (desktop, tablet, mobile), the system works across different web browsers, and the interface adapts to different screen sizes effectively."

---

### b. Installability
**The system can be effectively installed in specified environments.**

**+ Dentist (Admin):** "Composer manages PHP dependencies making installation straightforward, database migrations automate database setup, seeders populate initial data (roles, services, templates), .env.example file guides environment configuration, and Laravel artisan commands automate setup tasks."

**+ Staff:** "The system installation process is documented and straightforward, initial data setup (roles, services) is automated, and installation doesn't require extensive technical knowledge."

**+ Patient:** "The web-based system doesn't require installation on my device, I can access it through any web browser, and the system is accessible immediately after clinic setup."

---

### c. Replaceability
**The system can be replaced by another system while maintaining data compatibility.**

**+ Dentist (Admin):** "Standard SQL database allows data export for migration to other systems, API route structure supports integration with replacement systems, standard protocols (HTTP, SMTP, SQL) ensure compatibility, database migrations document schema for easy transfer, and open standards (HTML, CSS, JavaScript) ensure portability."

**+ Staff:** "Standard database format allows patient data to be exported, appointment data structure is compatible with other systems, and the system uses standard formats enabling future migration."

**+ Patient:** "My data is stored in standard formats allowing export if needed, appointment information can be accessed through standard queries, and my personal information can be retrieved in compatible formats."

---

## 9. Safety (Domain)

### a. Risk Mitigation
**The system reduces potential risks to people, property, or the environment.**

**+ Dentist (Admin):** "Patient medical data protection through access control reduces privacy breach risks, secure password handling prevents unauthorized access risks to sensitive medical information, CSRF protection prevents malicious request risks, SQL injection prevention reduces database compromise risks, XSS prevention protects against cross-site scripting risks, and activity logs enable risk detection through audit trail."

**+ Staff:** "Access control reduces unauthorized access risks to patient data, password security prevents credential theft risks, input validation prevents malicious input risks, and secure session management prevents session hijacking risks."

**+ Patient:** "Access control ensures only I can access my medical records reducing privacy risks, password protection prevents unauthorized access risks, secure data handling protects my personal information, and validation prevents malicious input risks to my account."

---

### b. Failure Avoidance
**The system avoids failures that could cause harm.**

**+ Dentist (Admin):** "Error handling prevents system crashes that could cause data loss harmful to patient care, database transactions prevent partial data updates that could cause medical errors, data integrity constraints (foreign keys) prevent data corruption leading to incorrect medical information, multiple validation layers prevent invalid data entry that could cause harm, and backup capabilities ensure data recovery preventing permanent loss."

**+ Staff:** "Database transactions ensure data integrity preventing medical record errors, validation layers prevent invalid patient data entry that could cause harm, error handling prevents system failures that could interrupt patient services, and graceful error handling prevents cascading failures."

**+ Patient:** "Input validation prevents errors in my appointment requests that could cause scheduling problems, error handling prevents system failures that could cause me to lose appointment information, data validation ensures my personal information is stored correctly preventing errors, and the system avoids failures that could prevent me from accessing critical appointment information."

---

## Scoring Instructions

For each statement, rate using:
- **1 = Strongly Disagree**
- **2 = Disagree**
- **3 = Neutral**
- **4 = Agree**
- **5 = Strongly Agree**

Calculate average scores for each sub-characteristic within each domain to assess overall quality ratings.

---

**Reference:** ISO/IEC 25010:2023 Software Quality Model
**System:** JValera Dental Clinic Management System

