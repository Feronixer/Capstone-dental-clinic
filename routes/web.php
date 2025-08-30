<?php

use App\Http\Controllers\Authentication\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;
use Illuminate\Support\Facades\Route;



Route::get("/", [HomeController::class,"showHomePage"])->name("home");
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class,'login']);
Route::get('/register', [AuthController::class,'showRegisterForm'])->name(name: 'register');
Route::post('/register', [AuthController::class,'register']);
Route::get('/patient/dashboard', [PatientDashboardController::class,'index'])->name('patient-dashboard');
Route::get('/admin/dashboard', [AdminDashboardController::class,'index'])->name('admin-dashboard');

