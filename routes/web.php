<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileTrackerController;

// Show landing page for guests, redirect authenticated users to dashboard
Route::get('/', [LandingPageController::class, 'index'])->name('landing');

Route::get('/home', function () {
    return view('welcome');
})->name('home');

// Redirect dashboard to filetracker
Route::get('/dashboard', function () {
    return redirect()->route('filetracker.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// File Tracker Routes (protected)
Route::get('/filetracker', [FileTrackerController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('filetracker.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
