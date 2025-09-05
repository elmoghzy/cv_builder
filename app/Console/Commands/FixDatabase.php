<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Template;

class FixDatabase extends Command
{
    protected $signature = 'app:fix-database';
    protected $description = 'Fix database issues and create test data';

    public function handle()
    {
        $this->info('Starting database fix...');

        try {
            // Make user_id nullable in cvs table
            $this->info('Making user_id nullable in cvs table...');
            DB::statement('ALTER TABLE cvs MODIFY user_id BIGINT UNSIGNED NULL');
            $this->info('✅ CVs table fixed');

            // Create test user if not exists
            $testUser = User::where('email', 'test@test.com')->first();
            if (!$testUser) {
                $testUser = User::create([
                    'name' => 'Test User',
                    'email' => 'test@test.com',
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                ]);
                $this->info('✅ Test user created: test@test.com / password');
            } else {
                $this->info('✅ Test user already exists');
            }

            // Create sample template if not exists
            $template = Template::first();
            if (!$template) {
                Template::create([
                    'name' => 'Professional Template',
                    'description' => 'A clean and professional CV template',
                    'structure' => [
                        'personal_info' => ['name', 'email', 'phone', 'address'],
                        'experience' => ['company', 'position', 'duration', 'description'],
                        'education' => ['degree', 'institution', 'year'],
                        'skills' => ['skill_name']
                    ],
                    'is_active' => true,
                ]);
                $this->info('✅ Sample template created');
            } else {
                $this->info('✅ Templates already exist');
            }

            $this->info('🎉 Database fix completed successfully!');
            $this->info('You can now:');
            $this->info('1. Run: php artisan serve');
            $this->info('2. Visit: http://localhost:8000');
            $this->info('3. Login with: test@test.com / password');

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
