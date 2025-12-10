# Hierarchical Data Model Diagrams by User Type

## Overview

This document provides comprehensive hierarchical data model diagrams for each user type in the Dental Clinic Management System. The diagrams illustrate the complete data structure accessible to each role, showing relationships, attributes, and hierarchical organization of information.

### Purpose

These diagrams serve multiple purposes:
- **System Documentation**: Provide clear visualization of data structures for each user role
- **Access Control Reference**: Show what data and features are available to each user type
- **Development Guide**: Assist developers in understanding data relationships and access patterns
- **Security Planning**: Help identify data access boundaries and security requirements
- **User Training**: Help users understand what information they can access and manage

### System Architecture

The Dental Clinic Management System uses a role-based access control (RBAC) model with three primary user roles:
1. **Patient (role_id: 3)**: End users receiving dental services
2. **Staff (role_id: 2)**: Clinic employees managing operations
3. **Administrator (role_id: 1)**: System administrators with full access

Each role has distinct access levels and capabilities, with data access enforced at both the database and application levels.

### Diagram Structure

Each hierarchical diagram follows a tree structure where:
- **Root Level**: Represents the user type
- **Branch Levels**: Represent major data categories or modules
- **Leaf Levels**: Represent individual data fields or attributes
- **Indentation**: Shows parent-child relationships and data hierarchy

### Data Relationships

The diagrams show how different data entities relate to each other:
- **One-to-Many**: A user can have multiple appointments, records, notifications
- **Many-to-One**: Multiple appointments can belong to one patient
- **One-to-One**: A user has one profile, one set of credentials
- **Many-to-Many**: Conversations involve multiple participants

### Access Patterns

- **Patient**: Read/write access limited to own data
- **Staff**: Read/write access to all patient data, limited write access to system configuration
- **Administrator**: Full read/write access to all data and system configuration

---

## 1. Patient User (role_id: 3)

### Description
The Patient user represents an individual who receives dental care services from the clinic. Patients have the most restricted access level, limited to viewing and managing only their own personal data, appointments, and records. This role is designed to provide patients with self-service capabilities while maintaining privacy and data security.

### Data Structure Breakdown

```
Patient
├── patientID
├── credentials
│   ├── email
│   ├── password
│   └── roleType (Patient)
├── accountInfo
│   ├── username
│   ├── fullName
│   ├── email
│   ├── contactNo
│   ├── birthday
│   └── bio
├── profile
│   ├── profilePicture
│   └── mustChangePassword
├── Appointment
│   ├── appointmentID
│   ├── appointmentType
│   │   ├── serviceID
│   │   ├── description
│   │   ├── price
│   │   └── duration
│   ├── schedule
│   │   ├── dateTime
│   │   ├── durationMinutes
│   │   ├── createDM
│   │   └── schedStatus
│   │       ├── pending
│   │       │   ├── notifSent
│   │       │   └── completedStatus
│   │       ├── confirmed
│   │       │   ├── notifSent
│   │       │   ├── rescheduleRemark
│   │       │   └── cancelRemark
│   │       ├── completed
│   │       │   ├── notifSent
│   │       │   └── completedID
│   │       └── cancelled
│   │           ├── notifSent
│   │           └── cancelRemark
│   ├── reasonForVisit
│   ├── notes
│   ├── isNewPatient
│   └── feedback
│       ├── rating
│       ├── patientFeedback
│       ├── feedbackComment
│       ├── ratedAt
│       └── feedbackSubmittedAt
├── Record
│   ├── patientNumber
│   ├── personalInfo
│   │   ├── homeAddress
│   │   ├── dateOfBirth
│   │   ├── age
│   │   ├── sex
│   │   ├── nickname
│   │   ├── religion
│   │   └── occupation
│   ├── contact
│   ├── guardianInfo
│   │   ├── guardianName
│   │   ├── guardianContact
│   │   └── guardianOccupation
│   ├── medicalHistory
│   ├── medications
│   │   └── currentMedications
│   ├── allergies
│   │   └── allergiesDetail
│   ├── healthQuestions
│   ├── womenHealth
│   │   ├── isPregnant
│   │   ├── isNursing
│   │   └── takesBirthControl
│   ├── physicianInfo
│   │   ├── physicianName
│   │   ├── physicianSpecialty
│   │   ├── physicianOfficeAddress
│   │   └── physicianContact
│   ├── dentalHistory
│   │   ├── previousDentist
│   │   ├── lastDentalVisit
│   │   └── treatmentDone
│   ├── clinicalInfo
│   │   ├── chiefComplaint
│   │   ├── diagnosis
│   │   └── treatmentPlan
│   ├── notes
│   ├── patientHistories
│   │   ├── visitDate
│   │   ├── healthStatus
│   │   ├── allergies
│   │   ├── procedurePerformed
│   │   ├── materialsUsed
│   │   ├── anesthesiaUsed
│   │   ├── complications
│   │   ├── postOperativeInstructions
│   │   └── followUpNotes
│   └── progressNotes
│       ├── noteDate
│       ├── progressDescription
│       ├── treatmentResponse
│       ├── amountPaid
│       ├── balance
│       ├── conforme
│       ├── nextSteps
│       ├── otherNotes
│       └── status
├── Feedback
│   ├── feedbackID
│   ├── ratingApp
│   ├── ratingClinic
│   └── date
├── Notification
│   ├── notifID
│   ├── type
│   ├── title
│   ├── message
│   ├── icon
│   ├── data
│   ├── isRead
│   ├── readAt
│   └── createDM
├── ConversationLogs
│   ├── conversationID
│   ├── patientID
│   ├── staffID
│   ├── adminID
│   ├── status
│   ├── lastMessageAt
│   └── messages
│       ├── messageID
│       ├── senderID
│       ├── senderType
│       ├── message
│       ├── attachments
│       ├── isRead
│       ├── readAt
│       └── dateTime
└── refreshToken
```

### Detailed Component Descriptions

The Patient user's data structure encompasses several key areas that enable self-service functionality while maintaining data privacy and security. The **patientID** serves as the primary unique identifier for each patient in the system, typically implemented as a bigint(20) unsigned auto-incrementing value that ensures each patient record has a distinct identifier. The **credentials** section contains critical authentication information, where the **email** field stores the patient's email address used for login and communication, validated to ensure uniqueness across the system. The **password** field stores a hashed version of the patient's password using secure hashing algorithms such as bcrypt, ensuring that plain text passwords are never stored in the database. The **roleType** attribute is specifically set to "Patient" (corresponding to role_id: 3) and determines the access level and capabilities available to the user within the system.

The **accountInfo** section maintains comprehensive personal information about the patient. The **username** field provides an alternative login identifier that must be unique across all users, allowing patients to log in using either their username or email address. The **fullName** field stores the patient's complete legal name as a varchar(255), which is used for identification and official documentation. The **email** field in accountInfo mirrors the credentials email and serves as the primary contact method for system communications, appointment reminders, and password reset functionality. The **contactNo** field stores the patient's phone number as a varchar(255), enabling direct communication for appointment confirmations and urgent notifications. The **birthday** field stores the patient's date of birth as a date type, which is used for age calculation, demographic analysis, and age-appropriate service recommendations. The **bio** field is an optional text field that allows patients to provide additional personal information or preferences that may be relevant to their care.

The **profile** section manages user interface and security settings. The **profilePicture** field stores the file path to the patient's profile image as a varchar(255), allowing patients to personalize their account with a profile photo. The **mustChangePassword** field is a boolean flag that defaults to true, forcing patients to change their password upon first login or when administrators require a password reset for security purposes. This field is automatically set to false once the patient successfully changes their password, allowing normal login procedures to proceed.

Appointment management is a central feature that allows patients to view and manage only their own appointments. The **appointmentID** serves as the primary key for each appointment record, implemented as a bigint(20) unsigned that uniquely identifies each appointment in the system. The **appointmentType** section provides detailed information about the service associated with the appointment. The **serviceID** is a foreign key that links to the services table, establishing a relationship between the appointment and the specific dental service being provided. The **description** field contains a text description of the service, explaining what treatment or procedure will be performed during the appointment. The **price** field stores the cost of the service as a decimal(10,2), representing the monetary value in the system's currency. The **duration** field specifies the expected length of the appointment in minutes, typically stored as an integer value that helps in scheduling and calendar management.

The **schedule** section manages all temporal aspects of the appointment. The **dateTime** field stores the appointment's start date and time as a datetime type, combining both date and time information in a single field. The **durationMinutes** field specifies how long the appointment will last, stored as an integer (default: 30 minutes) that is used to calculate the end time and manage calendar availability. The **createDM** (created_at) field is a timestamp that records exactly when the appointment record was first created in the system, providing an audit trail for appointment creation. The **schedStatus** (schedule status) field manages the appointment's current state through an enumerated or varchar field that can contain values such as 'Pending', 'Confirmed', 'Completed', or 'Cancelled'.

