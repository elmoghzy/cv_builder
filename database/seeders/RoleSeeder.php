<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            'manage users',
            'manage templates',
            'manage payments', 
            'view dashboard',
            'create cv',
            'download cv',
            'make payment',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        // Assign permissions to admin
        $adminRole->givePermissionTo([
            'manage users',
            'manage templates', 
            'manage payments',
            'view dashboard',
            'create cv',
            'download cv',
        ]);

        // Assign permissions to user
        $userRole->givePermissionTo([
            'create cv',
            'download cv',
            'make payment',
        ]);

        // Create default admin user if not exists
        $adminUser = User::where('email', 'admin@cvbuilder.com')->first();
        if (!$adminUser) {
            $adminUser = User::create([
                'name' => 'Admin User',
                'email' => 'admin@cvbuilder.com',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]);
            $adminUser->assignRole('admin');
        }
    }
}
