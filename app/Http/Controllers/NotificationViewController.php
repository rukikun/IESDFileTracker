<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationViewController extends Controller
{
    public function showNotifications()
    {
        // Get notifications for the authenticated user
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        $unreadCount = Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();
        
        // Return a partial view that can be included
        return view('components.notification-dropdown-content', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }
}