Within the **schedStatus**, the **pending** state represents appointments that have been requested but not yet approved or confirmed by clinic staff. When an appointment is in pending status, the **notifSent** boolean field tracks whether a notification has been sent to inform the patient that their request is being reviewed. The **completedStatus** field may contain additional information about the completion state if the appointment moves directly from pending to completed without confirmation. The **confirmed** state indicates that the appointment has been approved and scheduled. In this state, **notifSent** tracks whether confirmation notifications have been delivered to the patient. The **rescheduleRemark** field stores text comments explaining why an appointment was rescheduled, if applicable. The **cancelRemark** field contains text explaining the reason for cancellation, which is important for understanding cancellation patterns and patient needs.

The **completed** state indicates that the appointment has been finished. The **notifSent** field in this state tracks whether completion notifications have been sent. The **completedID** may reference related records or documentation associated with the completed appointment. The **cancelled** state represents appointments that have been cancelled before completion. The **notifSent** field ensures cancellation notifications are delivered, and the **cancelRemark** field documents the reason for cancellation, which may include patient requests, clinic-initiated cancellations, or emergency situations.

Additional appointment fields include **reasonForVisit**, a varchar(255) field where patients can describe their primary concern or reason for scheduling the appointment, helping staff prepare appropriately. The **notes** field is a text field that can contain clinical notes, administrative notes, or special instructions related to the appointment. The **isNewPatient** field is a boolean flag that indicates whether this is the patient's first visit to the clinic, which may trigger different workflows or documentation requirements. The **feedback** section within appointments allows patients to provide post-appointment evaluations. The **rating** field stores a tinyint(4) value representing a 1-5 star rating, where 1 indicates poor satisfaction and 5 indicates excellent satisfaction. The **patientFeedback** field is a text field containing written comments about the patient's experience. The **feedbackComment** field may contain additional detailed feedback. The **ratedAt** timestamp records when the patient submitted their rating, and **feedbackSubmittedAt** provides a separate timestamp for when the complete feedback was submitted, enabling analysis of feedback timing and response rates.

Patient records represent a comprehensive collection of medical and dental information that patients can access. The **patientNumber** field is a unique varchar(255) identifier that serves as the patient's official record number, often formatted in a specific pattern (e.g., "PAT-2024-001") for easy reference and identification in physical records. The **personalInfo** section contains demographic data essential for patient identification and care planning. The **homeAddress** field stores the patient's residential address as a varchar(255), which may be used for billing, emergency contact, or home visit purposes. The **dateOfBirth** field is a date type that stores the patient's birth date, used for age calculation and demographic categorization. The **age** field is an integer that may be calculated from the date of birth or stored directly, representing the patient's current age in years. The **sex** field is a varchar(255) that stores the patient's biological sex, which is important for treatment considerations and medical history documentation. The **nickname** field allows storage of preferred names or aliases the patient may use. The **religion** field stores religious affiliation, which may be relevant for treatment planning or scheduling considerations. The **occupation** field documents the patient's profession, which may be relevant for understanding lifestyle factors affecting dental health.

The **contact** field stores the primary contact number as a varchar(255), separate from the account contact information, allowing for multiple contact methods. The **guardianInfo** section is critical for minor patients or patients requiring guardianship. The **guardianName** field stores the full name of the legal guardian as a varchar(255). The **guardianContact** field provides the guardian's phone number or email for emergency situations or consent requirements. The **guardianOccupation** field documents the guardian's profession, which may be relevant for billing or communication purposes.

The **medicalHistory** field is a text field that stores comprehensive general health information, including past illnesses, surgeries, chronic conditions, and other medical events that may impact dental treatment. The **medications** section tracks pharmaceutical information. The **currentMedications** field is a text field listing all medications the patient is currently taking, including dosages and frequencies when available, which is critical for identifying potential drug interactions or treatment contraindications.

The **allergies** section manages allergy information to prevent adverse reactions during treatment. The **allergies** field is a text field containing a general description of known allergies. The **allergiesDetail** field is a JSON field that stores structured allergy information, allowing for detailed breakdowns including allergen types (medications, foods, materials), severity levels (mild, moderate, severe), reaction descriptions, and management protocols. This JSON structure enables complex allergy data to be stored in a queryable format while maintaining flexibility for various allergy types.

The **healthQuestions** field is a JSON field that stores responses to health screening questionnaires, allowing for structured storage of yes/no questions, multiple choice responses, and detailed health assessments. This JSON format enables the system to store comprehensive health information while maintaining the ability to query specific health conditions or risk factors. The **womenHealth** section contains gender-specific health information. The **isPregnant** field is a boolean that indicates pregnancy status, critical for treatment planning as certain procedures and medications are contraindicated during pregnancy. The **isNursing** field is a boolean indicating if the patient is currently breastfeeding, which affects medication and treatment decisions. The **takesBirthControl** field is a boolean that may be relevant for certain medical interactions or treatment considerations.

The **physicianInfo** section maintains information about the patient's primary care physician for coordination of care. The **physicianName** field stores the doctor's full name as a varchar(255). The **physicianSpecialty** field documents the physician's medical specialty. The **physicianOfficeAddress** field stores the complete office address for referral or communication purposes. The **physicianContact** field provides phone or email contact information for the physician's office.

The **dentalHistory** section tracks previous dental care experiences. The **previousDentist** field stores the name of the patient's former dentist as a varchar(255). The **lastDentalVisit** field is a date type recording when the patient last received dental care, which helps assess oral health maintenance patterns. The **treatmentDone** field is a text field describing previous dental treatments, procedures, or interventions the patient has received.

The **clinicalInfo** section contains current clinical assessment information. The **chiefComplaint** field is a text field where the patient's primary concern or reason for the current visit is documented. The **diagnosis** field stores the clinical diagnosis determined by the dental professional, documented as text that may include ICD codes or standard dental terminology. The **treatmentPlan** field is a text field outlining the proposed course of treatment, including procedures, timelines, and expected outcomes.

The **notes** field is a text field for general notes that don't fit into other specific categories, allowing for flexible documentation of additional information. The **patientHistories** section contains detailed records of each patient visit. Each history entry includes a **visitDate** field (date type) recording when the visit occurred. The **healthStatus** field documents the patient's general health condition at the time of the visit. The **allergies** field within histories may contain visit-specific allergy information or updates. The **procedurePerformed** field is a text field describing all procedures completed during the visit. The **materialsUsed** field documents dental materials, restorations, or devices used during treatment. The **anesthesiaUsed** field records the type and amount of anesthesia administered. The **complications** field is a text field documenting any complications encountered during or after the procedure. The **postOperativeInstructions** field contains text instructions given to the patient for post-treatment care. The **followUpNotes** field documents recommendations for follow-up care or future appointments.

The **progressNotes** section tracks ongoing treatment progress over time. The **noteDate** field is a date type recording when the progress note was created. The **progressDescription** field is a text field describing the patient's progress, treatment response, and current condition. The **treatmentResponse** field documents how the patient is responding to treatment, including positive outcomes or concerns. The **amountPaid** field is a decimal(10,2) storing the payment amount for the visit or treatment. The **balance** field is a decimal(10,2) tracking any remaining balance owed by the patient. The **conforme** field is a varchar(255) that may store patient signatures, consent confirmations, or acknowledgment of treatment. The **nextSteps** field is a text field outlining recommended next actions in the treatment plan. The **otherNotes** field allows for additional documentation. The **status** field is a varchar(255) that may contain values like 'ongoing', 'completed', or 'followup_needed', indicating the current state of the treatment plan.

The feedback system enables patients to provide ratings and comments about their experience with completed appointments. The **feedbackID** serves as the primary key uniquely identifying each feedback entry in the system, typically implemented as a bigint(20) unsigned. The **ratingApp** field stores the patient's rating of the application interface and user experience, typically as a tinyint(1-5) representing a star rating where 1 indicates poor user experience and 5 indicates excellent user experience. This rating helps developers and administrators understand how patients perceive the system's usability and identify areas for interface improvements. The **ratingClinic** field stores the patient's rating of the clinic's service quality, also typically as a tinyint(1-5), where 1 indicates poor service and 5 indicates excellent service. This rating provides direct feedback about the quality of care, staff professionalism, facility cleanliness, and overall patient experience. The **date** field is a timestamp or date type that records exactly when the feedback was submitted, enabling temporal analysis of patient satisfaction trends and correlation with specific events, services, or time periods. This feedback data is stored within the appointments table, creating a direct relationship between completed appointments and patient satisfaction metrics, allowing for analysis of which services, time periods, or staff members receive the highest ratings.

The notification system provides real-time alerts to patients about important events and updates. The **notifID** (notification ID) is the primary key uniquely identifying each notification, implemented as a bigint(20) unsigned. The **type** field is a varchar(255) that categorizes the notification, with predefined types such as 'appointment_confirmed' for appointment confirmations, 'appointment_reminder' for reminder notifications sent 24 hours or 3 hours before appointments, 'appointment_rescheduled' for rescheduling notifications, 'appointment_cancelled' for cancellation alerts, 'record_updated' for notifications when patient records are modified, 'announcement' for general clinic announcements, and 'general' for other system notifications. This type classification enables the system to apply appropriate styling, routing, and processing logic based on the notification category.

The **title** field is a varchar(255) that provides a brief, descriptive heading for the notification, such as "Appointment Confirmed" or "Record Updated", allowing patients to quickly identify the notification's purpose. The **message** field is a text field containing the full notification content, which may include appointment details, instructions, or important information the patient needs to know. The **icon** field is a varchar(255) that stores a CSS class name or icon identifier used for visual representation, enabling the user interface to display appropriate icons (such as calendar icons for appointments, bell icons for reminders, or document icons for records) that help patients quickly identify notification types.

