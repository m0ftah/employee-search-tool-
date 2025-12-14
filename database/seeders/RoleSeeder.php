<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles for the application
        $roles = [
            'super_admin',
            'admin',
            'hr',
            'HR', // Keep uppercase version for backward compatibility
            'candidate',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(
                ['name' => $roleName, 'guard_name' => 'web'],
                ['name' => $roleName, 'guard_name' => 'web']
            );
        }

        $this->command->info('Roles created successfully!');
        $this->command->info('Created roles: ' . implode(', ', $roles));
    }
}

