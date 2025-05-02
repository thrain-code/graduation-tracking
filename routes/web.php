<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\FormPublicController;
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
        // Main CRUD routes
        Route::get('/', [AlumniController::class, 'index'])->name('index');
        Route::get('/create', [AlumniController::class, 'create'])->name('create');
        Route::post('/', [AlumniController::class, 'store'])->name('store');
        Route::get('/{id}', [AlumniController::class, 'show'])->name('show')->where('id', '[0-9]+');
        Route::get('/{id}/edit', [AlumniController::class, 'edit'])->name('edit')->where('id', '[0-9]+');
        Route::put('/{id}', [AlumniController::class, 'update'])->name('update')->where('id', '[0-9]+');
        Route::delete('/{id}', [AlumniController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');

        // Import & Export routes
        Route::get('/import/form', [AlumniController::class, 'importForm'])->name('import.form');
        Route::post('/import/process', [AlumniController::class, 'importProcess'])->name('import');
        Route::get('/template/download', [AlumniController::class, 'downloadTemplate'])->name('template');
        Route::get('/export', [AlumniController::class, 'export'])->name('export');
    });

    // Admin Routes
    Route::post('/admin/admins', [AdminController::class, 'store'])->name('dashboard.admin.add');
    Route::delete('/admin/admins/{id}', [AdminController::class, 'delete'])->name('dashboard.admin.delete');

    // Forms management
    Route::prefix('admin/forms')->name('forms.')->group(function () {
        Route::get('/', [FormController::class, 'index'])->name('index');
        Route::get('/create', [FormController::class, 'create'])->name('create');
        Route::post('/', [FormController::class, 'store'])->name('store');
        Route::get('/{id}', [FormController::class, 'show'])->name('show')->where('id', '[0-9]+');
        Route::get('/{id}/edit', [FormController::class, 'edit'])->name('edit')->where('id', '[0-9]+');
        Route::put('/{id}', [FormController::class, 'update'])->name('update')->where('id', '[0-9]+');
        Route::delete('/{id}', [FormController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
        Route::get('/{id}/results', [FormController::class, 'results'])->name('results')->where('id', '[0-9]+');
        Route::post('/{id}/regenerate-slug', [FormController::class, 'regenerateSlug'])->name('regenerate-slug')->where('id', '[0-9]+');
    });
});

// Public form routes
Route::prefix('form')->name('form.')->group(function () {
    Route::get('/{slug}', [FormPublicController::class, 'show'])->name('show');
    Route::post('/{slug}', [FormPublicController::class, 'store'])->name('store');
    Route::get('/{slug}/thankyou', [FormPublicController::class, 'thankYou'])->name('thankyou');
    Route::get('/{slug}/result', [FormPublicController::class, 'results'])->name('results');
});
