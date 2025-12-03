<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'type' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Assign super_admin role if it exists
        try {
            if ($admin->wasRecentlyCreated) {
                $admin->assignRole('super_admin');
            } else {
                // Ensure existing admin has super_admin role
                if (!$admin->hasRole('super_admin')) {
                    $admin->assignRole('super_admin');
                }
            }
        } catch (\Exception $e) {
            // Role might not exist yet, that's okay
            $this->command->warn('Could not assign super_admin role. You can assign it manually later.');
        }

        $this->command->info('Admin user created!');
        $this->command->info('Email: admin@admin.com');
        $this->command->info('Password: password');
    }
}

