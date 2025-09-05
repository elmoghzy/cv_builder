<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SetupAdmin extends Command
{
    protected $signature = 'admin:setup {email?}';
    protected $description = 'Setup admin role and assign to user';

    public function handle()
    {
        $email = $this->argument('email') ?? $this->ask('Enter admin email');
        
        // Create admin role if doesn't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $this->info('✅ Admin role created/exists');

        // Create basic permissions
        $permissions = [
            'manage users',
            'manage cvs', 
            'manage payments',
            'manage templates',
            'view dashboard'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        $this->info('✅ Permissions created');

        // Assign all permissions to admin role
        $adminRole->syncPermissions(Permission::all());
        $this->info('✅ Permissions assigned to admin role');

        // Find user and assign admin role
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->assignRole('admin');
            $this->info("✅ User {$user->name} ({$email}) is now an admin");
        } else {
            $this->error("❌ User with email {$email} not found");
            return 1;
        }

        return 0;
    }
}
