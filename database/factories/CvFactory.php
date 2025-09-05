<?php

namespace Database\Factories;

use App\Models\Cv;
use App\Models\User;
use App\Models\Template;
use Illuminate\Database\Eloquent\Factories\Factory;

class CvFactory extends Factory
{
    protected $model = Cv::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'template_id' => Template::factory(),
            'title' => $this->faker->jobTitle . ' CV',
            'content' => [
                'personal_info' => [
                    'full_name' => $this->faker->name,
                    'email' => $this->faker->unique()->safeEmail,
                    'phone' => $this->faker->phoneNumber,
                    'address' => $this->faker->address,
                ],
                'professional_summary' => $this->faker->paragraph(3),
                'work_experience' => [
                    [
                        'job_title' => $this->faker->jobTitle,
                        'company' => $this->faker->company,
                        'start_date' => $this->faker->date('Y-m-d', '-2 years'),
                        'end_date' => $this->faker->date('Y-m-d', '-1 year'),
                        'current' => false,
                        'description' => $this->faker->paragraph(2),
                    ]
                ],
                'education' => [
                    [
                        'degree' => 'Bachelor of ' . $this->faker->randomElement(['Computer Science', 'Business', 'Engineering']),
                        'institution' => $this->faker->company . ' University',
                        'graduation_date' => $this->faker->date('Y-m-d', '-3 years'),
                    ]
                ],
                'technical_skills' => $this->faker->randomElements(['PHP', 'Laravel', 'JavaScript', 'React', 'Vue.js', 'Python', 'Java'], 3),
                'soft_skills' => $this->faker->randomElements(['Leadership', 'Communication', 'Problem Solving', 'Team Work'], 2),
            ],
            'is_paid' => false,
            'download_count' => $this->faker->numberBetween(0, 10),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
        ]);
    }
}
