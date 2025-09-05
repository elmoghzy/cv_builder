<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Template;
use App\Models\User;

class QuickFix extends Command
{
    protected $signature = 'app:quick-fix';
    protected $description = 'Quick fix for CV creation issues';

    public function handle()
    {
        $this->info('🔧 Running quick fix...');

        try {
            // 1. Create template if not exists
            $template = Template::first();
            if (!$template) {
                $template = Template::create([
                    'name' => 'Professional Template',
                    'description' => 'A clean and professional CV template',
                    'structure' => [
                        'personal_info' => ['full_name', 'email', 'phone', 'address'],
                        'professional_summary' => 'text',
                        'work_experience' => [
                            'job_title', 'company', 'location', 'start_date', 'end_date', 'description'
                        ],
                        'education' => ['degree', 'institution', 'graduation_year'],
                        'skills' => ['skill_name']
                    ],
                    'is_active' => true,
                    'is_premium' => false,
                    'price' => 0,
                    'sort_order' => 1
                ]);
                $this->info('✅ Template created: ' . $template->name);
            } else {
                $this->info('✅ Template exists: ' . $template->name);
            }

            // 2. Create test user if not exists
            $testUser = User::where('email', 'test@test.com')->first();
            if (!$testUser) {
                $testUser = User::create([
                    'name' => 'Test User',
                    'email' => 'test@test.com',
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                ]);
                $this->info('✅ Test user created: test@test.com');
            } else {
                $this->info('✅ Test user exists: ' . $testUser->email);
            }

            // 3. Show debug info
            $this->info('📊 Database status:');
            $this->info('   - Templates: ' . Template::count());
            $this->info('   - Users: ' . User::count());
            $this->info('   - CVs: ' . \App\Models\Cv::count());

            $this->info('🎯 Test URLs:');
            $this->info('   - Debug page: http://localhost:8000/debug-cv');
            $this->info('   - CV Builder: http://localhost:8000/cv/builder');
            $this->info('   - Login: http://localhost:8000/login');

        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return 1;
        }

        $this->info('🎉 Quick fix completed!');
        return 0;
    }
}
