<?php

use App\Http\Controllers\Authentication\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\AccountManagementController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\ContentManagementController;
use App\Http\Controllers\Admin\PostProceduralController;
use App\Http\Controllers\Admin\ToothTalkController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Patient\CalendarController;
use App\Http\Controllers\Patient\ProfileController as PatientProfileController;
use App\Http\Controllers\Patient\PatientRecord;
use App\Http\Controllers\Patient\AnnouncementController;
use App\Http\Controllers\Staff\StaffDashboard;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\Patient\PatientController;
use App\Http\Controllers\Admin\PatientRecordController;
use App\Http\Controllers\Admin\ProgressNoteController;
use App\Http\Controllers\Admin\PatientHistoryController;
use App\Http\Controllers\Patient\PatientDashboardController;
use App\Http\Controllers\Authentication\PasswordChangeController;
use App\Http\Controllers\Patient\ContentController as PatientContentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



/**
 * PUBLIC: Login / Register
 */
Route::get('/login',    [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login',   [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register',[AuthController::class, 'register']);

/**
 * AUTH-ONLY: Logout
 */
Route::middleware('auth')->post('/logout', [AuthController::class, 'logout'])->name('logout');

/**
 * ADMIN AREA (auth + role)
 */
Route::middleware(['auth','role:Admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin-dashboard');

    // ---- Move your existing admin routes inside this group ----
    Route::get('/admin/account-management', [AccountManagementController::class,'index'])->name('admin-account-management');
    Route::post('/admin/account-management', [AccountManagementController::class,'store']);
    Route::put('/admin/account-management/users/{user}', [AccountManagementController::class,'update'])->name('users.update');
    Route::get('/admin/account-management/users/{id}', [AccountManagementController::class, 'show'])->name('users.show');
    Route::delete('/admin/account-management/users/{id}', [AccountManagementController::class, 'destroy'])->name('users.delete');
    Route::post('/admin/account-management/users/change-password/{id}', [AccountManagementController::class,'changePasswword']);

    Route::get('/admin/appointment', [AppointmentController::class,'index'])->name('admin-appointment');
    Route::get('/admin/content-management', [ContentManagementController::class,'index'])->name('admin-content-management');
    Route::get('/admin/post-procedural', [PostProceduralController::class,'index'])->name('admin-post-procedural');
    Route::get('/admin/toothtalk', [ToothTalkController::class,'index'])->name('admin-toothtalk');
    Route::get('/admin/notifications', [AdminNotificationController::class,'index'])->name('admin-notification');
    Route::get('/admin/profile', [ProfileController::class,'index'])->name('admin-profile');

    // Appointments under admin
    Route::prefix('admin/appointments')->group(function () {
        Route::get('/', [AppointmentController::class, 'index'])->name('appointments.index');
        Route::post('/appointments/block', [AppointmentController::class, 'block'])->name('appointments.block');
        Route::get('/events', [AppointmentController::class, 'events'])->name('appointments.events');
        Route::post('/store', [AppointmentController::class, 'store'])->name('appointments.store');
        Route::put('/{id}', [AppointmentController::class, 'update'])->name('appointments.update');
        Route::delete('/{id}', [AppointmentController::class, 'destroy'])->name('appointments.delete');
        Route::get('/appointments/events', [AppointmentController::class, 'events']);
        Route::get('/appointments/blocks', [AppointmentController::class, 'blocks']);
        Route::post('/appointments/block', [AppointmentController::class, 'block'])->name('appointments.block');
        Route::get('/admin/appointments/events', [AppointmentController::class, 'events'])->name('admin.appointments.events');
    });

    // Patient records under admin
    Route::get('/admin/patientrecord', [PatientRecordController::class, 'index'])->name('admin-patientrecord');
    Route::get('/admin/progressnote', [ProgressNoteController::class, 'index'])->name('admin-progressnote');
    Route::get('/admin/patienthistory', [PatientHistoryController::class, 'index'])->name('admin-patienthistory');
});