The **data** field is a JSON field that stores additional structured information related to the notification. This JSON structure may contain appointment IDs for appointment-related notifications, record IDs for record update notifications, URLs for direct links to relevant pages, or any other contextual data needed for notification processing or user interaction. The JSON format allows for flexible storage of varying data structures while maintaining queryability. The **isRead** field is a boolean that defaults to false, tracking whether the patient has viewed the notification. When a patient opens or views a notification, this field is set to true, enabling the system to display unread notification counts and filter read versus unread notifications. The **readAt** field is a timestamp that records exactly when the notification was read, providing analytics data about notification engagement timing and helping identify optimal notification delivery times. The **createDM** (created_at) field is a timestamp recording when the notification was generated, enabling chronological sorting and analysis of notification patterns.

The conversation logs system facilitates communication between patients and clinic staff or administrators. The **conversationID** is the primary key uniquely identifying each conversation thread, implemented as a bigint(20) unsigned. The **patientID** field is a foreign key linking to the users table, specifically identifying the patient participating in the conversation. The **staffID** field is a foreign key that may be null, linking to the users table to identify which staff member is assigned to or participating in the conversation. The **adminID** field is also a foreign key that may be null, linking to administrators who may join or oversee the conversation. This structure allows for conversations between patients and staff, patients and administrators, or multi-party conversations involving all three user types.

The **status** field is an enum or varchar that can contain values such as 'active' for ongoing conversations requiring responses, 'inactive' for conversations that have been paused or abandoned, 'resolved' for conversations where the patient's issue has been addressed, or 'closed' for conversations that have been formally concluded. This status tracking helps staff prioritize conversations and manage their workload effectively. The **lastMessageAt** field is a timestamp that records when the most recent message was sent in the conversation, enabling sorting by activity and identification of conversations requiring attention.

Within the **messages** collection, each message contains detailed information. The **messageID** is the primary key for each individual message, implemented as a bigint(20) unsigned. The **senderID** is a foreign key linking to the users table, identifying who sent the message. The **senderType** field is an enum that can be 'patient', 'staff', or 'admin', allowing the system to apply different styling, permissions, and processing logic based on who sent the message. The **message** field is a text field containing the actual message content. The **attachments** field is a JSON field that stores file attachment information, including file names, paths, file types, and file sizes, enabling patients and staff to share documents, images, or other files during conversations. The **isRead** field is a boolean tracking whether the message has been read by the recipient, with separate tracking for patient messages (read by staff) and staff messages (read by patients). The **readAt** field is a timestamp recording when the message was read, providing analytics about response times and communication patterns. The **dateTime** field is a timestamp recording exactly when the message was sent, enabling chronological message ordering and conversation timeline reconstruction.

Security is maintained through **refreshToken** mechanisms that manage secure session authentication. Refresh tokens are typically stored in the database or session storage and are used to maintain authenticated user sessions without requiring repeated password entry. These tokens are cryptographically secure strings that are generated upon successful login and are used to obtain new access tokens when the current session token expires. The refresh token system ensures that patient data remains protected by requiring valid authentication tokens for all data access operations, and access is properly controlled throughout the patient's interaction with the system. Refresh tokens typically have longer expiration times than access tokens, allowing users to remain logged in across browser sessions while maintaining security through token rotation and expiration mechanisms.

---

## 2. Staff User (role_id: 2)

### Description
The Staff user represents clinic employees who assist in managing patient care, appointments, and day-to-day operations. Staff members have extended access to view and manage all patient data, appointments, and records across the system. They can also manage content, respond to patient inquiries, and handle appointment requests. Access is controlled through granular permission settings that can be configured by administrators.

### Data Structure Breakdown

```
Staff
├── staffID
├── credentials
│   ├── username
│   ├── password
│   └── roleType (Staff)
├── accountInfo
│   ├── username
│   ├── fullName
│   ├── email
│   ├── contactNo
│   ├── birthday
│   └── bio
├── profile
│   ├── profilePicture
│   └── mustChangePassword
├── accessControl
│   ├── accessDashboard
│   ├── accessAppointments
│   ├── accessUserManagement
│   ├── accessContentManagement
│   ├── accessPostProcedural
│   ├── accessToothTalk
│   ├── accessLiveChat
│   ├── accessNotifications
│   ├── accessProfile
│   ├── canCreateAppointments
│   ├── canEditAppointments
│   ├── canDeleteAppointments
│   ├── canUpdateAppointmentStatus
│   ├── canViewAllAppointments
│   ├── canCreateUsers
│   ├── canEditUsers
│   ├── canDeleteUsers
│   ├── canCreateAnnouncements
│   ├── canEditAnnouncements
│   ├── canDeleteAnnouncements
│   ├── canManageAnnouncements
│   ├── canDeleteArchives
│   ├── canManageServices
│   ├── canManageEvents
│   ├── canSendEmails
│   ├── canManageMails
│   ├── canViewPatientRecords
│   ├── canCreatePatientRecords
│   ├── canEditPatientRecords
│   ├── canDeletePatientRecords
│   ├── canRespondToChat
│   ├── canAttachFiles
│   └── canExportData
├── Appointment (All Patients)
│   ├── appointmentID
│   ├── patientID
│   ├── appointmentType
│   │   ├── serviceID
│   │   ├── description
│   │   ├── price
│   │   └── duration
│   ├── schedule
│   │   ├── dateTime
│   │   ├── durationMinutes
│   │   ├── createDM
│   │   └── schedStatus
│   │       ├── pending
│   │       │   ├── notifSent
│   │       │   └── completedStatus
│   │       ├── confirmed
│   │       │   ├── notifSent
│   │       │   ├── rescheduleRemark
│   │       │   └── cancelRemark
│   │       ├── completed
│   │       │   ├── notifSent
│   │       │   └── completedID
│   │       └── cancelled
│   │           ├── notifSent
│   │           └── cancelRemark
│   ├── reasonForVisit
│   ├── notes
│   ├── isNewPatient
│   └── feedback
│       ├── rating
│       ├── patientFeedback
│       ├── feedbackComment
│       ├── ratedAt
│       └── feedbackSubmittedAt
├── AppointmentRequest
│   ├── requestID
│   ├── patientID
│   ├── serviceID
│   ├── otherConcern
│   ├── existingAppointmentID
│   ├── requestType
│   ├── requestedDateTime
│   ├── requestedEndDateTime
│   ├── durationMinutes
│   ├── reason
│   ├── notes
│   ├── status
│   ├── reviewedBy
│   ├── reviewNotes
│   └── reviewedAt
├── BlockedTime
│   ├── blockedTimeID
│   ├── title
│   ├── startDateTime
│   ├── endDateTime
│   ├── durationMinutes
│   └── notes
├── PatientRecord (All Patients)
│   ├── recordID
│   ├── userID
│   ├── appointmentID
│   ├── patientNumber
│   ├── personalInfo
│   │   ├── homeAddress
│   │   ├── dateOfBirth
│   │   ├── age
│   │   ├── sex
│   │   ├── nickname
│   │   ├── religion
│   │   └── occupation
│   ├── contact
│   ├── guardianInfo
│   │   ├── guardianName
│   │   ├── guardianContact
│   │   └── guardianOccupation
│   ├── medicalHistory
│   ├── medications
│   │   └── currentMedications
│   ├── allergies
│   │   └── allergiesDetail
│   ├── healthQuestions
│   ├── womenHealth
│   │   ├── isPregnant
│   │   ├── isNursing
│   │   └── takesBirthControl
│   ├── physicianInfo
│   │   ├── physicianName
│   │   ├── physicianSpecialty
│   │   ├── physicianOfficeAddress
│   │   └── physicianContact
│   ├── dentalHistory
│   │   ├── previousDentist
│   │   ├── lastDentalVisit
│   │   └── treatmentDone
│   ├── clinicalInfo
│   │   ├── chiefComplaint
│   │   ├── diagnosis
│   │   └── treatmentPlan
│   ├── notes
│   ├── sentToPatient
│   ├── sentAt
│   ├── patientHistories
│   │   ├── historyID
│   │   ├── visitDate
│   │   ├── healthStatus
│   │   ├── allergies
│   │   ├── procedurePerformed
│   │   ├── materialsUsed
│   │   ├── anesthesiaUsed
│   │   ├── complications
│   │   ├── postOperativeInstructions
│   │   ├── followUpNotes
│   │   ├── sentToPatient
│   │   ├── sentAt
│   │   ├── createdByUserID
│   │   └── createdByRole
│   └── progressNotes
│       ├── noteID
│       ├── noteDate
│       ├── progressDescription
│       ├── treatmentResponse
│       ├── amountPaid
│       ├── balance
│       ├── conforme
│       ├── nextSteps
│       ├── otherNotes
│       ├── status
│       ├── createdByUserID
│       └── createdByRole
├── Feedback (All Patients)
│   ├── feedbackID
│   ├── appointmentID
│   ├── patientID
│   ├── ratingApp
│   ├── ratingClinic
│   ├── patientFeedback
│   ├── feedbackComment
│   └── date
├── Notification
│   ├── notifID
│   ├── userID
│   ├── type
│   ├── title
│   ├── message
│   ├── icon
│   ├── data
│   ├── isRead
│   ├── readAt
│   └── createDM
├── ConversationLogs
│   ├── conversationID
│   ├── patientID
│   ├── staffID
│   ├── adminID
│   ├── status
│   ├── lastMessageAt
│   └── messages
│       ├── messageID
│       ├── senderID
│       ├── senderType
│       ├── message
│       ├── attachments
│       ├── isRead
│       ├── readAt
│       └── dateTime
├── Content
│   ├── service
│   │   ├── serviceID
│   │   ├── serviceName
│   │   ├── description
│   │   ├── price
│   │   ├── priceNotes
│   │   ├── defaultDurationMinutes
│   │   ├── iconClass
│   │   ├── isActive
│   │   ├── createDM
│   │   └── updatedAt
│   ├── announcement
│   │   ├── announcementID
│   │   ├── title
│   │   ├── subheading
│   │   ├── content
│   │   ├── imagePath
│   │   ├── dateStart
│   │   ├── dateEnd
│   │   ├── timeStart
│   │   ├── timeEnd
│   │   ├── isWholeDay
│   │   ├── tickerText
│   │   ├── showTicker
│   │   ├── isActive
│   │   ├── createDM
│   │   └── updatedAt
│   ├── event
│   │   ├── eventID
│   │   ├── title
│   │   ├── description
│   │   ├── eventDate
│   │   ├── eventTime
│   │   ├── location
│   │   ├── eventType
│   │   ├── isActive
│   │   ├── imagePath
│   │   ├── createDM
│   │   └── updatedAt
│   └── mailTemplate
│       ├── templateID
│       ├── type
│       ├── subject
│       ├── content
│       ├── createDM
│       └── updatedAt
└── refreshToken
```

