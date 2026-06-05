<?php

use App\Http\Controllers\Auth\LinkedInController;
use App\Http\Controllers\BadgeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Home / project listing
Route::get('/', [ProjectController::class, 'index'])->name('home');

// LinkedIn OAuth
Route::get('/auth/linkedin', [LinkedInController::class, 'redirect'])->name('auth.linkedin');
Route::get('/auth/linkedin/callback', [LinkedInController::class, 'callback'])->name('auth.linkedin.callback');
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('home');
})->name('logout');

// Projects
Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create')->middleware('auth');
Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store')->middleware('auth');
Route::get('/projects/{slug}', [ProjectController::class, 'show'])->name('projects.show');

// Badge (LinkedIn share)
Route::get('/badge', [BadgeController::class, 'show'])->name('badge.show')->middleware('auth');
Route::post('/badge/share', [BadgeController::class, 'share'])->name('badge.share')->middleware('auth');
Route::post('/badge/skip', function () {
    session(['badge_generated' => true]);

    return redirect()->route('home');
})->name('badge.skip')->middleware('auth');

// Profile
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit')->middleware('auth');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware('auth');
Route::get('/users/{user}', [ProfileController::class, 'show'])->name('profile.show');
