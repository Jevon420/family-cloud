<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndUserSeeder extends Seeder
{
    public function run()
    {
        // 1. Define roles
        $roles = [
            'Developer',
            'Global Admin',
            'Admin',
            'Family',
            'Guest',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // 2. Define granular permissions
        $permissions = [
            'access site',

            // Gallery
            'view all galleries',
            'view own galleries',
            'create galleries',
            'edit galleries',
            'delete galleries',

            // Photo
            'view all photos',
            'view own photos',
            'upload photos',
            'delete photos',

            // File
            'view all files',
            'view own files',
            'upload files',
            'delete files',

            // Folder
            'view all folders',
            'view own folders',
            'create folders',
            'delete folders',

            // Shared Access
            'share content',
            'view shared content',

            // Admin-level
            'manage users',
            'manage roles',
            'manage settings',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // 3. Assign permissions by role
        Role::findByName('Developer')->givePermissionTo(Permission::all());

        Role::findByName('Global Admin')->givePermissionTo(Permission::all());

        Role::findByName('Admin')->givePermissionTo([
            'access site',

            'view all galleries', 'create galleries', 'edit galleries', 'delete galleries',
            'view all photos', 'upload photos', 'delete photos',
            'view all files', 'upload files', 'delete files',
            'view all folders', 'create folders', 'delete folders',

            'share content', 'view shared content',
            'manage users', 'manage settings',
        ]);

        Role::findByName('Family')->givePermissionTo([
            'access site',

            'view own galleries', 'create galleries', 'edit galleries', 'delete galleries',
            'view own photos', 'upload photos', 'delete photos',
            'view own files', 'upload files', 'delete files',
            'view own folders', 'create folders', 'delete folders',

            'share content', 'view shared content',
        ]);

        Role::findByName('Guest')->givePermissionTo([
            'access site',
            'view shared content',
        ]);

        // 4. Create users and assign roles
        $users = [
            [
                'name' => 'Jevon Redhead',
                'email' => 'jevonredhead@boostbyte.dev',
                'password' => Hash::make('password123'),
                'role' => 'Developer',
            ],
            [
                'name' => 'Alyssa Aming-Redhead',
                'email' => 'alyssaaming@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'Global Admin',
            ],
            [
                'name' => 'Jevon Red',
                'email' => 'jevon_redhead@yahoo.com',
                'password' => Hash::make('password123'),
                'role' => 'Family',
            ],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                ['name' => $data['name'], 'password' => $data['password']]
            );

            $user->assignRole($data['role']);
        }
    }
}
