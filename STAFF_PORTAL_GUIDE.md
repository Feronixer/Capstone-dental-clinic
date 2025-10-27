# Staff Portal Authentication Guide

## Overview
The JValera Dental Clinic system now has a **separate Staff Portal** for staff members (Administrators and Staff) to log in securely using their **username** instead of email.

---

## 🔐 Staff Portal Features

### 1. **Separate Login System**
- **URL**: `/staff/login`
- **Authentication**: Username + Password (not email)
- **Access Control**: Only staff members (role_id 1 or 2) can log in
- **Security**: Prevents patients from accessing staff portal

### 2. **Password Recovery**
- **Forgot Password**: Send verification code to staff email
- **Verification Code**: 6-digit code with 15-minute expiration
- **Password Reset**: Set new password with strength indicator

### 3. **Enhanced Security**
- Role-based access control
- Session management
- Activity logging for staff logins/logouts
- Password validation (minimum 8 characters)

---

## 📋 Staff Portal Routes

### **Authentication Routes**
```
GET  /staff/login                    - Show staff login form
POST /staff/login                    - Process staff login
GET  /staff/forgot-password          - Show forgot password form
POST /staff/forgot-password          - Send verification code
GET  /staff/reset-password/verify    - Show verification code form
POST /staff/reset-password/verify    - Verify code
GET  /staff/reset-password           - Show new password form
POST /staff/reset-password           - Reset password
POST /staff/logout                   - Staff logout
```

### **Dashboard Route** (after login)
```
GET /admin/dashboard                 - Staff dashboard (authenticated)
```

---

## 🎨 User Interface

### **Staff Login Page**
- **Title**: "JValera Dental Clinic - Staff Portal"
- **Shield icon** indicating secure staff access
- **Username field** (not email)
- **Password field** with show/hide toggle
- **Link to Patient Portal** at the bottom

### **Forgot Password Flow**
1. Enter staff email address
2. Receive 6-digit verification code via email
3. Enter verification code (auto-submits after 6 digits)
4. Set new password with strength indicator
5. Redirect to staff login

---

## 🔧 Technical Implementation

### **Files Created**

#### **Views**
- `resources/views/auth/staff-login.blade.php` - Staff login form
- `resources/views/auth/staff-forgot-password.blade.php` - Forgot password form
- `resources/views/auth/staff-reset-verify.blade.php` - Verification code form
- `resources/views/auth/staff-reset-password.blade.php` - Reset password form

#### **Controller**
- `app/Http/Controllers/Authentication/StaffAuthController.php` - Staff authentication logic

#### **Routes**
- Updated `routes/web.php` with staff authentication routes

---

## 🚀 How to Use

### **For Staff Members**

#### **First Time Login**
1. Administrator creates your staff account with:
   - Username
   - Name
   - Email
   - Password
   - Role (Admin or Staff)

2. You receive your login credentials

3. Go to `/staff/login`

4. Enter your **username** (not email) and password

5. Access the admin dashboard

#### **Forgot Password**
1. Click "Forgot Password?" on staff login page
2. Enter your **staff email address**
3. Check your email for 6-digit verification code
4. Enter the code (it will auto-submit)
5. Create a new strong password
6. Log in with your new password

---

## 🔒 Security Features

### **Access Control**
- ✅ Only users with `role_id` 1 (Admin) or 2 (Staff) can access
- ✅ Patients (role_id 3) are blocked from staff portal
- ✅ Clear error messages for unauthorized access

### **Session Management**
- ✅ Session regeneration on login
- ✅ Session invalidation on logout
- ✅ Secure token management

### **Activity Logging**
```php
// Login
\Log::info("Staff member 'username' logged in successfully");

// Password Reset
\Log::info("Password reset code sent to staff member: email@example.com");

// Logout
\Log::info("Staff member 'username' logged out");
```

---

## 🎯 Differences: Patient vs Staff Login

| Feature | Patient Portal | Staff Portal |
|---------|---------------|-------------|
| **URL** | `/login` | `/staff/login` |
| **Login Field** | Email | Username |
| **Access** | Patients only (role_id 3) | Staff only (role_id 1, 2) |
| **Icon** | 👤 User | 🛡️ Shield |
| **Password Reset** | Email-based | Email-based (staff only) |
| **Redirect After Login** | Patient Dashboard | Admin Dashboard |

---

## 📊 User Roles

| Role ID | Role Name | Portal Access |
|---------|-----------|---------------|
| 1 | Administrator | Staff Portal |
| 2 | Staff | Staff Portal |
| 3 | Patient | Patient Portal |

---

## ⚙️ Configuration

### **Mail Configuration**
Ensure your `.env` file has proper mail settings for password reset emails:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@jvaleradental.com
MAIL_FROM_NAME="JValera Dental Clinic"
```

### **Testing Email**
Use the test route to verify email functionality:
```
GET /admin/test-email
```

---

## 🐛 Troubleshooting

### **"Access denied. This portal is for staff members only."**
- Your account is not assigned a staff role (role_id must be 1 or 2)
- Contact the administrator to update your role

### **"Invalid staff credentials."**
- Verify you're using **username** (not email)
- Verify your password is correct
- Try the "Forgot Password" flow

### **"This email is not registered to a staff account."**
- Your email is not associated with a staff account
- Contact the administrator to create your staff account

---

## 📝 Code Examples

### **Staff Login Validation**
```php
// Check if user is staff
if (!in_array($user->role_id, [1, 2])) {
    return back()->withErrors([
        'error' => 'Access denied. This portal is for staff members only.',
    ]);
}
```

### **Username-based Authentication**
```php
// Attempt login with username
if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
    $request->session()->regenerate();
    return redirect()->route('admin-dashboard');
}
```

---

## 🎉 Summary

The Staff Portal provides:
- ✅ **Secure** username-based authentication
- ✅ **Separate** login system from patient portal
- ✅ **Role-based** access control
- ✅ **Complete** password recovery flow
- ✅ **Activity** logging for security auditing
- ✅ **Enhanced** user experience with visual feedback

---

**Last Updated**: October 27, 2025  
**Version**: 1.0  
**Author**: JValera Dental Clinic Development Team

