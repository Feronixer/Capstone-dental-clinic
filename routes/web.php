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

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.forgot');
Route::post('/forgot-password', [AuthController::class, 'sendResetCode'])->name('password.reset.send');
Route::get('/reset-password/verify', [AuthController::class, 'showResetVerifyForm'])->name('password.reset.verify');
Route::post('/reset-password/verify', [AuthController::class, 'verifyResetCode'])->name('password.reset.verify');
Route::get('/reset-password', [AuthController::class, 'showResetForm'])->name('password.reset.form');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');

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
    Route::get('/admin/content-management', [ContentManagementController::class,'index'])->name('admin-content-management');
    Route::get('/admin/post-procedural', [PostProceduralController::class,'index'])->name('admin-post-procedural');
    Route::get('/admin/toothtalk', [ToothTalkController::class,'index'])->name('admin-toothtalk');
    Route::get('/admin/notifications', [AdminNotificationController::class,'index'])->name('admin-notification');
    Route::get('/admin/profile', [ProfileController::class,'index'])->name('admin-profile');

    //Logout Route
    Route::post('/logout', [AuthController::class,'logout'])->name('logout');
});


Route::get('/patient/calendar', [CalendarController::class, 'index'])->name('patient-calendar'  );
Route::get('/patient/profile', [PatientProfileController::class, 'index'])->name('patient-profile');
Route::get('/patient/record', [PatientRecord::class, 'index'])->name('patient-record');
Route::get('/patient/announcement', [AnnouncementController::class, 'index'])->name('patient-announcement');
Route::get('/staff/dashboard',[StaffDashboard::class, 'index'])->name('staff-dashboard');
