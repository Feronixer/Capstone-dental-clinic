<?php

use App\Http\Controllers\Authentication\AuthController;
use App\Http\Controllers\Authentication\StaffAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AccountManagementController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\ContentManagementController;
use App\Http\Controllers\Admin\PostProceduralController;
use App\Http\Controllers\Admin\ToothTalkController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\PatientRecordAccessController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;
use App\Http\Controllers\Patient\CalendarController;
use App\Http\Controllers\Patient\ProfileController as PatientProfileController;
use App\Http\Controllers\Patient\PatientRecord;
use App\Http\Controllers\Patient\AnnouncementController;
use App\Http\Controllers\Staff\StaffDashboard;
use Illuminate\Support\Facades\Route;



Route::get("/", [HomeController::class,"showHomePage"])->name("home");
Route::get('/login', [AuthController::class, 'showLoginForm'])->name(name: 'login');
Route::post('/login', [AuthController::class,'login']);
Route::get('/register', [AuthController::class,'showRegisterForm'])->name(name: 'register');
Route::post('/register', [AuthController::class,'register']);

// Patient Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.forgot');
Route::post('/forgot-password', [AuthController::class, 'sendResetCode'])->name('password.reset.send');
Route::get('/reset-password/verify', [AuthController::class, 'showResetVerifyForm'])->name('password.reset.verify');
Route::post('/reset-password/verify', [AuthController::class, 'verifyResetCode'])->name('password.reset.verify.submit');
Route::get('/reset-password', [AuthController::class, 'showResetForm'])->name('password.reset.form');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');

// Staff Authentication Routes
Route::get('/staff/login', [StaffAuthController::class, 'showLoginForm'])->name('staff.login');
Route::post('/staff/login', [StaffAuthController::class, 'login'])->name('staff.login.submit');
Route::get('/staff/forgot-password', [StaffAuthController::class, 'showForgotPasswordForm'])->name('staff.password.forgot');
Route::post('/staff/forgot-password', [StaffAuthController::class, 'sendResetCode'])->name('staff.password.reset.send');
Route::get('/staff/reset-password/verify', [StaffAuthController::class, 'showResetVerifyForm'])->name('staff.password.reset.verify');
Route::post('/staff/reset-password/verify', [StaffAuthController::class, 'verifyResetCode'])->name('staff.password.reset.verify.submit');
Route::get('/staff/reset-password', [StaffAuthController::class, 'showResetForm'])->name('staff.password.reset.form');
Route::post('/staff/reset-password', [StaffAuthController::class, 'resetPassword'])->name('staff.password.reset');

