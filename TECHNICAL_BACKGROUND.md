# ToothTalk System - Technical Background

## Overview
ToothTalk is a comprehensive dental clinic management system built with modern web technologies. It provides patient management, appointment scheduling, live chat functionality, and staff access control features.

---

## Backend Framework

### **Laravel 12.0
- **Language**: PHP 8.2+ - Server-side programming language that powers the backend logic
- **Framework**: Laravel 12.0 - Web application framework that provides structure and tools for building the system
- **Architecture**: MVC (Model-View-Controller) - Design pattern that separates data, presentation, and business logic
- **API**: RESTful API endpoints - Standardized interfaces for communication between frontend and backend
- **Authentication**: Laravel Sanctum for API authentication - Secures API access with token-based authentication
- **Session Management**: Laravel's built-in session handling with multiple guards (admin, staff, patient) - Manages user login sessions for different user types

### Key Backend Features
- **Multi-guard Authentication**: Separate authentication systems for Admin, Staff, and Patients - Allows different login systems for each user type
- **Role-Based Access Control (RBAC)**: User roles managed through database - Controls what features each user type can access
- **Queue System**: Database-based queue for background job processing - Handles time-consuming tasks like sending emails without blocking the user interface
- **Activity Logging**: Comprehensive activity tracking system - Records all user actions for security and auditing purposes
- **Email Notifications**: Template-based email system for appointment notifications - Sends automated emails for appointment confirmations, reminders, and updates

---

## Frontend Technologies

### **Blade Templates**
- **Template Engine**: Laravel Blade - Generates HTML pages by combining templates with data from the backend
- **Styling**: Tailwind CSS 4.0 - Utility-first CSS framework for quickly styling the user interface
- **Build Tool**: Vite 7.0 - Compiles and optimizes CSS and JavaScript files for faster page loading
- **HTTP Client**: Axios 1.11.0 - JavaScript library that sends requests to the backend API and receives responses

### **UI Frameworks & Libraries** (CDN)
- **Bootstrap 5.3.7**: CSS framework and components - Provides pre-built UI components like buttons, forms, and navigation bars
- **Bootstrap Icons**: Icon library - Supplies visual icons for buttons and interface elements
- **Font Awesome 6.5.0**: Additional icon set - Offers more icon options for enhanced visual design
- **jQuery 3.7.1**: DOM manipulation and AJAX - Simplifies JavaScript code for updating page content and making API calls
- **Google Fonts**: Poppins typography - Provides the Poppins font family for consistent text styling across the system

### Frontend Features
- **Responsive Design**: Mobile-first approach using Tailwind CSS
- **Real-time Updates**: JavaScript-based polling for live chat
- **Dynamic UI**: Client-side JavaScript for interactive features
- **Component-based**: Reusable Blade components

---

## Database

### **Database System**
- **Primary**: SQLite (default, development) - Lightweight file-based database used during development
- **Production Support**: MySQL/MariaDB (configurable) - Robust database system for production environments that handles large amounts of data
- **ORM**: Laravel Eloquent - Object-relational mapping tool that simplifies database operations using PHP code instead of SQL queries

### Database Structure
- **Users & Authentication**: Users, roles, password resets
- **Appointments**: Scheduling, status tracking, ratings
- **Patient Records**: Medical histories, progress notes
- **Chat System**: Conversations, messages, attachments
- **Staff Access Control**: Granular permission system
- **Notifications**: In-app notification system
- **Activity Logs**: System activity tracking
- **Announcements**: Clinic announcements and archives

---

## Live Chat Feature

### **Architecture**
- **Real-time Communication**: Polling-based system (3-second intervals)
- **Conversation Management**: Persistent conversations per patient
- **Message Storage**: Database-backed message history
- **File Attachments**: Support for file uploads in chat
- **Censorship System**: Configurable word filtering
- **Online/Offline Status**: Staff availability tracking

### Technical Implementation
- **Frontend**: JavaScript polling with Fetch API
- **Backend**: RESTful API endpoints
- **Storage**: `chat_conversations` and `chat_messages` tables
- **Authentication**: Patient authentication required
- **Status Management**: Active, resolved, and closed conversation states

---

## Staff Access Control System

