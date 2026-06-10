<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\OnboardingController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', function () {
    return view('welcome');
});

// Google Auth
Route::post('/auth/google', [GoogleAuthController::class, 'handleGoogleToken']);

// Onboarding routes
Route::middleware(['auth', 'onboarding'])->prefix('onboarding')->name('onboarding.')->group(function () {
    Route::get('/step1', [OnboardingController::class, 'step1'])->name('step1');
    Route::post('/step1', [OnboardingController::class, 'storeStep1'])->name('store1');
    Route::get('/step2', [OnboardingController::class, 'step2'])->name('step2');
    Route::post('/step2/subject', [OnboardingController::class, 'storeSubject'])->name('storeSubject');
    Route::post('/step2/suggested', [OnboardingController::class, 'addSuggestedSubject'])->name('addSuggested');
    Route::delete('/step2/subject/{subject}', [OnboardingController::class, 'deleteSubject'])->name('deleteSubject');
    Route::get('/complete', [OnboardingController::class, 'complete'])->name('complete');
});

// Student routes
Route::middleware(['auth', 'onboarding'])->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

    Route::post('/subjects', [OnboardingController::class, 'storeDashboardSubject'])->name('subjects.store');
    Route::delete('/subjects/{subject}', [OnboardingController::class, 'deleteSubject'])->name('subjects.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', 'onboarding', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

require __DIR__.'/auth.php';
