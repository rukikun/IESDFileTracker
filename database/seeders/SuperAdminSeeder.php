<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if super admin already exists
        if (User::where('email', 'superadmin@filetracker.com')->exists()) {
            $this->command->info('Super admin user already exists.');
            return;
        }

        // Create super admin user
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@filetracker.com',
            'password' => Hash::make('password'), // Change this in production
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);

        $this->command->info('Super admin user created successfully.');
        $this->command->info('Email: superadmin@filetracker.com');
        $this->command->info('Password: password (please change in production)');
    }
}
