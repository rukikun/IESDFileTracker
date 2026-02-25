<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        // Get the first user
        $user = User::first();
        
        if (!$user) {
            $this->command->error('No user found! Please run user seeder first.');
            return;
        }

        // Clear existing notifications
        Notification::where('user_id', $user->id)->delete();
        
        $this->command->info('Creating notifications for user: ' . $user->name);

        // Create test notifications
        $notifications = [
            [
                'user_id' => $user->id,
                'type' => 'data_edit',
                'title' => 'Document Updated',
                'message' => 'Q4 2024 Business Review has been updated in Presentations',
                'is_read' => false,
                'created_at' => now()->subMinutes(5),
                'updated_at' => now()->subMinutes(5),
            ],
            [
                'user_id' => $user->id,
                'type' => 'data_edit',
                'title' => 'Document Added',
                'message' => 'Marketing Strategy 2024 has been added to Marketing',
                'is_read' => false,
                'created_at' => now()->subMinutes(15),
                'updated_at' => now()->subMinutes(15),
            ],
            [
                'user_id' => $user->id,
                'type' => 'data_edit',
                'title' => 'Document Deleted',
                'message' => 'Old Report 2023 has been deleted from Reports',
                'is_read' => false,
                'created_at' => now()->subMinutes(30),
                'updated_at' => now()->subMinutes(30),
            ],
            [
                'user_id' => $user->id,
                'type' => 'user_login',
                'title' => 'User Login',
                'message' => 'You have successfully logged into the system',
                'is_read' => false,
                'created_at' => now()->subHours(1),
                'updated_at' => now()->subHours(1),
            ],
            [
                'user_id' => $user->id,
                'type' => 'user_logout',
                'title' => 'User Logout',
                'message' => 'You have logged out from the system',
                'is_read' => false,
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(2),
            ],
            [
                'user_id' => $user->id,
                'type' => 'data_edit',
                'title' => 'Document Updated',
                'message' => 'Financial Report Q1 has been updated in Finance',
                'is_read' => false,
                'created_at' => now()->subHours(3),
                'updated_at' => now()->subHours(3),
            ],
            [
                'user_id' => $user->id,
                'type' => 'data_edit',
                'title' => 'Document Added',
                'message' => 'Project Timeline has been added to Projects',
                'is_read' => false,
                'created_at' => now()->subHours(4),
                'updated_at' => now()->subHours(4),
            ],
            [
                'user_id' => $user->id,
                'type' => 'user_login',
                'title' => 'User Login',
                'message' => 'You have successfully logged into the system',
                'is_read' => false,
                'created_at' => now()->subHours(5),
                'updated_at' => now()->subHours(5),
            ],
        ];

        // Insert notifications
        foreach ($notifications as $notification) {
            Notification::create($notification);
            $this->command->info("✓ Created: {$notification['title']} ({$notification['type']})");
        }

        $this->command->info('✅ Notification seeding completed!');
        $this->command->info("📊 Total notifications created: " . count($notifications));
        $this->command->info("🔔 Unread count: " . Notification::where('user_id', $user->id)->where('is_read', false)->count());
    }
}