### Detailed Component Descriptions

The Staff user's data structure begins with basic account information similar to patients, but with significantly expanded access capabilities. The **staffID** serves as the primary unique identifier for each staff member in the system, typically implemented as a bigint(20) unsigned auto-incrementing value that links to the users table where the staff member's account is stored. The **credentials** section contains critical authentication information, where the **username** field stores a unique login identifier that must be distinct across all users in the system, allowing staff to log in using their username. The **password** field stores a hashed version of the staff member's password using secure hashing algorithms such as bcrypt, ensuring that plain text passwords are never stored in the database. The **roleType** attribute is specifically set to "Staff" (corresponding to role_id: 2) and determines the access level and capabilities available to the user within the system, enabling staff-specific features and permissions.

The **accountInfo** section maintains comprehensive personal information about the staff member. The **username** field in accountInfo mirrors the credentials username and provides an alternative login identifier. The **fullName** field stores the staff member's complete legal name as a varchar(255), which is used for identification, official documentation, and display throughout the system. The **email** field stores the staff member's email address as a varchar(255), validated to ensure uniqueness, and serves as the primary contact method for system communications, password resets, and administrative notifications. The **contactNo** field stores the staff member's phone number as a varchar(255), enabling direct communication for urgent matters, shift scheduling, and emergency contact. The **birthday** field stores the staff member's date of birth as a date type, which may be used for age verification, demographic analysis, or employment records. The **bio** field is an optional text field that allows staff members to provide additional personal information or professional background.

The **profile** section manages user interface and security settings. The **profilePicture** field stores the file path to the staff member's profile image as a varchar(255), allowing staff to personalize their account with a profile photo that may be displayed in patient communications or internal directories. The **mustChangePassword** field is a boolean flag that defaults to true for new staff accounts, forcing staff members to change their password upon first login or when administrators require a password reset for security purposes. This field is automatically set to false once the staff member successfully changes their password, allowing normal login procedures to proceed.

A critical component of the staff system is the **accessControl** mechanism, which provides granular permission management that determines what features and actions each staff member can perform. This permission system is configured by administrators through the staff_access_controls table and controls access to various modules through boolean flags. The **accessDashboard** field is a boolean that determines whether the staff member can access the main dashboard interface, which provides overview statistics and quick access to common tasks. The **accessAppointments** field is a boolean controlling access to the appointment management module, allowing staff to view and manage appointments. The **accessUserManagement** field is a boolean that determines if the staff member can access user management features, though staff typically have limited user management capabilities compared to administrators. The **accessContentManagement** field is a boolean controlling access to content management features, enabling staff to manage services, announcements, and events. The **accessPostProcedural** field is a boolean that controls access to post-procedural care modules, allowing staff to manage follow-up care and post-treatment documentation. The **accessToothTalk** field is a boolean determining access to the ToothTalk chatbot management system, enabling staff to configure chatbot settings and FAQs. The **accessLiveChat** field is a boolean controlling access to the live chat system, allowing staff to respond to patient conversations. The **accessNotifications** field is a boolean that determines if the staff member can manage notifications. The **accessProfile** field is a boolean controlling access to profile management features.

The system also controls specific action permissions through additional boolean fields. The **canCreateAppointments** field determines if the staff member can create new appointments for patients. The **canEditAppointments** field controls the ability to modify existing appointments. The **canDeleteAppointments** field determines if appointments can be permanently removed from the system. The **canUpdateAppointmentStatus** field controls the ability to change appointment statuses (e.g., from pending to confirmed). The **canViewAllAppointments** field determines if the staff member can view appointments for all patients, not just assigned ones. The **canCreateUsers** field controls the ability to create new user accounts. The **canEditUsers** field determines if user account information can be modified. The **canDeleteUsers** field controls the ability to remove user accounts from the system. The **canCreateAnnouncements** field determines if the staff member can create new announcements. The **canEditAnnouncements** field controls the ability to modify existing announcements. The **canDeleteAnnouncements** field determines if announcements can be removed. The **canManageAnnouncements** field is a broader permission that may encompass multiple announcement-related actions. The **canDeleteArchives** field controls the ability to permanently delete archived content. The **canManageServices** field determines if the staff member can create, edit, or delete service offerings. The **canManageEvents** field controls the ability to manage clinic events. The **canSendEmails** field determines if the staff member can send emails through the system. The **canManageMails** field controls broader email management capabilities. The **canViewPatientRecords** field determines if the staff member can access patient medical records. The **canCreatePatientRecords** field controls the ability to create new patient record entries. The **canEditPatientRecords** field determines if existing patient records can be modified. The **canDeletePatientRecords** field controls the ability to remove patient record entries. The **canRespondToChat** field determines if the staff member can respond to patient chat messages. The **canAttachFiles** field controls the ability to attach files to chat messages or records. The **canExportData** field determines if the staff member can export data from the system, which may be restricted for data privacy reasons. This granular control allows administrators to customize each staff member's capabilities based on their role and responsibilities within the clinic, ensuring that staff members only have access to features necessary for their job functions.

Staff members have comprehensive access to appointment management for all patients in the system. They can view, create, edit, and manage appointments across all patient accounts, with each appointment containing complete information including unique identifiers, patient links, service details with pricing and duration, full scheduling information with status tracking through the entire appointment lifecycle, patient reasons for visits, clinical or administrative notes, new patient flags, and patient feedback and ratings. This broad access enables staff to efficiently manage the clinic's appointment calendar and ensure proper scheduling coordination.

The **AppointmentRequest** management system allows staff to review and process requests submitted by patients. The **requestID** is the primary key uniquely identifying each appointment request, implemented as a bigint(20) unsigned. The **patientID** field is a foreign key linking to the users table, identifying which patient submitted the request. The **serviceID** field is a foreign key linking to the services table, identifying the specific dental service the patient is requesting. The **otherConcern** field is a text field that allows patients to specify concerns or services not listed in the standard service catalog. The **existingAppointmentID** field is a foreign key that may be null, linking to an existing appointment if this request is for rescheduling or modification. The **requestType** field is an enum or varchar that can contain values such as 'new_booking' for new appointment requests, 'reschedule' for requests to change existing appointment times, or 'walk_in' for same-day appointment requests. The **requestedDateTime** field is a datetime type storing the patient's preferred appointment start date and time. The **requestedEndDateTime** field is a datetime type storing the patient's preferred appointment end time, which may be calculated from requestedDateTime and durationMinutes. The **durationMinutes** field is an integer specifying how long the patient expects the appointment to last. The **reason** field is a text field containing the patient's stated reason for the appointment request. The **notes** field is a text field for additional notes or special requests from the patient. The **status** field is an enum that can contain values such as 'pending' for requests awaiting review, 'approved' for requests that have been accepted and converted to appointments, or 'denied' for requests that have been rejected. The **reviewedBy** field is a foreign key linking to the users table, identifying which staff member reviewed and processed the request. The **reviewNotes** field is a text field containing staff comments explaining the approval or denial decision. The **reviewedAt** field is a timestamp recording when the request was reviewed, creating an audit trail for appointment request processing.

