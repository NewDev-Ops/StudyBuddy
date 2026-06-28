<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\RevisionSessionController;
use App\Http\Controllers\MarkController;
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
    Route::post('/complete', [OnboardingController::class, 'complete'])->name('complete');
});

// Student routes
Route::middleware(['auth', 'onboarding'])->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');

    Route::post('/subjects', [OnboardingController::class, 'storeDashboardSubject'])->name('subjects.store');
    Route::delete('/subjects/{subject}', [OnboardingController::class, 'deleteSubject'])->name('subjects.destroy');

    Route::post('/revision-sessions', [RevisionSessionController::class, 'store'])->name('revision-sessions.store');
    Route::delete('/revision-sessions/{revisionSession}', [RevisionSessionController::class, 'destroy'])->name('revision-sessions.destroy');

    Route::post('/marks', [MarkController::class, 'store'])->name('marks.store');
    Route::delete('/marks/{mark}', [MarkController::class, 'destroy'])->name('marks.destroy');

    Route::get('/study-wrapped', [\App\Http\Controllers\StudyWrappedController::class, 'index'])->name('study-wrapped');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/peer-network', [ProfileController::class, 'togglePeerNetwork'])->name('profile.peer-network.toggle');
});

Route::middleware(['auth'])->get('/subjects/search', [OnboardingController::class, 'search'])->name('subjects.search');

// Admin routes
Route::middleware(['auth', 'onboarding', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/universities', [\App\Http\Controllers\AdminUniversityController::class, 'index'])->name('universities');
    Route::post('/universities', [\App\Http\Controllers\AdminUniversityController::class, 'store'])->name('universities.store');
    Route::patch('/universities/{university}', [\App\Http\Controllers\AdminUniversityController::class, 'update'])->name('universities.update');
    Route::delete('/universities/{university}', [\App\Http\Controllers\AdminUniversityController::class, 'destroy'])->name('universities.destroy');

    Route::get('/resources', [\App\Http\Controllers\AdminResourceController::class, 'index'])->name('resources');
    Route::post('/resources', [\App\Http\Controllers\AdminResourceController::class, 'store'])->name('resources.store');
    Route::patch('/resources/{resource}', [\App\Http\Controllers\AdminResourceController::class, 'update'])->name('resources.update');
    Route::delete('/resources/{resource}', [\App\Http\Controllers\AdminResourceController::class, 'destroy'])->name('resources.destroy');

    Route::get('/peer-network', [\App\Http\Controllers\AdminPeerNetworkController::class, 'index'])->name('peer-network');
    Route::patch('/peer-network/{user}/remove', [\App\Http\Controllers\AdminPeerNetworkController::class, 'remove'])->name('peer-network.remove');

    Route::get('/users', [\App\Http\Controllers\AdminUserController::class, 'index'])->name('users');
    Route::post('/users/{user}/promote', [\App\Http\Controllers\AdminUserController::class, 'promote'])->name('users.promote');
    Route::post('/users/{user}/demote', [\App\Http\Controllers\AdminUserController::class, 'demote'])->name('users.demote');
});

require __DIR__.'/auth.php';
