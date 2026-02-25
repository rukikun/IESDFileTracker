<?php

use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationDataController;
use App\Http\Controllers\NotificationViewController;
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
    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::patch('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/data', [NotificationDataController::class, 'getNotifications'])->name('notifications.data');
    Route::get('/notifications/view', [NotificationViewController::class, 'showNotifications'])->name('notifications.view');
});

require __DIR__.'/auth.php';
