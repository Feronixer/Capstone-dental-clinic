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
use Illuminate\Support\Facades\Route;



Route::get("/", [HomeController::class,"showHomePage"])->name("home");
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class,'login']);
Route::get('/register', [AuthController::class,'showRegisterForm'])->name(name: 'register');
Route::post('/register', [AuthController::class,'register']);
Route::get('/patient/dashboard', [PatientDashboardController::class,'index'])->middleware('auth')->name('patient-dashboard');

Route::middleware(['auth'])->group(function(){
    //Admin Routes
    Route::get('/admin/dashboard', [AdminDashboardController::class,'index'])->name('admin-dashboard');

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

    //Logout Route
    Route::post('/logout', [AuthController::class,'logout'])->name('logout');
});