Staff can manage **BlockedTime** slots to prevent appointment scheduling during specific periods. The **blockedTimeID** is the primary key uniquely identifying each blocked time entry, implemented as a bigint(20) unsigned. The **title** field is a varchar(255) that provides a descriptive title explaining why the time is blocked, such as "Staff Meeting", "Clinic Closure", or "Equipment Maintenance", helping staff understand the reason for the block. The **startDateTime** field is a datetime type that defines when the blocked period begins, preventing any appointments from being scheduled at or after this time. The **endDateTime** field is a datetime type that defines when the blocked period ends, allowing appointments to be scheduled again after this time. The **durationMinutes** field is an integer that calculates or stores the duration of the blocked period in minutes, which may be automatically calculated from startDateTime and endDateTime. The **notes** field is a text field for optional additional context about the blocked time, such as specific staff members affected, alternative scheduling options, or special instructions. This feature is essential for managing clinic closures, staff unavailability, special events, equipment maintenance periods, or any other situations that require blocking appointment slots to prevent scheduling conflicts.

Patient record management provides staff with full access to all patient records across the system. Staff can view, create, edit, and update comprehensive patient information including personal details, medical history, current medications, allergies, clinical information, dental history, and treatment plans. Staff can add detailed patient histories that document each visit with information about procedures performed, materials used, anesthesia administered, complications encountered, and follow-up instructions. Progress notes can be created to track treatment progress, patient responses, payment information including amounts paid and balances, and next steps in treatment. The system tracks who created or modified each record, maintaining an audit trail with user IDs and roles. Staff also have the ability to send records to patients when appropriate, facilitating patient communication and record sharing.

The feedback management system allows staff to view all patient feedback across the system. Each feedback entry is linked to a specific appointment and patient, containing ratings for both the application and clinic service, along with detailed feedback comments and the date when feedback was submitted. This comprehensive view enables staff to monitor patient satisfaction and identify areas for improvement across all clinic services.

Staff receive notifications related to their work responsibilities, including alerts for appointment requests, patient inquiries, system updates, and other important events. The notification system tracks read status and timestamps, allowing staff to manage their notifications effectively and stay informed about tasks requiring attention.

The conversation logs system enables staff to participate in real-time communication with patients. Staff can view and respond to patient conversations, attach files to messages for sharing documents or images, and track conversation status and message read status. The system supports managing multiple conversations simultaneously, allowing staff to efficiently handle patient inquiries and provide support through the chat interface.

**Content** management capabilities allow staff to manage various types of content that appear throughout the system. Within the Content section, the **service** subsection enables staff to manage dental services offered by the clinic. The **serviceID** is the primary key uniquely identifying each service, implemented as a bigint(20) unsigned. The **serviceName** field is a varchar(255) storing the name of the service, such as "Teeth Cleaning" or "Root Canal Treatment". The **description** field is a text field containing detailed information about what the service entails, helping patients understand what to expect. The **price** field is a decimal(10,2) storing the cost of the service in the system's currency. The **priceNotes** field is a text field for additional pricing information, such as "Price may vary based on complexity" or "Insurance may cover partial cost". The **defaultDurationMinutes** field is an integer specifying the typical duration of the service in minutes, used for automatic scheduling calculations. The **iconClass** field is a varchar(255) storing CSS class names for visual representation of the service in the user interface. The **isActive** field is a boolean that determines whether the service is currently available for booking, allowing staff to temporarily disable services without deleting them. The **createDM** (created_at) and **updatedAt** fields are timestamps tracking when the service was created and last modified.

The **announcement** subsection allows staff to create and manage clinic announcements. The **announcementID** is the primary key uniquely identifying each announcement, implemented as a bigint(20) unsigned. The **title** field is a varchar(255) providing a headline for the announcement. The **subheading** field is a varchar(255) providing a secondary heading or tagline. The **content** field is a text field containing the full announcement text, which may include HTML formatting for rich text display. The **imagePath** field is a varchar(255) storing the file path to an associated image for the announcement. The **dateStart** and **dateEnd** fields are date types defining when the announcement should be displayed, allowing for scheduled announcements. The **timeStart** and **timeEnd** fields are time types providing more granular control over announcement display times. The **isWholeDay** field is a boolean indicating if the announcement should be displayed for the entire day regardless of time settings. The **tickerText** field is a varchar(255) storing text for a scrolling ticker or banner display. The **showTicker** field is a boolean determining whether the ticker should be displayed. The **isActive** field is a boolean controlling whether the announcement is currently visible to patients. The **createDM** and **updatedAt** fields track creation and modification timestamps.

The **event** subsection enables staff to manage clinic events. The **eventID** is the primary key uniquely identifying each event, implemented as a bigint(20) unsigned. The **title** field is a varchar(255) storing the event name. The **description** field is a text field containing event details. The **eventDate** field is a date type storing when the event occurs. The **eventTime** field is a time type storing the event start time. The **location** field is a varchar(255) storing where the event takes place. The **eventType** field is a varchar(255) categorizing the event, such as "Workshop", "Open House", or "Health Fair". The **isActive** field is a boolean determining if the event is currently visible. The **imagePath** field stores an associated image file path. The **createDM** and **updatedAt** fields track timestamps.

The **mailTemplate** subsection allows staff to configure email templates for automated communications. The **templateID** is the primary key uniquely identifying each template, implemented as a bigint(20) unsigned. The **type** field is an enum or varchar categorizing the template, such as 'appointment_confirmation', 'appointment_reminder', 'appointment_cancellation', 'appointment_reschedule', or 'follow_up'. The **subject** field is a varchar(255) storing the email subject line, which may include variables like {patient_name} or {appointment_date} for personalization. The **content** field is a text field containing the email body, which may include HTML formatting and template variables. The **createDM** and **updatedAt** fields track when templates were created and modified. This content management functionality enables staff to keep clinic information current and communicate effectively with patients through multiple channels.

---

## 3. Administrator User (role_id: 1)

### Description
The Administrator user represents the highest level of system access with complete control over all system features, user management, and system configuration. Administrators can manage all users (patients, staff, and other administrators), configure system settings, monitor system activity, manage chatbot functionality, and have full access to all data and features in the system. This role is designed for system oversight, security management, and comprehensive system administration.

### Data Structure Breakdown

