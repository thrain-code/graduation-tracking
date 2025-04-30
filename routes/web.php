<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ProdiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index'])->name('landing.page');

// Auth Route
Route::get("/login", [AuthController::class, "showLoginForm"])->name("login.form");
Route::post("/login", [AuthController::class, "login"])->name("login");
Route::post("/logout", [AuthController::class, "logout"])->name("logout");

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Prodi
    Route::get('/prodi', [ProdiController::class, 'index'])->name('prodi.index');
    // CRUD Admin
    Route::post('/admin/admins', [AdminController::class, 'store'])->name('dashboard.admin.add');
    Route::delete('/admin/admins/{id}', [AdminController::class, 'delete'])->name('dashboard.admin.delete');
});