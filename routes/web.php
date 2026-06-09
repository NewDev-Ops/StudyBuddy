<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\AdminDashboardController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', function () {
    return view('welcome');
});

// Google Auth
Route::post('/auth/google', [GoogleAuthController::class, 'handleGoogleToken']);

// Student routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

require __DIR__.'/auth.php';