```
Administrator
├── adminID
├── credentials
│   ├── username
│   ├── password
│   └── roleType (Administrator)
├── accountInfo
│   ├── username
│   ├── fullName
│   ├── email
│   ├── contactNo
│   ├── birthday
│   └── bio
├── profile
│   ├── profilePicture
│   └── mustChangePassword
├── UserManagement
│   ├── user
│   │   ├── userID
│   │   ├── name
│   │   ├── roleID
│   │   ├── username
│   │   ├── email
│   │   ├── emailVerifiedAt
│   │   ├── password
│   │   ├── profilePicture
│   │   ├── mustChangePassword
│   │   ├── rememberToken
│   │   ├── createDM
│   │   └── updatedAt
│   ├── userInfo
│   │   ├── infoID
│   │   ├── userID
│   │   ├── firstName
│   │   ├── middleName
│   │   ├── lastName
│   │   ├── phone
│   │   ├── address
│   │   ├── age
│   │   ├── gender
│   │   ├── birthday
│   │   ├── createDM
│   │   └── updatedAt
│   ├── role
│   │   ├── roleID
│   │   ├── role
│   │   ├── createdBy
│   │   ├── updatedBy
│   │   ├── createDM
│   │   └── updatedAt
│   └── staffAccessControl
│       ├── accessControlID
│       ├── staffID
│       ├── accessPermissions (all access control fields)
│       ├── createDM
│       └── updatedAt
├── Appointment (All Patients)
│   ├── appointmentID
│   ├── patientID
│   ├── appointmentType
│   │   ├── serviceID
│   │   ├── description
│   │   ├── price
│   │   └── duration
│   ├── schedule
│   │   ├── dateTime
│   │   ├── durationMinutes
│   │   ├── createDM
│   │   └── schedStatus
│   │       ├── pending
│   │       │   ├── notifSent
│   │       │   └── completedStatus
│   │       ├── confirmed
│   │       │   ├── notifSent
│   │       │   ├── rescheduleRemark
│   │       │   └── cancelRemark
│   │       ├── completed
│   │       │   ├── notifSent
│   │       │   └── completedID
│   │       └── cancelled
│   │           ├── notifSent
│   │           └── cancelRemark
│   ├── reasonForVisit
│   ├── notes
│   ├── isNewPatient
│   ├── feedback
│   │   ├── rating
│   │   ├── patientFeedback
│   │   ├── feedbackComment
│   │   ├── ratedAt
│   │   └── feedbackSubmittedAt
│   ├── rescheduledAt
│   ├── originalDateTime
│   ├── reminder24hSent
│   ├── reminder3hSent
│   ├── reminder24hSentAt
│   └── reminder3hSentAt
├── AppointmentRequest
│   ├── requestID
│   ├── patientID
│   ├── serviceID
│   ├── otherConcern
│   ├── existingAppointmentID
│   ├── requestType
│   ├── requestedDateTime
│   ├── requestedEndDateTime
│   ├── durationMinutes
│   ├── reason
│   ├── notes
│   ├── status
│   ├── reviewedBy
│   ├── reviewNotes
│   └── reviewedAt
├── BlockedTime
│   ├── blockedTimeID
│   ├── title
│   ├── startDateTime
│   ├── endDateTime
│   ├── durationMinutes
│   └── notes
├── PatientRecord (All Patients)
│   ├── recordID
│   ├── userID
│   ├── appointmentID
│   ├── patientNumber
│   ├── personalInfo
│   │   ├── homeAddress
│   │   ├── dateOfBirth
│   │   ├── age
│   │   ├── sex
│   │   ├── nickname
│   │   ├── religion
│   │   └── occupation
│   ├── contact
│   ├── guardianInfo
│   │   ├── guardianName
│   │   ├── guardianContact
│   │   └── guardianOccupation
│   ├── medicalHistory
│   ├── medications
│   │   └── currentMedications
│   ├── allergies
│   │   └── allergiesDetail
│   ├── healthQuestions
│   ├── womenHealth
│   │   ├── isPregnant
│   │   ├── isNursing
│   │   └── takesBirthControl
│   ├── physicianInfo
│   │   ├── physicianName
│   │   ├── physicianSpecialty
│   │   ├── physicianOfficeAddress
│   │   └── physicianContact
│   ├── dentalHistory
│   │   ├── previousDentist
│   │   ├── lastDentalVisit
│   │   └── treatmentDone
│   ├── clinicalInfo
│   │   ├── chiefComplaint
│   │   ├── diagnosis
│   │   └── treatmentPlan
│   ├── notes
│   ├── sentToPatient
│   ├── sentAt
│   ├── patientHistories
│   │   ├── historyID
│   │   ├── visitDate
│   │   ├── healthStatus
│   │   ├── allergies
│   │   ├── procedurePerformed
│   │   ├── materialsUsed
│   │   ├── anesthesiaUsed
│   │   ├── complications
│   │   ├── postOperativeInstructions
│   │   ├── followUpNotes
│   │   ├── sentToPatient
│   │   ├── sentAt
│   │   ├── createdByUserID
│   │   └── createdByRole
│   └── progressNotes
│       ├── noteID
│       ├── noteDate
│       ├── progressDescription
│       ├── treatmentResponse
│       ├── amountPaid
│       ├── balance
│       ├── conforme
│       ├── nextSteps
│       ├── otherNotes
│       ├── status
│       ├── createdByUserID
│       └── createdByRole
├── Feedback (All Patients)
│   ├── feedbackID
│   ├── appointmentID
│   ├── patientID
│   ├── ratingApp
│   ├── ratingClinic
│   ├── patientFeedback
│   ├── feedbackComment
│   └── date
├── Notification (All Users)
│   ├── notifID
│   ├── userID
│   ├── type
│   ├── title
│   ├── message
│   ├── icon
│   ├── data
│   ├── isRead
│   ├── readAt
│   └── createDM
├── ConversationLogs (All Conversations)
│   ├── conversationID
│   ├── patientID
│   ├── staffID
│   ├── adminID
│   ├── status
│   ├── lastMessageAt
│   └── messages
│       ├── messageID
│       ├── senderID
│       ├── senderType
│       ├── message
│       ├── attachments
│       ├── isRead
│       ├── readAt
│       └── dateTime
├── Content
│   ├── service
│   │   ├── serviceID
│   │   ├── serviceName
│   │   ├── description
│   │   ├── price
│   │   ├── priceNotes
│   │   ├── defaultDurationMinutes
│   │   ├── iconClass
│   │   ├── isActive
│   │   ├── createDM
│   │   └── updatedAt
│   ├── announcement
│   │   ├── announcementID
│   │   ├── title
│   │   ├── subheading
│   │   ├── content
│   │   ├── imagePath
│   │   ├── dateStart
│   │   ├── dateEnd
│   │   ├── timeStart
│   │   ├── timeEnd
│   │   ├── isWholeDay
│   │   ├── tickerText
│   │   ├── showTicker
│   │   ├── isActive
│   │   ├── createDM
│   │   └── updatedAt
│   ├── announcementArchive
│   │   ├── archiveID
│   │   ├── announcementID
│   │   ├── title
│   │   ├── subheading
│   │   ├── content
│   │   ├── imagePath
│   │   ├── dateStart
│   │   ├── dateEnd
│   │   ├── timeStart
│   │   ├── timeEnd
│   │   ├── isWholeDay
│   │   ├── tickerText
│   │   ├── showTicker
│   │   ├── isActive
│   │   ├── archivedBy
│   │   ├── archivedAt
│   │   ├── createDM
│   │   └── updatedAt
│   ├── event
│   │   ├── eventID
│   │   ├── title
│   │   ├── description
│   │   ├── eventDate
│   │   ├── eventTime
│   │   ├── location
│   │   ├── eventType
│   │   ├── isActive
│   │   ├── imagePath
│   │   ├── createDM
│   │   └── updatedAt
│   └── mailTemplate
│       ├── templateID
│       ├── type
│       ├── subject
│       ├── content
│       ├── createDM
│       └── updatedAt
├── ChatbotManagement
│   ├── chatbotSetting
│   │   ├── settingID
│   │   ├── enabled
│   │   ├── isOnline
│   │   ├── censorshipEnabled
│   │   ├── welcomeMessage
│   │   ├── quickIntents
│   │   ├── createDM
│   │   └── updatedAt
│   ├── chatbotFaq
│   │   ├── faqID
│   │   ├── question
│   │   ├── answer
│   │   ├── isActive
│   │   ├── order
│   │   ├── createDM
│   │   └── updatedAt
│   └── chatCensoredWord
│       ├── wordID
│       ├── word
│       ├── createdByID
│       ├── createdByType
│       ├── createDM
│       └── updatedAt
├── SystemManagement
│   ├── activityLog
│   │   ├── logID
│   │   ├── userID
│   │   ├── action
│   │   ├── module
│   │   ├── description
│   │   ├── recordID
│   │   ├── recordType
│   │   ├── oldValues
│   │   ├── newValues
│   │   ├── ipAddress
│   │   ├── userAgent
│   │   ├── createDM
│   │   └── updatedAt
│   ├── fullyBookedDay
│   │   ├── dayID
│   │   ├── date
│   │   ├── createDM
│   │   └── updatedAt
│   └── passwordResetToken
│       ├── email
│       ├── token
│       ├── createDM
│       ├── updatedAt
│       └── expiresAt
└── refreshToken
```

### Detailed Component Descriptions

The Administrator user's data structure includes the same foundational account information as other user types, but with the most comprehensive access level in the system. The **adminID** serves as the primary unique identifier for each administrator in the system, typically implemented as a bigint(20) unsigned auto-incrementing value that links to the users table where the administrator's account is stored. The **credentials** section contains critical authentication information, where the **username** field stores a unique login identifier that must be distinct across all users in the system, allowing administrators to log in using their username. The **password** field stores a hashed version of the administrator's password using secure hashing algorithms such as bcrypt, ensuring that plain text passwords are never stored in the database. The **roleType** attribute is specifically set to "Administrator" (corresponding to role_id: 1) and determines the highest access level and capabilities available in the system, enabling all administrative features and unrestricted access to all data and system functions.

The **accountInfo** section maintains comprehensive personal information about the administrator. The **username** field in accountInfo mirrors the credentials username and provides an alternative login identifier. The **fullName** field stores the administrator's complete legal name as a varchar(255), which is used for identification, official documentation, and display throughout the system. The **email** field stores the administrator's email address as a varchar(255), validated to ensure uniqueness, and serves as the primary contact method for system communications, security alerts, and administrative notifications. The **contactNo** field stores the administrator's phone number as a varchar(255), enabling direct communication for urgent matters, security incidents, or critical system issues. The **birthday** field stores the administrator's date of birth as a date type, which may be used for age verification, demographic analysis, or employment records. The **bio** field is an optional text field that allows administrators to provide additional personal information or professional background.

The **profile** section manages user interface and security settings. The **profilePicture** field stores the file path to the administrator's profile image as a varchar(255), allowing administrators to personalize their account with a profile photo. The **mustChangePassword** field is a boolean flag that defaults to true for new administrator accounts, forcing administrators to change their password upon first login or when security policies require a password reset. This field is automatically set to false once the administrator successfully changes their password, allowing normal login procedures to proceed.

The **UserManagement** system represents one of the most critical administrative functions, providing complete control over all users in the system including patients, staff members, and other administrators. Within UserManagement, the **user** subsection contains core user account information. The **userID** is the primary key uniquely identifying each user in the system, implemented as a bigint(20) unsigned. The **name** field is a varchar(255) storing the user's display name. The **roleID** field is a foreign key linking to the roles table, determining the user's role (1 for Administrator, 2 for Staff, 3 for Patient). The **username** field is a varchar(255) storing the unique login identifier. The **email** field is a varchar(255) storing the user's email address, validated for uniqueness. The **emailVerifiedAt** field is a timestamp that records when the user's email address was verified, which may be null for unverified accounts. The **password** field stores the hashed password. The **profilePicture** field stores the file path to the user's profile image. The **mustChangePassword** field is a boolean flag forcing password changes. The **rememberToken** field is a varchar(100) storing the "remember me" token for persistent login sessions. The **createDM** (created_at) and **updatedAt** fields track when the user account was created and last modified.

