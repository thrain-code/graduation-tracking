<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ProdiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index'])->name('landing.page');

// Auth Route
Route::get("/login", [AuthController::class, "showLoginForm"])->name("login.form");
Route::post("/login", [AuthController::class, "login"])->name("login");
Route::post("/logout", [AuthController::class, "logout"])->name("logout");

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Prodi
    Route::resource('/prodi', ProdiController::class);
});