Route::middleware(['auth'])->group(function(): void{
    //Admin Routes
    Route::get('/admin/dashboard', [AdminDashboardController::class,'index'])->name('admin-dashboard');

    Route::get('/admin/account-management', [AccountManagementController::class,'index'])->name('admin-account-management');
    Route::post('/admin/account-management', [AccountManagementController::class,'store']);
    Route::put('/admin/account-management/users/{user}', [AccountManagementController::class,'update'])->name('users.update');
    Route::get('/admin/account-management/users/{id}', [AccountManagementController::class, 'show'])->name('users.show');
    Route::delete('/admin/account-management/users/{id}', [AccountManagementController::class, 'destroy'])->name('users.delete');
    Route::post('/admin/account-management/users/change-password/{id}', [AccountManagementController::class,'changePasswword']);


    Route::get('/admin/appointment', [AppointmentController::class,'index'])->name('admin-appointment');
    Route::get('/admin/appointment/appointments', [AppointmentController::class,'getAppointments'])->name('admin-appointment.get');
    Route::post('/admin/appointment', [AppointmentController::class,'store'])->name('admin-appointment.store');
    Route::get('/admin/appointment/{id}', [AppointmentController::class,'show'])->name('admin-appointment.show');
    Route::put('/admin/appointment/{id}', [AppointmentController::class,'update'])->name('admin-appointment.update');
    Route::post('/admin/appointment/{id}/delete', [AppointmentController::class,'destroy'])->name('admin-appointment.destroy');
    Route::patch('/admin/appointment/{id}/status', [AppointmentController::class,'updateStatus'])->name('admin-appointment.status');
    Route::get('/admin/appointment/search/patients', [AppointmentController::class,'searchPatients'])->name('admin-appointment.search-patients');

    // Blocked Time Routes
    Route::post('/admin/blocked-time', [App\Http\Controllers\Admin\BlockedTimeController::class, 'store'])->name('admin-blocked-time.store');
    Route::put('/admin/blocked-time/{id}', [App\Http\Controllers\Admin\BlockedTimeController::class, 'update'])->name('admin-blocked-time.update');
    Route::post('/admin/blocked-time/{id}/delete', [App\Http\Controllers\Admin\BlockedTimeController::class, 'destroy'])->name('admin-blocked-time.destroy');
    Route::get('/admin/content-management', [ContentManagementController::class,'index'])->name('admin-content-management');
    Route::post('/admin/content-management/announcement', [ContentManagementController::class,'updateAnnouncement'])->name('admin-content-management.announcement.update');
    Route::post('/admin/content-management/service', [ContentManagementController::class,'storeService'])->name('admin-content-management.service.store');
    Route::put('/admin/content-management/service/{id}', [ContentManagementController::class,'updateService'])->name('admin-content-management.service.update');
    Route::delete('/admin/content-management/service/{id}', [ContentManagementController::class,'destroyService'])->name('admin-content-management.service.destroy');
    Route::post('/admin/content-management/mail-template/{type}', [ContentManagementController::class,'updateMailTemplate'])->name('admin-content-management.mail-template.update');
    Route::get('/admin/content-management/patients-with-appointments', [ContentManagementController::class,'getPatientsWithAppointments'])->name('admin-content-management.patients-with-appointments');
    Route::get('/admin/content-management/patient-appointments/{patientId}', [ContentManagementController::class,'getPatientAppointments'])->name('admin-content-management.patient-appointments');
    Route::post('/admin/content-management/send-patient-email', [ContentManagementController::class,'sendPatientEmail'])->name('admin-content-management.send-patient-email');
    Route::get('/admin/post-procedural', [PostProceduralController::class,'index'])->name('admin-post-procedural');
    Route::get('/admin/post-procedural/patient-record/{id}', [PostProceduralController::class,'getPatientRecord']);
    Route::get('/admin/post-procedural/patient-record-by-user/{userId}', [PostProceduralController::class,'getPatientRecordByUser']);
    Route::post('/admin/post-procedural/patient-record/store', [PostProceduralController::class,'storePatientRecord']);
    Route::delete('/admin/post-procedural/patient-record/{id}', [PostProceduralController::class,'destroyPatientRecord']);
    Route::get('/admin/post-procedural/search-patients', [PostProceduralController::class,'searchPatients']);
    Route::post('/admin/post-procedural/send-to-patient', [PostProceduralController::class,'sendToPatient']);
    Route::get('/admin/post-procedural/patient-history/{id}', [PostProceduralController::class,'getPatientHistory']);
    Route::post('/admin/post-procedural/patient-history', [PostProceduralController::class,'storePatientHistory']);
    Route::delete('/admin/post-procedural/patient-history/{id}', [PostProceduralController::class,'destroyPatientHistory']);
    Route::get('/admin/post-procedural/progress-notes/{id}', [PostProceduralController::class,'getProgressNotes']);
    Route::post('/admin/post-procedural/progress-notes', [PostProceduralController::class,'storeProgressNote']);
    Route::delete('/admin/post-procedural/progress-notes/{id}', [PostProceduralController::class,'destroyProgressNote']);
    Route::get('/admin/toothtalk', [ToothTalkController::class,'index'])->name('admin-toothtalk');
    Route::get('/admin/notifications', [AdminNotificationController::class,'index'])->name('admin-notification');
    Route::get('/admin/profile', [ProfileController::class,'index'])->name('admin-profile');
    Route::post('/admin/profile/update', [ProfileController::class,'update'])->name('admin-profile.update');
    Route::post('/admin/profile/update-picture', [ProfileController::class,'updateProfilePicture'])->name('admin-profile.update-picture');
    Route::post('/admin/profile/update-password', [ProfileController::class,'updatePassword'])->name('admin-profile.update-password');

    // Patient Record Access Routes
    Route::get('/admin/patient-records', [PatientRecordAccessController::class,'index'])->name('admin-patient-records');
    Route::get('/admin/patient-records/search', [PatientRecordAccessController::class,'searchPatients'])->name('admin-patient-records.search');
    Route::get('/admin/patient-records/{id}', [PatientRecordAccessController::class,'getPatientDetails'])->name('admin-patient-records.details');
    Route::get('/admin/patient-records/{id}/export', [PatientRecordAccessController::class,'exportPatientRecord'])->name('admin-patient-records.export');

    // Test Email Route (for development/testing)
    Route::get('/admin/test-email', function() {
        $appointment = \App\Models\Appointment::with(['patient.info', 'service'])->first();

        if (!$appointment) {
            return '<div style="font-family: Arial; padding: 20px; background: #fee; border: 2px solid #c33; border-radius: 8px; margin: 20px;">
                <h3 style="color: #c33;">❌ No Appointments Found</h3>
                <p>Please create at least one appointment first to test emails.</p>
            </div>';
        }

        $patient = $appointment->patient;
        $patientName = $patient->info ? $patient->info->first_name . ' ' . $patient->info->last_name : $patient->name;

        try {
            \App\Services\MailService::sendAppointmentEmail('initial_confirmation', $appointment);

            return '<div style="font-family: Arial; padding: 20px; background: #dfd; border: 2px solid #3c3; border-radius: 8px; margin: 20px;">
                <h3 style="color: #3c3;">✅ Email Sent Successfully!</h3>
                <p><strong>To:</strong> ' . $patient->email . '</p>
                <p><strong>Patient:</strong> ' . $patientName . '</p>
                <p><strong>Type:</strong> Initial Confirmation</p>
                <hr>
                <p><strong>Next Steps:</strong></p>
                <ul>
                    <li>If using MAIL_MAILER=log, check <code>storage/logs/laravel.log</code></li>
                    <li>If using Mailtrap, check your inbox at mailtrap.io</li>
                    <li>If using Gmail, check ' . $patient->email . '</li>
                </ul>
                <p style="margin-top: 20px;">
                    <a href="/admin/appointment" style="background: #0d6efd; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Back to Appointments</a>
                </p>
            </div>';
        } catch (\Exception $e) {
            return '<div style="font-family: Arial; padding: 20px; background: #fee; border: 2px solid #c33; border-radius: 8px; margin: 20px;">
                <h3 style="color: #c33;">❌ Email Failed</h3>
                <p><strong>Error:</strong> ' . $e->getMessage() . '</p>
                <hr>
                <p><strong>Common Solutions:</strong></p>
                <ul>
                    <li>Run: <code>php artisan config:clear</code></li>
                    <li>Check your .env file for MAIL_* settings</li>
                    <li>Make sure patient email exists: ' . ($patient->email ?: 'NO EMAIL') . '</li>
                    <li>Check <code>storage/logs/laravel.log</code> for details</li>
                </ul>
            </div>';
        }
    })->name('admin.test-email');

    //Logout Route
    Route::post('/logout', [AuthController::class,'logout'])->name('logout');
    Route::post('/staff/logout', [StaffAuthController::class,'logout'])->name('staff.logout');
});


Route::get('/patient/calendar', [CalendarController::class, 'index'])->name('patient-calendar'  );
Route::get('/patient/profile', [PatientProfileController::class, 'index'])->name('patient-profile');
Route::get('/patient/record', [PatientRecord::class, 'index'])->name('patient-record');
Route::get('/patient/announcement', [AnnouncementController::class, 'index'])->name('patient-announcement');
Route::get('/staff/dashboard',[StaffDashboard::class, 'index'])->name('staff-dashboard');