The **userInfo** subsection contains extended personal information for users. The **infoID** is the primary key uniquely identifying each user info record, implemented as a bigint(20) unsigned. The **userID** field is a foreign key linking to the users table. The **firstName** field is a varchar(255) storing the user's first name. The **middleName** field is a varchar(255) storing the user's middle name, which may be null. The **lastName** field is a varchar(255) storing the user's last name. The **phone** field is a varchar(255) storing the user's phone number. The **address** field is a text field storing the user's complete address. The **age** field is an integer storing the user's age. The **gender** field is a varchar(255) storing the user's gender. The **birthday** field is a date type storing the user's date of birth. The **createDM** and **updatedAt** fields track creation and modification timestamps.

The **role** subsection enables administrators to manage system roles. The **roleID** is the primary key uniquely identifying each role, implemented as a bigint(20) unsigned. The **role** field is a varchar(255) storing the role name, such as "Administrator", "Staff", or "Patient". The **createdBy** field is a foreign key linking to the users table, identifying which administrator created the role. The **updatedBy** field is a foreign key identifying which administrator last updated the role. The **createDM** and **updatedAt** fields track when roles were created and modified.

The **staffAccessControl** subsection allows administrators to configure granular permissions for staff members. The **accessControlID** is the primary key uniquely identifying each access control record, implemented as a bigint(20) unsigned. The **staffID** field is a foreign key linking to the users table, identifying which staff member these permissions apply to. The **accessPermissions** field represents all the access control boolean fields (accessDashboard, accessAppointments, canCreateAppointments, etc.) that determine what features and actions each staff member can perform. Administrators can set individual access controls for each staff member, determining module access, appointment permissions, content management permissions, and various other capabilities. The **createDM** and **updatedAt** fields track when permissions were created and last modified, maintaining a complete audit trail of permission changes. This permission system ensures that staff members only have access to features necessary for their job functions while maintaining security and data privacy.

**Appointment** management for administrators includes all features available to staff members, with additional administrative capabilities that provide deeper insight and control. Administrators have access to all appointment fields available to staff, plus additional tracking fields. The **rescheduledAt** field is a timestamp that records when an appointment was rescheduled, enabling administrators to track rescheduling patterns and frequency. The **originalDateTime** field is a datetime type that stores the appointment's original scheduled time before any rescheduling occurred, allowing administrators to maintain a complete history of appointment changes and analyze rescheduling trends. The **reminder24hSent** field is a boolean that tracks whether a 24-hour reminder notification was sent to the patient before their appointment. The **reminder3hSent** field is a boolean that tracks whether a 3-hour reminder notification was sent. The **reminder24hSentAt** field is a timestamp recording exactly when the 24-hour reminder was delivered, enabling administrators to verify notification delivery and analyze reminder effectiveness. The **reminder3hSentAt** field is a timestamp recording when the 3-hour reminder was delivered. This comprehensive tracking enables administrators to monitor appointment management efficiency, ensure proper notification delivery, identify patients who may need additional reminders, and analyze appointment reminder patterns to optimize notification timing and reduce no-shows. Administrators have complete visibility and management capabilities for all appointment-related data across the entire system, including the ability to view, modify, or delete any appointment regardless of patient or staff assignment.

The appointment request management system provides administrators with complete oversight of all appointment requests. Administrators can review, approve, or deny any appointment request in the system, view the complete review history and notes associated with each request, and reassign requests to different staff members when necessary. This oversight capability ensures proper coordination and management of appointment requests across the clinic.

Blocked time management gives administrators full control over time blocking functionality. Administrators can create and manage blocked time slots throughout the system and view all blocked times across the entire clinic, providing comprehensive calendar management capabilities.

Patient record management for administrators includes all features available to staff members, with additional administrative oversight capabilities. Administrators can view complete audit trails showing who created or modified each record, export patient data for reporting or backup purposes, and manage record sharing with patients. This comprehensive access ensures administrators can monitor patient record management activities and maintain data integrity across the system.

The feedback management system provides administrators with complete access to all patient feedback across the system. Administrators can view all feedback entries, filter and analyze feedback by various criteria such as service type, rating level, date range, and other parameters. This analytical capability enables administrators to generate comprehensive feedback reports and analytics that help identify trends, areas for improvement, and overall patient satisfaction levels.

The notification system for administrators operates at a system-wide level, allowing administrators to view and manage notifications for all users in the system. Administrators can send notifications to any user, view notification delivery status across the entire system, and monitor notification effectiveness. This system-wide control enables administrators to coordinate communications and ensure important messages reach the appropriate recipients.

Conversation logs provide administrators with complete oversight of all chat conversations between patients and staff. Administrators can view all conversations in the system, participate in any conversation when necessary, monitor conversation quality and response times, and view conversation analytics that help assess communication effectiveness and identify areas for improvement in patient support.

Content management for administrators includes full control over all content types with additional administrative features. Services can be managed with complete control, announcements can be created, edited, and managed with full administrative oversight, and the announcement archive system allows administrators to archive and restore announcements while tracking who archived each announcement and when the archiving occurred. Clinic events can be managed comprehensively, and email templates for all system communications can be configured and maintained. This complete content management capability ensures administrators can control all information displayed throughout the system.

**ChatbotManagement** provides administrators with complete control over the ToothTalk chatbot system. Within ChatbotManagement, the **chatbotSetting** subsection contains global chatbot configuration. The **settingID** is the primary key uniquely identifying the chatbot settings record, typically implemented as a bigint(20) unsigned (often a singleton record with ID 1). The **enabled** field is a boolean that determines whether the chatbot is currently active and available to users. When disabled, the chatbot is hidden from the user interface and does not respond to queries. The **isOnline** field is a boolean that indicates whether the chatbot is currently online and accepting conversations, allowing administrators to temporarily take the chatbot offline for maintenance without disabling it entirely. The **censorshipEnabled** field is a boolean that controls whether content censorship is active, filtering inappropriate words from chatbot conversations. The **welcomeMessage** field is a text field containing the message that greets users when they first interact with the chatbot, which can be customized to provide a friendly introduction and set expectations. The **quickIntents** field is a JSON field that stores structured data for quick response intents, allowing administrators to configure common questions and their immediate answers, such as "What are your hours?" or "How do I book an appointment?". This JSON structure enables flexible configuration of quick responses without requiring code changes. The **createDM** and **updatedAt** fields track when settings were created and last modified.

The **chatbotFaq** subsection enables administrators to manage frequently asked questions. The **faqID** is the primary key uniquely identifying each FAQ entry, implemented as a bigint(20) unsigned. The **question** field is a text field storing the FAQ question that users might ask. The **answer** field is a text field containing the response that the chatbot will provide when this question is detected. The **isActive** field is a boolean determining whether the FAQ is currently active and will be used by the chatbot, allowing administrators to temporarily disable FAQs without deleting them. The **order** field is an integer that determines the display order of FAQs when presented to users, enabling administrators to prioritize important questions. The **createDM** and **updatedAt** fields track when FAQs were created and modified.

The **chatCensoredWord** subsection allows administrators to manage words that should be filtered from chatbot conversations. The **wordID** is the primary key uniquely identifying each censored word entry, implemented as a bigint(20) unsigned. The **word** field is a varchar(255) storing the word or phrase that should be censored, which may include variations or partial matches depending on the filtering algorithm. The **createdByID** field is a foreign key linking to the users table, identifying which administrator added the censored word, maintaining accountability for content filtering decisions. The **createdByType** field is a varchar(255) that may store the type of user who created the entry (e.g., "admin"), providing additional context for audit purposes. The **createDM** and **updatedAt** fields track when censored words were added and last modified. This comprehensive chatbot management ensures administrators can maintain appropriate content standards, provide effective automated support, customize the chatbot experience, and ensure compliance with content policies.

**SystemManagement** represents the most comprehensive administrative capability, providing complete system oversight and monitoring. Within SystemManagement, the **activityLog** subsection creates a comprehensive audit trail of all system activities. The **logID** is the primary key uniquely identifying each activity log entry, implemented as a bigint(20) unsigned. The **userID** field is a foreign key linking to the users table, identifying which user performed the action. The **action** field is a varchar(255) storing the type of action performed, such as 'create', 'update', 'delete', 'view', 'login', or 'logout'. The **module** field is a varchar(255) identifying which system module was accessed, such as 'appointments', 'patient_records', 'user_management', or 'content_management', enabling administrators to track which parts of the system are most frequently used. The **description** field is a text field containing a human-readable description of the action, providing context for what occurred. The **recordID** field is a bigint(20) unsigned storing the ID of the specific record that was affected by the action, enabling administrators to trace the history of individual records. The **recordType** field is a varchar(255) identifying the type of record affected, such as 'appointment', 'patient_record', or 'user'. The **oldValues** field is a JSON field storing the previous state of the record before modification, enabling administrators to see exactly what changed and potentially rollback changes if necessary. The **newValues** field is a JSON field storing the new state of the record after modification, providing a complete before-and-after snapshot of changes. The **ipAddress** field is a varchar(45) storing the IP address from which the action was performed, which is critical for security monitoring and identifying suspicious activities or unauthorized access attempts. The **userAgent** field is a text field storing the browser or client user agent string, helping identify the device and browser used for the action, which can be useful for troubleshooting or security investigations. The **createDM** (created_at) and **updatedAt** fields track when the log entry was created and last modified. This detailed logging provides administrators with complete visibility into system usage, helps maintain security and compliance, enables forensic analysis of security incidents, and supports audit requirements.

