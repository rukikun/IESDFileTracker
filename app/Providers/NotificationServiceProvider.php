<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Listen for user login events
        Event::listen(Login::class, function (Login $event) {
            $user = $event->user;
            
            // Create login notification for all users except the logged-in user
            $this->createUserActivityNotification(
                $user->id,
                'user_login',
                'User Login',
                "{$user->name} has logged in to the system.",
                [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'user_email' => $user->email,
                    'action' => 'login'
                ]
            );
        });

        // Listen for user logout events
        Event::listen(Logout::class, function (Logout $event) {
            $user = $event->user;
            
            if ($user) {
                // Create logout notification for all users except the logged-out user
                $this->createUserActivityNotification(
                    $user->id,
                    'user_logout',
                    'User Logout',
                    "{$user->name} has logged out from the system.",
                    [
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'user_email' => $user->email,
                        'action' => 'logout'
                    ]
                );
            }
        });
    }

    /**
     * Create user activity notification for all relevant users
     */
    private function createUserActivityNotification($excludeUserId, $type, $title, $message, $data = null)
    {
        // Get all users except the one who performed the action
        $users = User::where('id', '!=', $excludeUserId)->get();

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => $data,
            ]);
        }
    }

    /**
     * Create data edit notification
     */
    public static function createDataEditNotification($userId, $documentTitle, $action, $data = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => 'data_edit',
            'title' => 'Document ' . ucfirst($action),
            'message' => "Document '{$documentTitle}' has been {$action}.",
            'data' => array_merge($data ?? [], [
                'document_title' => $documentTitle,
                'action' => $action
            ]),
        ]);
    }
}
