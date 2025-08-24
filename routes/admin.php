<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

// Admin authentication routes
Route::name('admin.')->group(function () {
    // Guest routes (not authenticated)
    Route::middleware('admin.guest')->group(function () {
        Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminController::class, 'login']);
    });

    // Admin authenticated routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
        Route::get('/projects', [AdminController::class, 'projects'])->name('projects');
        Route::get('/skills', [AdminController::class, 'skills'])->name('skills');
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    });
});