<?php

namespace Database\Factories;

use App\Models\Template;
use Illuminate\Database\Eloquent\Factories\Factory;

class TemplateFactory extends Factory
{
    protected $model = Template::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Professional', 'Modern', 'Classic', 'Creative']) . ' Template',
            'description' => $this->faker->paragraph(),
            'content' => [
                'layout' => 'single-column',
                'sections' => [
                    [
                        'type' => 'header',
                        'fields' => ['name', 'title', 'contact']
                    ],
                    [
                        'type' => 'body',
                        'fields' => ['summary', 'experience', 'education', 'skills']
                    ]
                ]
            ],
            'styling' => [
                'font_family' => $this->faker->randomElement(['Arial, sans-serif', 'Times New Roman, serif', 'Helvetica, sans-serif']),
                'font_size' => $this->faker->randomElement(['10px', '11px', '12px']),
                'line_height' => $this->faker->randomElement(['1.4', '1.5', '1.6']),
                'colors' => [
                    'text' => '#000000',
                    'headers' => $this->faker->randomElement(['#000000', '#333333', '#2563eb']),
                    'lines' => '#e5e7eb'
                ],
                'margins' => $this->faker->randomElement(['0.75in', '1in', '0.5in']),
                'section_spacing' => $this->faker->randomElement(['16px', '20px', '24px'])
            ],
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
