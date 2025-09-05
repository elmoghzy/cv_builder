<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class ListAdmins extends Command
{
    protected $signature = 'admin:list';
    protected $description = 'List all admin users';

    public function handle()
    {
        $adminRole = Role::where('name', 'admin')->first();
        
        if (!$adminRole) {
            $this->error('❌ Admin role not found. Run php artisan admin:setup first.');
            return 1;
        }

        $admins = User::role('admin')->get();
        
        if ($admins->isEmpty()) {
            $this->info('No admin users found.');
            return 0;
        }

        $this->info('Admin Users:');
        $this->table(
            ['ID', 'Name', 'Email', 'Created At'],
            $admins->map(function ($admin) {
                return [
                    $admin->id,
                    $admin->name,
                    $admin->email,
                    $admin->created_at->format('Y-m-d H:i:s')
                ];
            })->toArray()
        );

        return 0;
    }
}