### **Features**
- **Granular Permissions**: Per-feature access control
- **Navigation Control**: Restrict access to specific pages
- **Action Permissions**: Control specific actions (create, edit, delete)
- **Dynamic Updates**: Real-time permission updates
- **Password Protection**: Admin password required for permission changes

### Technical Implementation
- **Database**: `staff_access_controls` table
- **Middleware**: Custom access checking trait (`CheckStaffAccess`)
- **Controllers**: Access control validation in staff controllers
- **UI Integration**: JavaScript handlers for permission management

### Access Control Areas
- Dashboard access
- Appointments management
- Patient records
- Live chat (ToothTalk)
- Announcements
- Reports and analytics
- File attachments in chat

---

## Tools & Services

### **Development Tools**
- **Package Manager**: Composer (PHP), npm (JavaScript) - Tools that download and manage third-party libraries and dependencies
- **Build System**: Vite - Compiles and bundles frontend assets (CSS/JS) for production deployment
- **Code Quality**: Laravel Pint (code formatting) - Automatically formats code to maintain consistent style across the project
- **Testing**: PHPUnit 11.5 - Framework for writing and running automated tests to ensure code works correctly

### **Third-Party Packages**
- **Laravel Sanctum**: API authentication - Generates secure tokens for API access and validates user identity
- **Maatwebsite Excel**: Excel export functionality - Converts data from the database into Excel spreadsheet files for reporting
- **Laravel Pail**: Log viewing tool - Displays application logs in real-time during development for debugging
- **Faker**: Test data generation - Creates fake but realistic data for testing purposes without using real patient information

### **Email Services**
- **Primary**: Laravel Mail - Built-in email system that handles sending emails through various providers
- **Supported Drivers**: SMTP, Log, Mailgun, Postmark, SES, Resend - Different email delivery methods that can be configured based on needs
- **Templates**: Database-stored email templates - Reusable email formats stored in the database that can be customized by admins
- **Notifications**: Appointment confirmations, reminders, updates - Automated emails sent to patients about their appointments
- **Common Configurations**: Gmail SMTP, Mailtrap (testing), custom SMTP servers - Typical email service setups used in development and production

### **Queue System**
- **Driver**: Database queue - Uses the database to store jobs that need to be processed later
- **Purpose**: Background job processing - Executes time-consuming tasks without making users wait
- **Use Cases**: Email sending, notification processing - Handles sending emails and creating notifications asynchronously

---

## External Services & APIs

### **Email Service Providers** (Configurable)
- **SMTP Services**: Gmail, Mailtrap, custom SMTP servers - Standard email protocols for sending emails through various providers
- **Mailgun**: Transactional email service (configurable) - Third-party service specializing in reliable email delivery for applications
- **Postmark**: Email delivery service (configurable) - Email service focused on high deliverability rates for transactional emails
- **AWS SES**: Amazon Simple Email Service (configurable) - Amazon's cloud-based email sending service for scalable email delivery
- **Resend**: Modern email API (configurable) - Modern email service with developer-friendly API for sending emails
- **Log Driver**: Development/testing email logging - Saves emails to log files instead of sending them, useful for testing

### **External APIs**
- **Google Maps Embed API**: Location display on clinic pages - Displays an interactive map showing the clinic's physical location to help patients find the clinic
  - Embedded maps showing clinic location
  - Address: Policarpio St. Gen. T. de Leon Valenzuela City, Philippines

### **CDN Resources** (External Libraries)
- **Bootstrap 5.3.7**: UI framework (jsdelivr CDN) - Pre-built CSS and JavaScript components loaded from a content delivery network for faster access
  - CSS and JavaScript components
  - Bootstrap Icons 1.13.1
- **jQuery 3.7.1**: JavaScript library (Cloudflare CDN) - JavaScript library loaded from CDN that simplifies DOM manipulation and AJAX requests
- **Font Awesome 6.5.0**: Icon library (Cloudflare CDN) - Icon font library loaded from CDN providing hundreds of icons for the interface
- **Google Fonts**: Poppins font family - Web fonts loaded from Google's servers to ensure consistent typography across all devices
  - Multiple font weights (100-900)
  - Loaded from Google Fonts CDN