/**
 * STAFF AREA (auth + role)
 */
Route::middleware(['auth','role:Staff'])->group(function () {
    Route::get('/staff/dashboard', [StaffDashboard::class, 'index'])->name('staff-dashboard');
    // Add more staff-only routes here…
});

/**
 * PATIENT AREA (auth + role + your custom password middleware)
 */
Route::middleware(['auth','role:Patient','force.change.password'])->group(function () {
    Route::get('/patient/dashboard', [PatientDashboardController::class, 'index'])->name('patient-dashboard');
    Route::get('/patient/calendar', [CalendarController::class, 'index'])->name('patient-calendar');
    Route::get('/patient/profile',  [PatientProfileController::class, 'index'])->name('patient-profile');
    Route::get('/patient/record',   [PatientRecord::class, 'index'])->name('patient-record');
    Route::get('/patient/announcement', [AnnouncementController::class, 'index'])->name('patient-announcement');
    Route::get('/patient/announcements', [PatientContentController::class, 'announcements'])->name('patient.announcements');
    Route::get('/patient/announcements/{announcement}', [PatientContentController::class, 'announcementShow']) ->name('patient.announcements.show');
    Route::get('/patient/services', [PatientContentController::class, 'services']) ->name('patient.services');
    Route::get('/patient/services/{service}', [PatientContentController::class, 'serviceShow']) ->name('patient.services.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/password/change',  [PasswordChangeController::class, 'form'])->name('password.change');
    Route::post('/password/change', [PasswordChangeController::class, 'update'])->name('password.change.submit');
});


// content management routes

Route::prefix('admin/content-management')->group(function () {
    Route::post('/announcement/add', [ContentManagementController::class, 'addAnnouncement'])
        ->name('announcement.add');
    Route::put('/announcement/update/{id}', [ContentManagementController::class, 'updateAnnouncement'])
        ->name('announcement.update');


    // Services CRUD
    Route::get('/services', [ContentManagementController::class, 'indexServices'])->name('admin-services');
    Route::post('/services', [ContentManagementController::class, 'storeService'])->name('services.store');
    Route::get('/services/{id}', [ContentManagementController::class, 'showService'])->name('services.show');
    Route::put('/services/{id}', [ContentManagementController::class, 'updateService'])->name('services.update');
    Route::delete('/services/{id}', [ContentManagementController::class, 'destroyService'])->name('services.delete');


    // Mail Settings
    Route::put('/mail/update/{id}', [ContentManagementController::class, 'updateMailSetting'])
        ->name('mail.update');
});


//appointment routes
Route::prefix('admin/appointments')->group(function () {
    Route::get('/', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::post('/appointments/block', [AppointmentController::class, 'block'])->name('appointments.block');
    Route::get('/events', [AppointmentController::class, 'events'])->name('appointments.events');
    Route::post('/store', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::put('/{id}', [AppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('/{id}', [AppointmentController::class, 'destroy'])->name('appointments.delete');
    Route::get('/appointments/events', [AppointmentController::class, 'events']);
    Route::get('/appointments/blocks', [AppointmentController::class, 'blocks']);
    Route::post('/appointments/block', [AppointmentController::class, 'block'])->name('appointments.block');
    Route::get('/admin/appointments/events', [App\Http\Controllers\Admin\AppointmentController::class, 'events'])->name('admin.appointments.events');

    // Fetch blocked times (used by frontend calendar / patient side)
    //Route::get('/appointments/blocked', [AppointmentController::class, 'getBlockedTimes'])->name('appointments.blocked');
});
// Patient Record Routes
Route::get('/admin/patientrecord', [PatientRecordController::class, 'index'])->name('admin-patientrecord');

// Progress Note Routes
Route::get('/admin/progressnote', [ProgressNoteController::class, 'index'])->name('admin-progressnote');

// Patient History Routes
Route::get('/admin/patienthistory', [PatientHistoryController::class, 'index'])->name('admin-patienthistory');

//patient management routes
