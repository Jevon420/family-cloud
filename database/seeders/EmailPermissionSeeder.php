<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EmailPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create permissions
        $permissions = [
            'manage-email-configurations',
            'manage-emails',
            'send-emails',
            'view-all-emails',
            'delete-emails',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $developerRole = Role::findByName('Developer');
        $globalAdminRole = Role::findByName('Global Admin');
        $adminRole = Role::findByName('Admin');

        // Developer gets all permissions
        $developerRole->givePermissionTo($permissions);

        // Global Admin gets all permissions
        $globalAdminRole->givePermissionTo($permissions);

        // Admin gets limited permissions
        $adminRole->givePermissionTo([
            'manage-emails',
            'send-emails',
            'view-all-emails',
        ]);
    }
}
