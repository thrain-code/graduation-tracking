<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ProdiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index'])->name('landing.page');
Route::get('/get-statistics', [LandingPageController::class, 'getStatistics']);

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Prodi Routes
    Route::prefix('prodi')->name('prodi.')->group(function () {
        Route::get('/', [ProdiController::class, 'index'])->name('index');
        Route::post('/', [ProdiController::class, 'store'])->name('store');
        Route::delete('/{id}', [ProdiController::class, 'destroy'])->name('delete');
    });

    // Alumni Routes
    Route::prefix('alumni')->name('alumni.')->group(function () {
        Route::get('/', [AlumniController::class, 'index'])->name('index');
        Route::get('/create', [AlumniController::class, 'create'])->name('create');
        Route::post('/', [AlumniController::class, 'store'])->name('store');
        Route::get('/{id}', [AlumniController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AlumniController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AlumniController::class, 'update'])->name('update');
        Route::delete('/{id}', [AlumniController::class, 'destroy'])->name('destroy');
        Route::post('/import', [AlumniController::class, 'import'])->name('import');
        Route::get('/export', [AlumniController::class, 'export'])->name('export');
    });

    // Admin Routes
    Route::post('/admin/admins', [AdminController::class, 'store'])->name('dashboard.admin.add');
    Route::delete('/admin/admins/{id}', [AdminController::class, 'delete'])->name('dashboard.admin.delete');
});