### **Third-Party Software Integration**
- **Laravel Sanctum**: API token authentication - Provides secure token-based authentication for API endpoints
- **Maatwebsite Excel**: Excel file generation and export - Enables exporting appointment and patient data to Excel spreadsheets
- **Laravel Pail**: Real-time log viewing tool - Shows application logs in the terminal for debugging during development
- **Faker**: Test data generation for development - Creates realistic fake data for testing without using real patient information

---

## Additional Technologies

### **File Storage**
- **Local Storage**: Laravel's filesystem - Stores uploaded files directly on the server's file system
- **Profile Pictures**: User profile image uploads - Handles storing and serving user profile photos
- **Chat Attachments**: File attachments in live chat - Manages file uploads sent through the live chat feature

### **Session & Caching**
- **Session Driver**: File/database (configurable) - Stores user session data either in files or database to maintain login state
- **Cache**: File cache (configurable to Redis/Memcached) - Temporarily stores frequently accessed data to improve system performance

### **Security Features**
- **CSRF Protection**: Laravel's built-in CSRF tokens - Prevents cross-site request forgery attacks by validating form submissions
- **Password Hashing**: Bcrypt - Encrypts user passwords using one-way hashing so passwords cannot be recovered from the database
- **Input Validation**: Laravel validation rules - Checks user input to ensure data is correct and safe before processing
- **SQL Injection Protection**: Eloquent ORM parameter binding - Prevents malicious SQL code injection by safely binding parameters to database queries
- **XSS Protection**: Blade template escaping - Automatically escapes user input in templates to prevent cross-site scripting attacks

---

## Development Environment

### **Server Requirements**
- PHP 8.2 or higher
- Composer
- Node.js and npm
- Web server (Apache/Nginx) or PHP built-in server

### **Development Software**
- **Wampserver 64 3.3.7**: Local web development environment - Provides Apache web server, MySQL database, and PHP runtime for Windows development
- **HeidiSQL 12.11**: Database management tool - Graphical interface for managing MySQL/MariaDB databases, executing queries, and viewing database structure

### **Development Workflow**
- **Asset Compilation**: Vite for CSS/JS bundling
- **Hot Reload**: Vite dev server for frontend changes
- **Queue Worker**: Background job processing
- **Log Monitoring**: Laravel Pail for real-time logs

---

## Hosting & Deployment

### **Hosting Provider**
- **Hostinger**: Web hosting and deployment platform - Hosts the ToothTalk system application, provides web server infrastructure, and manages the production environment

### **Database Hosting**
- **Hostinger**: Database hosting service - Provides MySQL/MariaDB database server for storing all system data including appointments, patient records, chat messages, and user information

### **Domain Registration**
- **Hostinger**: Domain registrar - Registered and manages the `toothtalk.site` domain name for the system

---

## System Architecture Summary

```
┌─────────────────────────────────────────┐
│         Frontend (Blade + Tailwind)     │
│         - Responsive UI                  │
│         - JavaScript Polling            │
│         - Axios HTTP Client             │
└─────────────────┬───────────────────────┘
                  │
┌─────────────────▼───────────────────────┐
│      Laravel 12.0 (PHP 8.2)             │
│      - MVC Architecture                 │
│      - RESTful API                      │
│      - Multi-guard Auth                 │
│      - Queue System                     │
└─────────────────┬───────────────────────┘
                  │
┌─────────────────▼───────────────────────┐
│      Database (SQLite/MySQL)            │
│      - Eloquent ORM                     │
│      - Migrations                       │
│      - Relationships                    │
└─────────────────────────────────────────┘
```

---

## Key System Components

1. **Authentication System**: Multi-guard authentication (admin, staff, patient)
2. **Live Chat**: Real-time messaging with polling mechanism
3. **Staff Access Control**: Granular permission management
4. **Appointment Management**: Scheduling, status tracking, reminders
5. **Patient Records**: Medical history and progress notes
6. **Notification System**: In-app and email notifications
7. **Activity Logging**: Comprehensive audit trail
8. **Email System**: Template-based email notifications

---

*This document provides a high-level overview of the ToothTalk system's technical stack. For specific implementation details, refer to the source code and inline documentation.*

