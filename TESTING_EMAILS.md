# Email Testing Guide for JValera Dental Clinic

## Method 1: Log Driver (Easiest - No Configuration)

### Step 1: Configure .env
```env
MAIL_MAILER=log
```

### Step 2: Create an Appointment
1. Login to admin panel
2. Go to Appointments page
3. Create a new appointment for a patient with a valid email
4. Click "Save"

### Step 3: Check the Log File
```bash
# Windows PowerShell
Get-Content storage/logs/laravel.log -Tail 100

# Or open the file directly:
# storage/logs/laravel.log
```

Look for lines containing:
- `Email sent successfully`
- The email content in the log

---

## Method 2: Mailtrap (Recommended for Testing)

Mailtrap catches all emails in a fake inbox - perfect for testing!

### Step 1: Create Free Mailtrap Account
1. Go to https://mailtrap.io
2. Sign up for free account
3. Go to "Email Testing" → "Inboxes"
4. Click on your inbox → "SMTP Settings"

### Step 2: Configure .env
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=clinic@jvalera.com
MAIL_FROM_NAME="JValera Dental Clinic"
```

### Step 3: Test
1. Create an appointment
2. Check your Mailtrap inbox
3. You'll see the email with full formatting!

**Benefits:**
- ✅ See actual email design
- ✅ Test on different devices
- ✅ Check spam score
- ✅ No emails sent to real addresses

---

## Method 3: Gmail (For Production)

### Step 1: Enable 2-Factor Authentication
1. Go to Google Account settings
2. Security → 2-Step Verification → Turn On

### Step 2: Generate App Password
1. Go to Google Account → Security
2. Search for "App passwords"
3. Select app: "Mail"
4. Select device: "Other" → Enter "Laravel"
5. Click "Generate"
6. Copy the 16-character password

### Step 3: Configure .env
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-16-char-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-gmail@gmail.com
MAIL_FROM_NAME="JValera Dental Clinic"
```

### Step 4: Test
1. Create an appointment with YOUR email as the patient email
2. Check your Gmail inbox
3. The email should arrive within seconds!

**⚠️ Warning:** Real emails will be sent with this method!

---

## Quick Test Script

Create a test route to send a test email:

### Add to routes/web.php:
```php
Route::get('/test-email', function() {
    $appointment = \App\Models\Appointment::with(['patient.info', 'service'])->first();
    
    if (!$appointment) {
        return "No appointments found to test";
    }
    
    try {
        \App\Services\MailService::sendAppointmentEmail('initial_confirmation', $appointment);
        return "Email sent! Check your inbox or logs.";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});
```

### Test by visiting:
```
http://localhost:8000/test-email
```

---

## Testing Different Email Types

### 1. Initial Confirmation
```php
// Create a new appointment (automatic)
// Or use test route
\App\Services\MailService::sendAppointmentEmail('initial_confirmation', $appointment);
```

### 2. Rescheduling
```php
// Edit an appointment and change the datetime (automatic)
// Or use test route
\App\Services\MailService::sendAppointmentEmail('rescheduling', $appointment);
```

### 3. Cancellation
```php
// Delete an appointment (automatic)
// Or use test route
\App\Services\MailService::sendAppointmentEmail('cancellation', $appointment);
```

### 4. Reminder
```php
// Manual test
\App\Services\MailService::sendAppointmentEmail('reminder', $appointment);
```

### 5. Follow Up
```php
// Manual test
\App\Services\MailService::sendAppointmentEmail('follow_up', $appointment);
```

---

## Troubleshooting

### Problem: No emails in log file
**Solution:**
1. Check `storage/logs/laravel.log` exists
2. Verify `MAIL_MAILER=log` in .env
3. Run: `php artisan config:clear`

### Problem: Gmail authentication failed
**Solution:**
1. Make sure 2FA is enabled
2. Use App Password, not regular password
3. Check for typos in .env
4. Run: `php artisan config:clear`

### Problem: Emails not sending
**Solution:**
1. Check patient has valid email
2. Check Laravel logs: `storage/logs/laravel.log`
3. Run: `php artisan config:clear`
4. Test with: `php artisan tinker` then:
   ```php
   Mail::raw('Test email', function($msg) {
       $msg->to('angelsarandin123@gmail.com')->subject('Test');
   });
   ```

### Problem: Template placeholders not replaced
**Solution:**
1. Verify template in database has %firstname%, %datetime%, %service%
2. Check appointment has patient and service loaded
3. Check logs for MailService errors

---

## Checking Email Content

### In Log File (MAIL_MAILER=log):
```
Email sent successfully to patient@email.com for appointment 123
```

Look for the HTML content in the log file.

### In Mailtrap:
- Full HTML preview
- Plain text version
- Spam score
- Email size
- Headers

### In Gmail:
- Real inbox experience
- Test on mobile
- Check spam folder
- Test forwarding

---

## Best Practices

1. **Development:** Use `log` or Mailtrap
2. **Testing:** Use Mailtrap
3. **Production:** Use Gmail or professional SMTP (SendGrid, Mailgun, etc.)

4. **Always test:**
   - Creating appointment
   - Rescheduling appointment
   - Canceling appointment
   - Different patient emails
   - Template customization

5. **Remember to:**
   - Run `php artisan config:clear` after changing .env
   - Check `storage/logs/laravel.log` for errors
   - Test with real patient emails before going live

---

## Email Template Customization

Go to: **Admin → Content Management → Patient Mail Settings**

1. Click on email type (Initial Confirmation, etc.)
2. Edit the template in "Mail Structure"
3. Use placeholders:
   - `%firstname%` - Patient's first name
   - `%datetime%` - Appointment date and time
   - `%service%` - Service name
4. Click "Edit Template"
5. Test by creating an appointment!

---

## Quick Start (Recommended)

1. Set `MAIL_MAILER=log` in .env
2. Run `php artisan config:clear`
3. Create a test appointment
4. Check `storage/logs/laravel.log`
5. Look for "Email sent successfully"

That's it! 🎉

