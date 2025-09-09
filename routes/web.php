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
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class,'login']);
Route::get('/register', [AuthController::class,'showRegisterForm'])->name(name: 'register');
Route::post('/register', [AuthController::class,'register']);

Route::get('/admin/dashboard', [AdminDashboardController::class,'index'])->name('admin-dashboard');
Route::get('/dashboard', function () {return view('dashboard');});
Route::get('/patient/calendar', [CalendarController::class, 'index'])->name('patient-calendar'  );
Route::get('/patient/profile', [ProfileController::class, 'index'])->name('patient-profile');
Route::get('/patient/record', [PatientRecord::class, 'index'])->name('patient-record');
Route::get('/patient/announcement', [AnnouncementController::class, 'index'])->name('patient-announcement');
Route::get('/staff/dashboard',[StaffDashboard::class, 'index'])->name('staff-dashboard');
