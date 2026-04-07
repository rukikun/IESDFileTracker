<?php

use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ArchiveController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileTrackerController;

// Show landing page for guests, redirect authenticated users to dashboard
Route::get('/', [LandingPageController::class, 'index'])->name('landing');

// Redirect dashboard to filetracker
Route::get('/dashboard', function () {
    return redirect()->route('filetracker.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// File Tracker Routes (protected)
Route::get('/filetracker', [FileTrackerController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('filetracker.index');

Route::middleware('auth')->group(function () {
    // Archive Routes (for super admin only)
    Route::get('/archives', [ArchiveController::class, 'index'])
        ->name('archives.index')
        ->middleware('superadmin');
    
    Route::get('/archives/{archive}', [ArchiveController::class, 'show'])
        ->name('archives.show')
        ->middleware('superadmin');
        
    Route::delete('/archives/{archive}', [ArchiveController::class, 'destroy'])
        ->name('archives.destroy')
        ->middleware('superadmin');
});

require __DIR__.'/auth.php';
