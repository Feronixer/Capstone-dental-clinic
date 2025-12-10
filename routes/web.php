
<?php

use App\Http\Controllers\Authentication\AuthController;
use App\Http\Controllers\Authentication\StaffAuthController;
use App\Http\Controllers\Authentication\AdminAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AccountManagementController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\ContentManagementController;
use App\Http\Controllers\Admin\PostProceduralController;
use App\Http\Controllers\Admin\ToothTalkController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Staff\PatientRecordAccessController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;
use App\Http\Controllers\Patient\CalendarController;
use App\Http\Controllers\Patient\ProfileController as PatientProfileController;
use App\Http\Controllers\Patient\PatientRecord;
use App\Http\Controllers\Patient\AnnouncementController;
use App\Http\Controllers\Patient\FeedbackController;
use App\Http\Controllers\Staff\StaffDashboard;
use App\Http\Controllers\Staff\PostProceduralController as StaffPostProceduralController;
use App\Http\Controllers\Staff\AccountManagementController as StaffAccountManagementController;
use Illuminate\Support\Facades\Route;



Route::get("/", [HomeController::class,"showHomePage"])->name("home");
Route::get('/about-us', function() {
    $chatbotSetting = App\Models\ChatbotSetting::first() ?? App\Models\ChatbotSetting::create([
        'enabled' => true,
        'welcome_message' => '',
        'quick_intents' => [],
    ]);
    $chatbotFaqs = App\Models\ChatbotFaq::where('is_active', true)
        ->orderBy('order')
        ->get(['question', 'answer']);

    return view('about-us', compact('chatbotSetting', 'chatbotFaqs'));
})->name('about-us');
Route::get('/announcements', [HomeController::class,"showAnnouncement"])->name('announcements');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class,'login']);
Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('password.change')->middleware('auth');
Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.change.submit')->middleware('auth');
Route::get('/register', [AuthController::class,'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class,'register']);

// Patient Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.forgot');
Route::post('/forgot-password', [AuthController::class, 'sendResetCode'])->name('password.reset.send');
Route::get('/reset-password/verify', [AuthController::class, 'showResetVerifyForm'])->name('password.reset.verify');
Route::post('/reset-password/verify', [AuthController::class, 'verifyResetCode'])->name('password.reset.verify.submit');
Route::get('/reset-password', [AuthController::class, 'showResetForm'])->name('password.reset.form');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');

// Admin Authentication Routes
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::get('/admin/change-password', [AdminAuthController::class, 'showChangePasswordForm'])->name('admin.password.change')->middleware('auth:admin');
Route::post('/admin/change-password', [AdminAuthController::class, 'changePassword'])->name('admin.password.change.submit')->middleware('auth:admin');
Route::get('/admin/forgot-password', [AdminAuthController::class, 'showForgotPasswordForm'])->name('admin.password.forgot');
Route::post('/admin/forgot-password', [AdminAuthController::class, 'sendResetCode'])->name('admin.password.reset.send');
Route::get('/admin/reset-password/verify', [AdminAuthController::class, 'showResetVerifyForm'])->name('admin.password.reset.verify');
Route::post('/admin/reset-password/verify', [AdminAuthController::class, 'verifyResetCode'])->name('admin.password.reset.verify.submit');
Route::get('/admin/reset-password', [AdminAuthController::class, 'showResetForm'])->name('admin.password.reset.form');
Route::post('/admin/reset-password', [AdminAuthController::class, 'resetPassword'])->name('admin.password.reset');

// Staff Authentication Routes
Route::get('/staff/login', [StaffAuthController::class, 'showLoginForm'])->name('staff.login');
Route::post('/staff/login', [StaffAuthController::class, 'login'])->name('staff.login.submit');
Route::get('/staff/change-password', [StaffAuthController::class, 'showChangePasswordForm'])->name('staff.password.change')->middleware('auth:staff');
Route::post('/staff/change-password', [StaffAuthController::class, 'changePassword'])->name('staff.password.change.submit')->middleware('auth:staff');
Route::get('/staff/forgot-password', [StaffAuthController::class, 'showForgotPasswordForm'])->name('staff.password.forgot');
Route::post('/staff/forgot-password', [StaffAuthController::class, 'sendResetCode'])->name('staff.password.reset.send');
Route::get('/staff/reset-password/verify', [StaffAuthController::class, 'showResetVerifyForm'])->name('staff.password.reset.verify');
Route::post('/staff/reset-password/verify', [StaffAuthController::class, 'verifyResetCode'])->name('staff.password.reset.verify.submit');
Route::get('/staff/reset-password', [StaffAuthController::class, 'showResetForm'])->name('staff.password.reset.form');
Route::post('/staff/reset-password', [StaffAuthController::class, 'resetPassword'])->name('staff.password.reset');

// Broadcast Events Route (accessible by admin, staff, and patients)
Route::middleware(['auth:admin,staff,web'])->group(function(): void{
    Route::get('/broadcasting/events', [App\Http\Controllers\BroadcastController::class, 'events'])->name('broadcasting.events');
});

// Admin Routes - Only accessible by admin guard (role_id = 1)
Route::middleware(['auth:admin', 'detect.mobile', 'restrict.admin.mobile'])->group(function(): void{
    //Admin Routes
    Route::post('/admin/verify-inactivity-password', [AdminAuthController::class, 'verifyInactivityPassword'])->name('admin.verify-inactivity-password');
    Route::get('/admin/dashboard', [AdminDashboardController::class,'index'])->name('admin-dashboard');
    Route::get('/admin/services', [AdminDashboardController::class,'services'])->name('admin-services');

    Route::get('/admin/account-management', [AccountManagementController::class,'index'])->name('admin-account-management');
    Route::post('/admin/account-management', [AccountManagementController::class,'store']);
    Route::post('/admin/account-management/check-duplicates', [AccountManagementController::class,'checkDuplicates'])->name('admin-account-management.check-duplicates');
    Route::put('/admin/account-management/users/{user}', [AccountManagementController::class,'update'])->name('users.update');
    Route::get('/admin/account-management/users/{id}', [AccountManagementController::class, 'show'])->name('users.show');
    Route::delete('/admin/account-management/users/{id}', [AccountManagementController::class, 'destroy'])->name('users.delete');
    Route::post('/admin/account-management/users/change-password/{id}', [AccountManagementController::class,'changePasswword']);
    Route::post('/admin/account-management/{id}/reveal-email', [AccountManagementController::class,'revealEmail'])->name('admin-account-management.reveal-email');
    Route::post('/admin/account-management/verify-password', [AccountManagementController::class,'verifyPassword'])->name('admin-account-management.verify-password');

    // Staff Access Control Routes
    Route::get('/admin/staff-access-control', [App\Http\Controllers\Admin\StaffAccessControlController::class, 'index'])->name('admin-staff-access-control');
    Route::post('/admin/staff-access-control/verify-password', [App\Http\Controllers\Admin\StaffAccessControlController::class, 'verifyPassword'])->name('admin-staff-access-control.verify-password');
    Route::put('/admin/staff-access-control/{staffId}', [App\Http\Controllers\Admin\StaffAccessControlController::class, 'update'])->name('admin-staff-access-control.update');
    Route::get('/admin/staff-access-control/{staffId}', [App\Http\Controllers\Admin\StaffAccessControlController::class, 'show'])->name('admin-staff-access-control.show');


    Route::get('/admin/appointment', [AppointmentController::class,'index'])->name('admin-appointment');
    Route::get('/admin/appointment/table', [AppointmentController::class,'table'])->name('admin-appointment.table');
    Route::get('/admin/appointment/appointments', [AppointmentController::class,'getAppointments'])->name('admin-appointment.get');
    Route::get('/admin/appointment/calendar-data', [AppointmentController::class,'calendarData'])->name('admin-appointment.calendar-data');
    Route::get('/admin/appointment/server-time', [AppointmentController::class,'getServerTime'])->name('admin-appointment.server-time');
    Route::post('/admin/appointment', [AppointmentController::class,'store'])->name('admin-appointment.store');
    Route::get('/admin/appointment/{id}', [AppointmentController::class,'show'])->name('admin-appointment.show');
    Route::put('/admin/appointment/{id}', [AppointmentController::class,'update'])->name('admin-appointment.update');
    Route::post('/admin/appointment/{id}/delete', [AppointmentController::class,'destroy'])->name('admin-appointment.destroy');
    Route::post('/admin/appointment/{id}/status', [AppointmentController::class,'updateStatus'])->name('admin-appointment.status');
    Route::get('/admin/appointment/search/patients', [AppointmentController::class,'searchPatients'])->name('admin-appointment.search-patients');
    Route::get('/admin/appointment/export/excel', [AppointmentController::class,'exportExcel'])->name('admin-appointment.export-excel');

    // Blocked Time Routes
    Route::post('/admin/blocked-time', [App\Http\Controllers\Admin\BlockedTimeController::class, 'store'])->name('admin-blocked-time.store');
    Route::put('/admin/blocked-time/{id}', [App\Http\Controllers\Admin\BlockedTimeController::class, 'update'])->name('admin-blocked-time.update');
    Route::post('/admin/blocked-time/{id}/delete', [App\Http\Controllers\Admin\BlockedTimeController::class, 'destroy'])->name('admin-blocked-time.destroy');
    Route::get('/admin/blocked-time/future/clinic-closed/count', [App\Http\Controllers\Admin\BlockedTimeController::class, 'getFutureClinicClosedCount'])->name('admin-blocked-time.clinic-closed.count');
    Route::get('/admin/blocked-time/future/clinic-closed/dates', [App\Http\Controllers\Admin\BlockedTimeController::class, 'getFutureClinicClosedDates'])->name('admin-blocked-time.clinic-closed.dates');
    Route::post('/admin/blocked-time/future/clinic-closed/clear', [App\Http\Controllers\Admin\BlockedTimeController::class, 'clearFutureClinicClosed'])->name('admin-blocked-time.clinic-closed.clear');
    Route::post('/admin/blocked-time/future/clinic-closed/clear-specific', [App\Http\Controllers\Admin\BlockedTimeController::class, 'clearSpecificClinicClosed'])->name('admin-blocked-time.clinic-closed.clear-specific');
    Route::get('/admin/blocked-time/future/block-off-time/count', [App\Http\Controllers\Admin\BlockedTimeController::class, 'getFutureBlockOffTimeCount'])->name('admin-blocked-time.block-off-time.count');
    Route::get('/admin/blocked-time/future/block-off-time/dates', [App\Http\Controllers\Admin\BlockedTimeController::class, 'getFutureBlockOffTimeDates'])->name('admin-blocked-time.block-off-time.dates');
    Route::post('/admin/blocked-time/future/block-off-time/clear', [App\Http\Controllers\Admin\BlockedTimeController::class, 'clearFutureBlockOffTime'])->name('admin-blocked-time.block-off-time.clear');
    Route::post('/admin/blocked-time/future/block-off-time/clear-specific', [App\Http\Controllers\Admin\BlockedTimeController::class, 'clearSpecificBlockOffTime'])->name('admin-blocked-time.block-off-time.clear-specific');
      Route::get('/admin/content-management', [ContentManagementController::class,'index'])->name('admin-content-management');
      Route::get('/admin/announcement-archives', [ContentManagementController::class,'announcementArchives'])->name('admin-announcement-archives');
      Route::delete('/admin/announcement-archives/{id}', [ContentManagementController::class,'deleteArchive'])->name('admin-announcement-archives.delete');
      Route::post('/admin/content-management/announcement', [ContentManagementController::class,'updateAnnouncement'])->name('admin-content-management.announcement.update');
      Route::post('/admin/content-management/announcement/new', [ContentManagementController::class,'createNewAnnouncement'])->name('admin-content-management.announcement.create');
      Route::post('/admin/content-management/ticker', [ContentManagementController::class,'updateTicker'])->name('admin-content-management.ticker.update');
      Route::post('/admin/content-management/service', [ContentManagementController::class,'storeService'])->name('admin-content-management.service.store');
    Route::put('/admin/content-management/service/{id}', [ContentManagementController::class,'updateService'])->name('admin-content-management.service.update');
    Route::delete('/admin/content-management/service/{id}', [ContentManagementController::class,'destroyService'])->name('admin-content-management.service.destroy');
    Route::post('/admin/content-management/event', [ContentManagementController::class,'storeEvent'])->name('admin-content-management.event.store');
    Route::put('/admin/content-management/event/{id}', [ContentManagementController::class,'updateEvent'])->name('admin-content-management.event.update');
    Route::delete('/admin/content-management/event/{id}', [ContentManagementController::class,'destroyEvent'])->name('admin-content-management.event.destroy');
    Route::post('/admin/content-management/mail-template/{type}', [ContentManagementController::class,'updateMailTemplate'])->name('admin-content-management.mail-template.update');
    Route::get('/admin/content-management/patients-with-appointments', [ContentManagementController::class,'getPatientsWithAppointments'])->name('admin-content-management.patients-with-appointments');
    Route::get('/admin/content-management/patient-appointments/{patientId}', [ContentManagementController::class,'getPatientAppointments'])->name('admin-content-management.patient-appointments');
    Route::post('/admin/content-management/send-patient-email', [ContentManagementController::class,'sendPatientEmail'])->name('admin-content-management.send-patient-email');
    Route::get('/admin/content-management/patients-by-situation', [ContentManagementController::class,'getPatientsBySituation'])->name('admin-content-management.patients-by-situation');
    Route::post('/admin/content-management/send-bulk-email', [ContentManagementController::class,'sendBulkEmail'])->name('admin-content-management.send-bulk-email');
    Route::get('/admin/post-procedural', [PostProceduralController::class,'index'])->name('admin-post-procedural');
    Route::get('/admin/post-procedural/records', [PostProceduralController::class,'getRecords']);
    Route::get('/admin/post-procedural/patient-record-by-user/{userId}', [PostProceduralController::class,'getPatientRecordByUser']);
    Route::get('/admin/post-procedural/patient-record/{recordId}', [PostProceduralController::class,'getPatientRecord']);
    Route::post('/admin/post-procedural/patient-record/store', [PostProceduralController::class,'storePatientRecord']);
    Route::delete('/admin/post-procedural/patient-record/{id}', [PostProceduralController::class,'destroyPatientRecord']);
    Route::get('/admin/post-procedural/search-patients', [PostProceduralController::class,'searchPatients']);
    Route::post('/admin/post-procedural/send-to-patient', [PostProceduralController::class,'sendToPatient']);
    Route::get('/admin/post-procedural/patient-history/{id}', [PostProceduralController::class,'getPatientHistory']);
    Route::post('/admin/post-procedural/patient-history', [PostProceduralController::class,'storePatientHistory']);
    Route::put('/admin/post-procedural/patient-history/{id}', [PostProceduralController::class,'updatePatientHistory']);
    Route::delete('/admin/post-procedural/patient-history/{id}', [PostProceduralController::class,'destroyPatientHistory']);
    Route::get('/admin/post-procedural/progress-notes/{id}', [PostProceduralController::class,'getProgressNotes']);
    Route::post('/admin/post-procedural/progress-notes', [PostProceduralController::class,'storeProgressNote']);
    Route::put('/admin/post-procedural/progress-notes/{id}', [PostProceduralController::class,'updateProgressNote']);
    Route::post('/admin/post-procedural/store-progress-notes', [PostProceduralController::class,'storeProgressNotes']);
    Route::delete('/admin/post-procedural/progress-notes/{id}', [PostProceduralController::class,'destroyProgressNote']);
    Route::get('/admin/post-procedural/progress-notes/{recordId}/download', [PostProceduralController::class,'downloadProgressNotes']);
    Route::post('/admin/post-procedural/verify-password', [PostProceduralController::class,'verifyPassword'])->name('admin-post-procedural.verify-password');
    Route::get('/admin/toothtalk', [ToothTalkController::class,'index'])->name('admin-toothtalk');
    Route::post('/admin/toothtalk/settings', [ToothTalkController::class,'saveSettings'])->name('admin-toothtalk.settings.save');
    Route::post('/admin/toothtalk/faq', [ToothTalkController::class,'storeFaq'])->name('admin-toothtalk.faq.store');
    Route::put('/admin/toothtalk/faq/{id}', [ToothTalkController::class,'updateFaq'])->name('admin-toothtalk.faq.update');
    Route::delete('/admin/toothtalk/faq/{id}', [ToothTalkController::class,'destroyFaq'])->name('admin-toothtalk.faq.delete');

    Route::get('/admin/notifications', [AdminNotificationController::class,'index'])->name('admin-notification');
    Route::get('/admin/notifications/appointments-for-date', [AdminNotificationController::class,'getAppointmentsForDate'])->name('admin-notification.appointments-for-date');
    Route::post('/admin/notifications/approve/{id}', [AdminNotificationController::class,'approveRequest'])->name('admin-notification.approve');
    Route::post('/admin/notifications/deny/{id}', [AdminNotificationController::class,'denyRequest'])->name('admin-notification.deny');
    Route::get('/admin/profile', [ProfileController::class,'index'])->name('admin-profile');
    Route::post('/admin/profile/update', [ProfileController::class,'update'])->name('admin-profile.update');
    Route::post('/admin/profile/update-picture', [ProfileController::class,'updateProfilePicture'])->name('admin-profile.update-picture');
    Route::post('/admin/profile/update-password', [ProfileController::class,'updatePassword'])->name('admin-profile.update-password');

    // Admin Activity Logs Routes
    Route::get('/admin/activity-logs', [\App\Http\Controllers\Admin\ActivityLogController::class,'index'])->name('admin-activity-logs');
    Route::get('/admin/activity-logs/data', [\App\Http\Controllers\Admin\ActivityLogController::class,'getLogs'])->name('admin-activity-logs.data');
    Route::get('/admin/activity-logs/{id}', [\App\Http\Controllers\Admin\ActivityLogController::class,'show'])->name('admin-activity-logs.show');

    // Admin Chat Routes
    Route::get('/admin/chat', [App\Http\Controllers\Admin\ChatController::class, 'index'])->name('admin-chat');
    Route::get('/admin/chat/conversations', [App\Http\Controllers\Admin\ChatController::class, 'getConversations'])->name('admin-chat.conversations');
    Route::get('/admin/chat/conversations/{id}/messages', [App\Http\Controllers\Admin\ChatController::class, 'getMessages'])->name('admin-chat.messages');
    Route::post('/admin/chat/conversations/{id}/send', [App\Http\Controllers\Admin\ChatController::class, 'sendMessage'])->name('admin-chat.send');
    Route::put('/admin/chat/conversations/{id}/status', [App\Http\Controllers\Admin\ChatController::class, 'updateStatus'])->name('admin-chat.update-status');
    Route::delete('/admin/chat/conversations/{id}', [App\Http\Controllers\Admin\ChatController::class, 'deleteConversation'])->name('admin-chat.delete');
    Route::get('/admin/chat/unread-count', [App\Http\Controllers\Admin\ChatController::class, 'getUnreadCount'])->name('admin-chat.unread-count');
    Route::post('/admin/chat/toggle-online-status', [App\Http\Controllers\Admin\ChatController::class, 'toggleOnlineStatus'])->name('admin-chat.toggle-online-status');
    Route::get('/admin/chat/online-status', [App\Http\Controllers\Admin\ChatController::class, 'getOnlineStatus'])->name('admin-chat.online-status');
    Route::post('/admin/chat/patients/{patient}/toggle', [App\Http\Controllers\Admin\ChatController::class, 'togglePatientChat'])->name('admin-chat.patient.toggle');
    Route::post('/admin/chat/toggle-censorship', [App\Http\Controllers\Admin\ChatController::class, 'toggleCensorship'])->name('admin-chat.toggle-censorship');
    Route::get('/admin/chat/censorship-status', [App\Http\Controllers\Admin\ChatController::class, 'getCensorshipStatus'])->name('admin-chat.censorship-status');
    Route::get('/admin/chat/blocklist', [App\Http\Controllers\Admin\ChatController::class, 'getBlocklist'])->name('admin-chat.blocklist.index');
    Route::post('/admin/chat/blocklist', [App\Http\Controllers\Admin\ChatController::class, 'addBlocklistWord'])->name('admin-chat.blocklist.store');
    Route::post('/admin/chat/blocklist/save', [App\Http\Controllers\Admin\ChatController::class, 'saveBlocklist'])->name('admin-chat.blocklist.save');
    Route::delete('/admin/chat/blocklist/{word}', [App\Http\Controllers\Admin\ChatController::class, 'removeBlocklistWord'])->name('admin-chat.blocklist.destroy');
    Route::get('/admin/chat/requests', [App\Http\Controllers\Admin\ChatController::class, 'getAccessRequests'])->name('admin-chat.requests');

    // Admin Feedback Routes
    Route::get('/admin/feedback', [App\Http\Controllers\Admin\FeedbackController::class, 'index'])->name('admin-feedback');

    // Admin Logout Route
    Route::post('/admin/logout', [AdminAuthController::class,'logout'])->name('admin.logout');

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
});

// Staff Routes - Only accessible by staff guard (role_id = 2)
Route::middleware(['auth:staff', \App\Http\Middleware\LogStaffActivity::class])->group(function(): void{
    // Staff Password Verification Route
    Route::post('/staff/verify-inactivity-password', [StaffAuthController::class, 'verifyInactivityPassword'])->name('staff.verify-inactivity-password');
    // Staff Patient Record Access Routes (Staff Only)
    Route::get('/staff/patient-records', [PatientRecordAccessController::class,'index'])->name('staff-patient-records');
    Route::get('/staff/patient-records/search', [PatientRecordAccessController::class,'searchPatients'])->name('staff-patient-records.search');
    Route::get('/staff/patient-records/{id}', [PatientRecordAccessController::class,'getPatientDetails'])->name('staff-patient-records.details');
    Route::get('/staff/patient-records/{id}/export', [PatientRecordAccessController::class,'exportPatientRecord'])->name('staff-patient-records.export');

    // Staff Post-Procedural Routes
    Route::get('/staff/post-procedural', [StaffPostProceduralController::class,'index'])->name('staff-post-procedural');
    Route::get('/staff/post-procedural/records', [StaffPostProceduralController::class,'getRecords']);
    Route::get('/staff/post-procedural/patient-record-by-user/{userId}', [StaffPostProceduralController::class,'getPatientRecordByUser']);
    Route::get('/staff/post-procedural/patient-record/{recordId}', [StaffPostProceduralController::class,'getPatientRecord']);
    Route::post('/staff/post-procedural/patient-record/store', [StaffPostProceduralController::class,'storePatientRecord']);
    Route::delete('/staff/post-procedural/patient-record/{id}', [StaffPostProceduralController::class,'destroyPatientRecord']);
    Route::get('/staff/post-procedural/search-patients', [StaffPostProceduralController::class,'searchPatients']);
    Route::post('/staff/post-procedural/send-to-patient', [StaffPostProceduralController::class,'sendToPatient']);
    Route::get('/staff/post-procedural/patient-history/{id}', [StaffPostProceduralController::class,'getPatientHistory']);
    Route::post('/staff/post-procedural/patient-history', [StaffPostProceduralController::class,'storePatientHistory']);
    Route::put('/staff/post-procedural/patient-history/{id}', [StaffPostProceduralController::class,'updatePatientHistory']);
    Route::delete('/staff/post-procedural/patient-history/{id}', [StaffPostProceduralController::class,'destroyPatientHistory']);
    Route::get('/staff/post-procedural/progress-notes/{id}', [StaffPostProceduralController::class,'getProgressNotes']);
    Route::post('/staff/post-procedural/progress-notes', [StaffPostProceduralController::class,'storeProgressNote']);
    Route::put('/staff/post-procedural/progress-notes/{id}', [StaffPostProceduralController::class,'updateProgressNote']);
    Route::post('/staff/post-procedural/store-progress-notes', [StaffPostProceduralController::class,'storeProgressNotes']);
    Route::delete('/staff/post-procedural/progress-notes/{id}', [StaffPostProceduralController::class,'destroyProgressNote']);
    Route::get('/staff/post-procedural/progress-notes/{recordId}/download', [StaffPostProceduralController::class,'downloadProgressNotes']);
    Route::post('/staff/post-procedural/verify-password', [StaffPostProceduralController::class,'verifyPassword'])->name('staff-post-procedural.verify-password');

    Route::get('/staff/dashboard',[StaffDashboard::class, 'index'])->name('staff-dashboard');
    Route::get('/staff/services',[StaffDashboard::class, 'services'])->name('staff-services');

    // Staff ToothTalk Routes
    Route::get('/staff/toothtalk', [App\Http\Controllers\Staff\ToothTalkController::class, 'index'])->name('staff-toothtalk');
    Route::post('/staff/toothtalk/settings', [App\Http\Controllers\Staff\ToothTalkController::class,'saveSettings'])->name('staff-toothtalk.settings.save');
    Route::post('/staff/toothtalk/faq', [App\Http\Controllers\Staff\ToothTalkController::class,'storeFaq'])->name('staff-toothtalk.faq.store');
    Route::put('/staff/toothtalk/faq/{id}', [App\Http\Controllers\Staff\ToothTalkController::class,'updateFaq'])->name('staff-toothtalk.faq.update');
    Route::delete('/staff/toothtalk/faq/{id}', [App\Http\Controllers\Staff\ToothTalkController::class,'destroyFaq'])->name('staff-toothtalk.faq.delete');

    // Staff Account Management Routes (Patient accounts only)
    Route::get('/staff/account-management', [StaffAccountManagementController::class,'index'])->name('staff-account-management');
    Route::post('/staff/account-management', [StaffAccountManagementController::class,'store']);
    Route::post('/staff/account-management/check-duplicates', [StaffAccountManagementController::class,'checkDuplicates'])->name('staff-account-management.check-duplicates');
    Route::put('/staff/account-management/users/{user}', [StaffAccountManagementController::class,'update'])->name('staff.users.update');
    Route::get('/staff/account-management/users/{id}', [StaffAccountManagementController::class, 'show'])->name('staff.users.show');
    Route::delete('/staff/account-management/users/{id}', [StaffAccountManagementController::class, 'destroy'])->name('staff.users.delete');
    Route::post('/staff/account-management/users/change-password/{id}', [StaffAccountManagementController::class,'changePasswword']);
    Route::post('/staff/account-management/{id}/reveal-email', [StaffAccountManagementController::class,'revealEmail'])->name('staff-account-management.reveal-email');
    Route::post('/staff/account-management/verify-password', [StaffAccountManagementController::class,'verifyPassword'])->name('staff-account-management.verify-password');

    // Staff Appointment Routes (No delete permission)
    Route::get('/staff/appointment', [App\Http\Controllers\Staff\AppointmentController::class,'index'])->name('staff-appointment');
    Route::get('/staff/appointment/table', [App\Http\Controllers\Staff\AppointmentController::class,'table'])->name('staff-appointment.table');
    Route::get('/staff/appointment/appointments', [App\Http\Controllers\Staff\AppointmentController::class,'getAppointments'])->name('staff-appointment.get');
    Route::get('/staff/appointment/calendar-data', [App\Http\Controllers\Staff\AppointmentController::class,'calendarData'])->name('staff-appointment.calendar-data');
    Route::get('/staff/appointment/server-time', [App\Http\Controllers\Staff\AppointmentController::class,'getServerTime'])->name('staff-appointment.server-time');
    Route::post('/staff/appointment', [App\Http\Controllers\Staff\AppointmentController::class,'store'])->name('staff-appointment.store');
    Route::get('/staff/appointment/{id}', [App\Http\Controllers\Staff\AppointmentController::class,'show'])->name('staff-appointment.show');
    Route::put('/staff/appointment/{id}', [App\Http\Controllers\Staff\AppointmentController::class,'update'])->name('staff-appointment.update');
    Route::post('/staff/appointment/{id}/status', [App\Http\Controllers\Staff\AppointmentController::class,'updateStatus'])->name('staff-appointment.status');
    Route::get('/staff/appointment/search/patients', [App\Http\Controllers\Staff\AppointmentController::class,'searchPatients'])->name('staff-appointment.search-patients');
    Route::get('/staff/appointment/export/excel', [App\Http\Controllers\Staff\AppointmentController::class,'exportExcel'])->name('staff-appointment.export-excel');

    // Staff Blocked Time Routes
    Route::post('/staff/blocked-time', [App\Http\Controllers\Staff\BlockedTimeController::class, 'store'])->name('staff-blocked-time.store');
    Route::put('/staff/blocked-time/{id}', [App\Http\Controllers\Staff\BlockedTimeController::class, 'update'])->name('staff-blocked-time.update');
    Route::post('/staff/blocked-time/{id}/delete', [App\Http\Controllers\Staff\BlockedTimeController::class, 'destroy'])->name('staff-blocked-time.destroy');
    Route::get('/staff/blocked-time/future/clinic-closed/count', [App\Http\Controllers\Staff\BlockedTimeController::class, 'getFutureClinicClosedCount'])->name('staff-blocked-time.clinic-closed.count');
    Route::get('/staff/blocked-time/future/clinic-closed/dates', [App\Http\Controllers\Staff\BlockedTimeController::class, 'getFutureClinicClosedDates'])->name('staff-blocked-time.clinic-closed.dates');
    Route::post('/staff/blocked-time/future/clinic-closed/clear', [App\Http\Controllers\Staff\BlockedTimeController::class, 'clearFutureClinicClosed'])->name('staff-blocked-time.clinic-closed.clear');
    Route::post('/staff/blocked-time/future/clinic-closed/clear-specific', [App\Http\Controllers\Staff\BlockedTimeController::class, 'clearSpecificClinicClosed'])->name('staff-blocked-time.clinic-closed.clear-specific');
    Route::get('/staff/blocked-time/future/block-off-time/count', [App\Http\Controllers\Staff\BlockedTimeController::class, 'getFutureBlockOffTimeCount'])->name('staff-blocked-time.block-off-time.count');
    Route::get('/staff/blocked-time/future/block-off-time/dates', [App\Http\Controllers\Staff\BlockedTimeController::class, 'getFutureBlockOffTimeDates'])->name('staff-blocked-time.block-off-time.dates');
    Route::post('/staff/blocked-time/future/block-off-time/clear', [App\Http\Controllers\Staff\BlockedTimeController::class, 'clearFutureBlockOffTime'])->name('staff-blocked-time.block-off-time.clear');
    Route::post('/staff/blocked-time/future/block-off-time/clear-specific', [App\Http\Controllers\Staff\BlockedTimeController::class, 'clearSpecificBlockOffTime'])->name('staff-blocked-time.block-off-time.clear-specific');

      // Staff Content Management Routes (No delete permission for services)
      Route::get('/staff/content-management', [App\Http\Controllers\Staff\ContentManagementController::class,'index'])->name('staff-content-management');
      Route::get('/staff/announcement-archives', [App\Http\Controllers\Staff\ContentManagementController::class,'announcementArchives'])->name('staff-announcement-archives');
      Route::delete('/staff/announcement-archives/{id}', [App\Http\Controllers\Staff\ContentManagementController::class,'deleteArchive'])->name('staff-announcement-archives.delete');
      Route::post('/staff/content-management/announcement', [App\Http\Controllers\Staff\ContentManagementController::class,'updateAnnouncement'])->name('staff-content-management.announcement.update');
      Route::post('/staff/content-management/announcement/new', [App\Http\Controllers\Staff\ContentManagementController::class,'createNewAnnouncement'])->name('staff-content-management.announcement.create');
      Route::post('/staff/content-management/ticker', [App\Http\Controllers\Staff\ContentManagementController::class,'updateTicker'])->name('staff-content-management.ticker.update');
      Route::post('/staff/content-management/service', [App\Http\Controllers\Staff\ContentManagementController::class,'storeService'])->name('staff-content-management.service.store');
    Route::put('/staff/content-management/service/{id}', [App\Http\Controllers\Staff\ContentManagementController::class,'updateService'])->name('staff-content-management.service.update');
    Route::delete('/staff/content-management/service/{id}', [App\Http\Controllers\Staff\ContentManagementController::class,'destroyService'])->name('staff-content-management.service.destroy');
    Route::post('/staff/content-management/event', [App\Http\Controllers\Staff\ContentManagementController::class,'storeEvent'])->name('staff-content-management.event.store');
    Route::put('/staff/content-management/event/{id}', [App\Http\Controllers\Staff\ContentManagementController::class,'updateEvent'])->name('staff-content-management.event.update');
    Route::delete('/staff/content-management/event/{id}', [App\Http\Controllers\Staff\ContentManagementController::class,'destroyEvent'])->name('staff-content-management.event.destroy');
    Route::post('/staff/content-management/mail-template/{type}', [App\Http\Controllers\Staff\ContentManagementController::class,'updateMailTemplate'])->name('staff-content-management.mail-template.update');
    Route::get('/staff/content-management/patients-with-appointments', [App\Http\Controllers\Staff\ContentManagementController::class,'getPatientsWithAppointments'])->name('staff-content-management.patients-with-appointments');
    Route::get('/staff/content-management/patient-appointments/{patientId}', [App\Http\Controllers\Staff\ContentManagementController::class,'getPatientAppointments'])->name('staff-content-management.patient-appointments');
    Route::post('/staff/content-management/send-patient-email', [App\Http\Controllers\Staff\ContentManagementController::class,'sendPatientEmail'])->name('staff-content-management.send-patient-email');
    Route::get('/staff/content-management/patients-by-situation', [App\Http\Controllers\Staff\ContentManagementController::class,'getPatientsBySituation'])->name('staff-content-management.patients-by-situation');
    Route::post('/staff/content-management/send-bulk-email', [App\Http\Controllers\Staff\ContentManagementController::class,'sendBulkEmail'])->name('staff-content-management.send-bulk-email');

    // Staff Profile Routes
    Route::get('/staff/profile', [App\Http\Controllers\Staff\ProfileController::class,'index'])->name('staff-profile');
    Route::post('/staff/profile/update', [App\Http\Controllers\Staff\ProfileController::class,'update'])->name('staff-profile.update');
    Route::post('/staff/profile/update-picture', [App\Http\Controllers\Staff\ProfileController::class,'updateProfilePicture'])->name('staff-profile.update-picture');
    Route::post('/staff/profile/update-password', [App\Http\Controllers\Staff\ProfileController::class,'updatePassword'])->name('staff-profile.update-password');

    // Staff Notification Routes
    Route::get('/staff/notifications', [App\Http\Controllers\Staff\NotificationController::class,'index'])->name('staff-notification');
    Route::get('/staff/notifications/appointments-for-date', [App\Http\Controllers\Staff\NotificationController::class,'getAppointmentsForDate'])->name('staff-notification.appointments-for-date');
    Route::post('/staff/notifications/approve/{id}', [App\Http\Controllers\Staff\NotificationController::class,'approveRequest'])->name('staff-notification.approve');
    Route::post('/staff/notifications/deny/{id}', [App\Http\Controllers\Staff\NotificationController::class,'denyRequest'])->name('staff-notification.deny');

    // Staff Chat Routes
    Route::get('/staff/chat', [App\Http\Controllers\Staff\ChatController::class, 'index'])->name('staff-chat');
    Route::get('/staff/chat/conversations', [App\Http\Controllers\Staff\ChatController::class, 'getConversations'])->name('staff-chat.conversations');
    Route::get('/staff/chat/conversations/{id}/messages', [App\Http\Controllers\Staff\ChatController::class, 'getMessages'])->name('staff-chat.messages');
    Route::post('/staff/chat/conversations/{id}/send', [App\Http\Controllers\Staff\ChatController::class, 'sendMessage'])->name('staff-chat.send');
    Route::put('/staff/chat/conversations/{id}/status', [App\Http\Controllers\Staff\ChatController::class, 'updateStatus'])->name('staff-chat.update-status');
    Route::get('/staff/chat/unread-count', [App\Http\Controllers\Staff\ChatController::class, 'getUnreadCount'])->name('staff-chat.unread-count');
    Route::post('/staff/chat/toggle-online-status', [App\Http\Controllers\Staff\ChatController::class, 'toggleOnlineStatus'])->name('staff-chat.toggle-online-status');
    Route::get('/staff/chat/online-status', [App\Http\Controllers\Staff\ChatController::class, 'getOnlineStatus'])->name('staff-chat.online-status');
    Route::post('/staff/chat/patients/{patient}/toggle', [App\Http\Controllers\Staff\ChatController::class, 'togglePatientChat'])->name('staff-chat.patient.toggle');
    Route::post('/staff/chat/toggle-censorship', [App\Http\Controllers\Staff\ChatController::class, 'toggleCensorship'])->name('staff-chat.toggle-censorship');
    Route::get('/staff/chat/censorship-status', [App\Http\Controllers\Staff\ChatController::class, 'getCensorshipStatus'])->name('staff-chat.censorship-status');
    Route::get('/staff/chat/blocklist', [App\Http\Controllers\Staff\ChatController::class, 'getBlocklist'])->name('staff-chat.blocklist.index');
    Route::post('/staff/chat/blocklist', [App\Http\Controllers\Staff\ChatController::class, 'addBlocklistWord'])->name('staff-chat.blocklist.store');
    Route::post('/staff/chat/blocklist/save', [App\Http\Controllers\Staff\ChatController::class, 'saveBlocklist'])->name('staff-chat.blocklist.save');
    Route::delete('/staff/chat/blocklist/{word}', [App\Http\Controllers\Staff\ChatController::class, 'removeBlocklistWord'])->name('staff-chat.blocklist.destroy');

    // Staff Feedback Routes
    Route::get('/staff/feedback', [App\Http\Controllers\Staff\FeedbackController::class, 'index'])->name('staff-feedback');

    // Staff Logout Route
    Route::post('/staff/logout', [StaffAuthController::class,'logout'])->name('staff.logout');
});

// Patient Routes - Only accessible by web guard (role_id = 3)
Route::middleware(['auth:web'])->group(function(): void{
    Route::get('/patient/dashboard', [PatientDashboardController::class, 'index'])->name('patient-dashboard');
    Route::get('/patient/home', [PatientDashboardController::class, 'index'])->name('patient-home');
    Route::get('/patient/calendar', [CalendarController::class, 'index'])->name('patient-calendar');
    Route::get('/patient/calendar/server-time', [CalendarController::class, 'getServerTime'])->name('patient-calendar.server-time');
    Route::get('/patient/calendar/poll', [CalendarController::class, 'poll'])->name('patient-calendar.poll');
    Route::post('/patient/calendar/submit-request', [CalendarController::class, 'submitRequest'])->name('patient-calendar.submit-request');
    Route::get('/patient/profile', [PatientProfileController::class, 'index'])->name('patient-profile');
    Route::get('/patient/record', [PatientRecord::class, 'index'])->name('patient-record');
    // These routes must come BEFORE /patient/record/{id} to avoid route conflicts
    Route::post('/patient/record/verify-password', [PatientRecord::class, 'verifyPassword'])->name('patient-record.verify-password');
    Route::get('/patient/record/check-access', [PatientRecord::class, 'checkAccess'])->name('patient-record.check-access');
    Route::get('/patient/record/{id}', [PatientRecord::class, 'show'])->name('patient-record.show');
    Route::get('/patient/record/{id}/download', [PatientRecord::class, 'download'])->name('patient-record.download');
    Route::get('/patient/records/all', [PatientRecord::class, 'getRecords'])->name('patient-records.all');
    Route::post('/patient/profile/update', [PatientProfileController::class, 'update'])->name('patient-profile.update');
    Route::get('/patient/history/{id}', [PatientRecord::class, 'showHistory'])->name('patient-history.show');
    Route::get('/patient/history/{id}/download', [PatientRecord::class, 'downloadHistory'])->name('patient-history.download');
    Route::get('/patient/progress-note/{id}', [PatientRecord::class, 'showProgressNote'])->name('patient-progress-note.show');
    Route::get('/patient/progress-note/{id}/download', [PatientRecord::class, 'downloadProgressNote'])->name('patient-progress-note.download');
    Route::get('/patient/record/{recordId}/progress-notes/download', [PatientRecord::class, 'downloadAllProgressNotes'])->name('patient-progress-notes-all.download');
    Route::get('/patient/announcement', [AnnouncementController::class, 'index'])->name('patient-announcement');
    Route::get('/patient/about', function() {
        $chatbotSetting = App\Models\ChatbotSetting::first() ?? App\Models\ChatbotSetting::create([
            'enabled' => true,
            'welcome_message' => 'Hi! I\'m the ToothTalk Assistant. How can I help you today?',
            'quick_intents' => [
                ['label' => 'Clinic Hours', 'value' => 'What are your clinic hours?'],
                ['label' => 'Book Appointment', 'value' => 'How do I book an appointment?'],
                ['label' => 'Services', 'value' => 'What dental services do you offer?'],
            ],
        ]);

        $chatbotFaqs = App\Models\ChatbotFaq::where('is_active', true)
            ->orderBy('order')
            ->get(['question', 'answer']);

        return view('patient.aboutUs', compact('chatbotSetting', 'chatbotFaqs'));
    })->name('patient-about');

    Route::get('/patient/development-team', function() {
        $chatbotSetting = App\Models\ChatbotSetting::first() ?? App\Models\ChatbotSetting::create([
            'enabled' => true,
            'welcome_message' => 'Hi! I\'m the ToothTalk Assistant. How can I help you today?',
            'quick_intents' => [
                ['label' => 'Clinic Hours', 'value' => 'What are your clinic hours?'],
                ['label' => 'Book Appointment', 'value' => 'How do I book an appointment?'],
                ['label' => 'Services', 'value' => 'What dental services do you offer?'],
            ],
        ]);

        $chatbotFaqs = App\Models\ChatbotFaq::where('is_active', true)
            ->orderBy('order')
            ->get(['question', 'answer']);

        return view('patient.developmentTeam', compact('chatbotSetting', 'chatbotFaqs'));
    })->name('patient-development-team');

    // Patient Notification Routes
    Route::get('/patient/notifications', [App\Http\Controllers\Patient\NotificationController::class, 'index'])->name('patient-notifications');
    Route::get('/patient/notifications/recent', [App\Http\Controllers\Patient\NotificationController::class, 'getRecent'])->name('patient-notifications.recent');
    Route::get('/patient/notifications/poll', [App\Http\Controllers\Patient\NotificationController::class, 'poll'])->name('patient-notifications.poll');
    Route::get('/patient/notifications/{id}', [App\Http\Controllers\Patient\NotificationController::class, 'show'])->name('patient-notifications.show');
    Route::get('/patient/notifications/unread-count', [App\Http\Controllers\Patient\NotificationController::class, 'getUnreadCount'])->name('patient-notifications.unread-count');
    Route::post('/patient/notifications/{id}/read', [App\Http\Controllers\Patient\NotificationController::class, 'markAsRead'])->name('patient-notifications.mark-read');
    Route::post('/patient/notifications/{id}/unread', [App\Http\Controllers\Patient\NotificationController::class, 'markAsUnread'])->name('patient-notifications.mark-unread');
    Route::post('/patient/notifications/mark-all-read', [App\Http\Controllers\Patient\NotificationController::class, 'markAllAsRead'])->name('patient-notifications.mark-all-read');
    Route::delete('/patient/notifications/{id}', [App\Http\Controllers\Patient\NotificationController::class, 'destroy'])->name('patient-notifications.destroy');
    Route::post('/patient/notifications/clear-read', [App\Http\Controllers\Patient\NotificationController::class, 'clearRead'])->name('patient-notifications.clear-read');

    // Patient Feedback Routes
    Route::get('/patient/feedback', [FeedbackController::class, 'index'])->name('patient-feedback');
    Route::get('/patient/feedback/appointments', [FeedbackController::class, 'getCompletedAppointments'])->name('patient-feedback.appointments');
    Route::get('/patient/feedback/debug', [FeedbackController::class, 'debugAppointments'])->name('patient-feedback.debug');
    Route::post('/patient/feedback/submit', [FeedbackController::class, 'submitFeedback'])->name('patient-feedback.submit');
    Route::get('/patient/feedback/history', [FeedbackController::class, 'getFeedbackHistory'])->name('patient-feedback.history');

    // Patient Chat Routes
    Route::get('/patient/chat/conversation', [App\Http\Controllers\Patient\ChatController::class, 'getConversation'])->name('patient-chat.conversation');
    Route::get('/patient/chat/messages', [App\Http\Controllers\Patient\ChatController::class, 'getMessages'])->name('patient-chat.messages');
    Route::post('/patient/chat/send', [App\Http\Controllers\Patient\ChatController::class, 'sendMessage'])->name('patient-chat.send');
    Route::get('/patient/chat/unread-count', [App\Http\Controllers\Patient\ChatController::class, 'getUnreadCount'])->name('patient-chat.unread-count');
    Route::get('/patient/chat/online-status', [App\Http\Controllers\Patient\ChatController::class, 'getOnlineStatus'])->name('patient-chat.online-status');
    Route::post('/patient/chat/enable-request', [App\Http\Controllers\Patient\ChatController::class, 'requestEnable'])->name('patient-chat.request-enable');

    // Patient Logout Route
    Route::post('/logout', [AuthController::class,'logout'])->name('logout');
    Route::post('/patient/logout', [AuthController::class,'logout'])->name('patient.logout');
});

// Public route for checking chat authentication
Route::get('/chat/check-auth', [App\Http\Controllers\Patient\ChatController::class, 'checkAuth'])->name('chat.check-auth');
// Public route for checking chat online status
Route::get('/chat/online-status', [App\Http\Controllers\Patient\ChatController::class, 'getOnlineStatus'])->name('chat.online-status');