The **fullyBookedDay** subsection allows administrators to mark specific days as fully booked, preventing appointment scheduling on those days. The **dayID** is the primary key uniquely identifying each fully booked day entry, implemented as a bigint(20) unsigned. The **date** field is a date type storing the specific date that should be blocked from appointment scheduling. This feature is useful for managing holidays, special events, clinic closures, staff training days, or any other situations where the clinic cannot accept appointments. The **createDM** and **updatedAt** fields track when fully booked days were created and modified.

The **passwordResetToken** subsection enables administrators to monitor password reset requests. The **email** field is a varchar(255) storing the email address associated with the password reset request. The **token** field is a varchar(255) storing the unique reset token that is sent to the user's email, which must match for the password reset to be processed. The **createDM** (created_at) field is a timestamp recording when the password reset token was generated. The **updatedAt** field tracks when the token was last modified. The **expiresAt** field is a timestamp storing when the password reset token expires, typically set to a short time period (e.g., 1 hour) for security purposes. This monitoring enables administrators to track password reset requests, monitor token expiration, identify potential security issues such as excessive reset requests, and maintain security oversight for authentication processes. This comprehensive system management capability ensures administrators can maintain system security, monitor usage patterns, respond effectively to security incidents, comply with audit requirements, and optimize system performance.

Security management for administrators includes refresh token management for maintaining secure sessions, along with complete security oversight and monitoring capabilities that enable administrators to protect the system and its data from unauthorized access and security threats.

---

## Key Differences Between User Types

### Patient (role_id: 3)

**Access Level**: Restricted - Own Data Only

**Core Capabilities**:
- **Limited Access**: Can only view and manage their own personal data
- **Own Appointments**: Can view, create, and manage only their own appointments
- **Own Records**: Can view their own patient records, histories, and progress notes
- **Own Notifications**: Receives notifications related only to their account
- **Feedback**: Can provide feedback and ratings (1-5 stars) for completed appointments
- **Chat**: Can initiate and participate in conversations with staff/admin
- **Profile Management**: Can update their own profile information

**Data Scope**:
- Single user account
- Personal appointments only
- Own medical/dental records
- Own notification history
- Own conversation logs

**Restrictions**:
- Cannot view other patients' data
- Cannot access administrative functions
- Cannot manage system content
- Cannot view staff-only information

---

### Staff (role_id: 2)

**Access Level**: Extended - All Patient Data + Content Management

**Core Capabilities**:
- **Extended Access**: Can view and manage all patient data across the system
- **All Appointments**: Can view, create, edit, delete, and manage all patient appointments
- **All Patient Records**: Can view, create, edit, and manage all patient records and histories
- **Appointment Requests**: Can review, approve, or deny appointment requests
- **Blocked Time**: Can block time slots to prevent scheduling
- **Content Management**: Can manage services, announcements, events, and mail templates
- **Access Controls**: Has granular permission controls configured by administrators
- **Chat**: Can respond to patient conversations and attach files
- **Notifications**: Can view and manage notifications
- **Feedback Viewing**: Can view all patient feedback and ratings

**Data Scope**:
- All patient accounts
- All appointments (all patients)
- All patient records
- All appointment requests
- All notifications
- All conversations
- Content management data

**Restrictions**:
- Cannot manage other staff or administrator accounts
- Cannot modify system roles
- Cannot access activity logs
- Cannot configure chatbot settings
- Cannot access system management features
- Permissions controlled by administrator

---

### Administrator (role_id: 1)

**Access Level**: Full - Complete System Access

**Core Capabilities**:
- **Full System Access**: Complete access to all system features and data
- **User Management**: Can create, edit, delete, and manage all users (patients, staff, administrators)
- **Role Management**: Can create and modify system roles
- **Access Control Management**: Can configure granular permissions for all staff members
- **System Monitoring**: Can view comprehensive activity logs and system analytics
- **Chatbot Management**: Can configure chatbot settings, FAQs, and censored words
- **Content Management**: Full control over all content including archives
- **Appointment Oversight**: Complete oversight of all appointments with reminder tracking
- **Security Management**: Can monitor password resets, tokens, and security events
- **Data Export**: Can export any data from the system
- **All Staff Features**: Has access to all features available to staff, plus administrative functions

**Data Scope**:
- All user accounts (patients, staff, administrators)
- All appointments and appointment requests
- All patient records
- All notifications (system-wide)
- All conversations
- All content and archives
- System configuration
- Activity logs and audit trails
- Chatbot configuration
- Security tokens and reset requests

**No Restrictions**:
- Complete system control
- Can modify any data
- Can configure any system setting
- Can access all audit logs
- Can manage all users and permissions

---

## Access Comparison Matrix

| Feature | Patient | Staff | Administrator |
|---------|---------|-------|---------------|
| View Own Appointments | ✅ | ✅ | ✅ |
| View All Appointments | ❌ | ✅ | ✅ |
| Create Appointments | ✅ (own) | ✅ (all) | ✅ (all) |
| Edit Appointments | ✅ (own) | ✅ (all) | ✅ (all) |
| Delete Appointments | ✅ (own) | ✅ (all) | ✅ (all) |
| View Own Records | ✅ | ✅ | ✅ |
| View All Records | ❌ | ✅ | ✅ |
| Create/Edit Records | ❌ | ✅ | ✅ |
| Manage Users | ❌ | ❌ | ✅ |
| Manage Roles | ❌ | ❌ | ✅ |
| Configure Permissions | ❌ | ❌ | ✅ |
| Manage Content | ❌ | ✅ | ✅ |
| View Activity Logs | ❌ | ❌ | ✅ |
| Manage Chatbot | ❌ | ❌ | ✅ |
| System Configuration | ❌ | ❌ | ✅ |
| Export Data | ❌ | ⚠️ (if permitted) | ✅ |

---

## Data Flow and Relationships

### Patient Data Flow
1. **Registration**: Patient creates account → User record created → Role assigned (role_id: 3)
2. **Appointment Booking**: Patient requests appointment → Appointment request created → Staff reviews → Appointment confirmed
3. **Visit**: Appointment occurs → Patient record updated → Progress notes added → History created
4. **Feedback**: Appointment completed → Patient provides feedback → Rating stored in appointment record
5. **Notifications**: System events trigger notifications → Patient receives notification → Patient reads notification

### Staff Data Flow
1. **Account Creation**: Administrator creates staff account → Access controls configured → Staff can access assigned modules
2. **Appointment Management**: Staff views appointment requests → Reviews and approves/denies → Appointment created/updated
3. **Record Management**: Staff accesses patient record → Updates information → Creates history/progress notes → Sends to patient
4. **Content Management**: Staff creates/edits content → Content published → Patients view content

### Administrator Data Flow
1. **User Management**: Admin creates user → Assigns role → Configures permissions (for staff)
2. **System Monitoring**: All actions logged → Activity log created → Admin reviews logs
3. **Configuration**: Admin modifies settings → Changes applied system-wide → Affects all users
4. **Security**: Admin monitors security events → Reviews tokens → Manages access

## Security Considerations

### Data Privacy
- **Patient Data Isolation**: Patients can only access their own data
- **Staff Access Control**: Staff permissions are granular and configurable
- **Audit Trails**: All actions are logged for administrators to review
- **Secure Authentication**: All users require credentials and can use password reset tokens

### Access Enforcement
- **Database Level**: Foreign key constraints ensure data integrity
- **Application Level**: Role-based middleware enforces access control
- **Permission Checks**: Staff permissions checked before allowing actions
- **Session Management**: Refresh tokens maintain secure sessions

## Notes

### Field Naming Conventions
- **createDM**: Represents `created_at` timestamp (Creation Date/Time)
- **updatedAt**: Represents `updated_at` timestamp
- **dateTime**: Represents datetime fields (includes both date and time)
- **date**: Represents date-only fields (no time component)
- **ID fields**: All ID fields are unique identifiers (primary keys or foreign keys)

### Data Relationships
- All hierarchical structures reflect the actual database schema and relationships
- Foreign key relationships are maintained at the database level
- Access permissions are enforced at the application level based on `role_id`
- Many relationships are one-to-many (one user has many appointments)
- Some relationships are one-to-one (one user has one profile)

### Implementation Notes
- The system uses Laravel's Eloquent ORM for data relationships
- Role-based access control (RBAC) is implemented using middleware
- Staff permissions are stored in the `staff_access_controls` table
- All timestamps are automatically managed by Laravel
- JSON fields are used for complex data structures (allergies_detail, health_questions, etc.)

---

## Conclusion

These hierarchical diagrams provide a comprehensive view of the data structures accessible to each user type in the Dental Clinic Management System. Understanding these structures is essential for:

- **Developers**: To implement proper access controls and data relationships
- **System Administrators**: To configure permissions and manage users effectively
- **Staff**: To understand what data they can access and manage
- **Patients**: To understand what information is available to them
- **Auditors**: To verify data access and security compliance

The role-based access control model ensures that each user type has appropriate access to the data and features they need, while maintaining security and privacy standards.

---

**Document Version:** 1.0  
**Last Updated:** 2025  
**System:** Dental Clinic Management System  
**Framework:** Laravel (PHP)

