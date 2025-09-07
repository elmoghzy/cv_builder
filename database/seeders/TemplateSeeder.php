<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Template;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Professional Simple',
                'description' => 'A clean, ATS-compliant template perfect for professional positions. Uses standard Arial font with clear section headers.',
                'content' => json_encode([
                    'sections' => [
                        'personal_info' => [
                            'title' => 'Personal Information',
                            'fields' => ['full_name', 'email', 'phone', 'address', 'linkedin', 'website']
                        ],
                        'professional_summary' => [
                            'title' => 'Professional Summary',
                            'type' => 'textarea',
                            'max_chars' => 300
                        ],
                        'work_experience' => [
                            'title' => 'Work Experience',
                            'type' => 'repeatable',
                            'fields' => ['job_title', 'company', 'location', 'start_date', 'end_date', 'current', 'description']
                        ],
                        'education' => [
                            'title' => 'Education',
                            'type' => 'repeatable',
                            'fields' => ['degree', 'institution', 'location', 'graduation_date', 'gpa']
                        ],
                        'skills' => [
                            'title' => 'Skills',
                            'type' => 'tags',
                            'categories' => ['Technical Skills', 'Soft Skills', 'Languages']
                        ],
                        'certifications' => [
                            'title' => 'Certifications',
                            'type' => 'repeatable',
                            'fields' => ['name', 'issuer', 'date', 'credential_id']
                        ]
                    ],
                    'styling' => [
                        'font_family' => 'Arial, sans-serif',
                        'font_size' => '11px',
                        'line_height' => '1.4',
                        'margins' => '0.75in',
                        'section_spacing' => '16px',
                        'colors' => [
                            'text' => '#000000',
                            'headers' => '#000000',
                            'lines' => '#000000'
                        ]
                    ]
                ]),
                'is_active' => true,
                'is_premium' => false,
                'sort_order' => 1
            ],
            [
                'name' => 'Modern Clean',
                'description' => 'A modern yet ATS-friendly design with subtle formatting. Perfect for tech and creative industries while maintaining parsability.',
                'content' => json_encode([
                    'sections' => [
                        'personal_info' => [
                            'title' => 'Contact Information',
                            'fields' => ['full_name', 'email', 'phone', 'address', 'linkedin', 'github', 'portfolio']
                        ],
                        'objective' => [
                            'title' => 'Career Objective',
                            'type' => 'textarea',
                            'max_chars' => 250
                        ],
                        'work_experience' => [
                            'title' => 'Professional Experience',
                            'type' => 'repeatable',
                            'fields' => ['job_title', 'company', 'location', 'start_date', 'end_date', 'current', 'achievements']
                        ],
                        'education' => [
                            'title' => 'Education',
                            'type' => 'repeatable',
                            'fields' => ['degree', 'institution', 'location', 'graduation_date', 'honors']
                        ],
                        'technical_skills' => [
                            'title' => 'Technical Skills',
                            'type' => 'categorized_list',
                            'categories' => ['Programming Languages', 'Frameworks', 'Tools & Technologies', 'Databases']
                        ],
                        'projects' => [
                            'title' => 'Key Projects',
                            'type' => 'repeatable',
                            'fields' => ['project_name', 'description', 'technologies', 'duration', 'url']
                        ]
                    ],
                    'styling' => [
                        'font_family' => 'Times New Roman, serif',
                        'font_size' => '11px',
                        'line_height' => '1.3',
                        'margins' => '0.8in',
                        'section_spacing' => '14px',
                        'colors' => [
                            'text' => '#000000',
                            'headers' => '#000000',
                            'lines' => '#000000'
                        ]
                    ]
                ]),
                'is_active' => true,
                'is_premium' => false,
                'sort_order' => 2
            ]
            ,
            [
                'name' => 'Elegant Minimal',
                'description' => 'Minimal, ATS-safe layout with generous white space and clear hierarchy.',
                'content' => json_encode([
                    'sections' => [
                        'personal_info' => [
                            'title' => 'Personal Info',
                            'fields' => ['full_name', 'email', 'phone', 'address', 'linkedin', 'website']
                        ],
                        'professional_summary' => [
                            'title' => 'Summary',
                            'type' => 'textarea',
                            'max_chars' => 300
                        ],
                        'work_experience' => [
                            'title' => 'Experience',
                            'type' => 'repeatable',
                            'fields' => ['job_title', 'company', 'location', 'start_date', 'end_date', 'current', 'description']
                        ],
                        'education' => [
                            'title' => 'Education',
                            'type' => 'repeatable',
                            'fields' => ['degree', 'institution', 'location', 'graduation_date']
                        ],
                        'technical_skills' => [
                            'title' => 'Skills',
                            'type' => 'categorized_list',
                            'categories' => ['Languages', 'Frameworks', 'Tools']
                        ],
                    ],
                    'styling' => [
                        'font_family' => 'Calibri, Arial, sans-serif',
                        'font_size' => '11px',
                        'line_height' => '1.35',
                        'margins' => '0.8in',
                        'section_spacing' => '14px',
                        'colors' => [
                            'text' => '#111111',
                            'headers' => '#111111',
                            'lines' => '#333333'
                        ]
                    ]
                ]),
                'is_active' => true,
                'is_premium' => false,
                'sort_order' => 3
            ],
            [
                'name' => 'Tech Focus',
                'description' => 'Structured for software roles: emphasizes projects and technical stacks, still ATS-friendly.',
                'content' => json_encode([
                    'sections' => [
                        'personal_info' => [
                            'title' => 'Contact',
                            'fields' => ['full_name', 'email', 'phone', 'address', 'linkedin', 'github', 'portfolio']
                        ],
                        'projects' => [
                            'title' => 'Projects',
                            'type' => 'repeatable',
                            'fields' => ['project_name', 'description', 'technologies', 'duration', 'url']
                        ],
                        'work_experience' => [
                            'title' => 'Experience',
                            'type' => 'repeatable',
                            'fields' => ['job_title', 'company', 'location', 'start_date', 'end_date', 'current', 'achievements']
                        ],
                        'technical_skills' => [
                            'title' => 'Technical Skills',
                            'type' => 'categorized_list',
                            'categories' => ['Languages', 'Frameworks', 'Databases', 'Tools']
                        ],
                        'education' => [
                            'title' => 'Education',
                            'type' => 'repeatable',
                            'fields' => ['degree', 'institution', 'location', 'graduation_date']
                        ],
                    ],
                    'styling' => [
                        'font_family' => 'Inter, Arial, sans-serif',
                        'font_size' => '11px',
                        'line_height' => '1.35',
                        'margins' => '0.75in',
                        'section_spacing' => '12px',
                        'colors' => [
                            'text' => '#000000',
                            'headers' => '#000000',
                            'lines' => '#000000'
                        ]
                    ]
                ]),
                'is_active' => true,
                'is_premium' => false,
                'sort_order' => 4
            ],
            [
                'name' => 'Compact One-Page',
                'description' => 'A concise one-page template optimized for ATS parsing and quick scanning.',
                'content' => json_encode([
                    'sections' => [
                        'personal_info' => [
                            'title' => 'Contact',
                            'fields' => ['full_name', 'email', 'phone', 'linkedin']
                        ],
                        'professional_summary' => [
                            'title' => 'Summary',
                            'type' => 'textarea',
                            'max_chars' => 220
                        ],
                        'work_experience' => [
                            'title' => 'Experience',
                            'type' => 'repeatable',
                            'fields' => ['job_title', 'company', 'start_date', 'end_date', 'current', 'description']
                        ],
                        'education' => [
                            'title' => 'Education',
                            'type' => 'repeatable',
                            'fields' => ['degree', 'institution', 'graduation_date']
                        ],
                        'skills' => [
                            'title' => 'Skills',
                            'type' => 'tags',
                            'categories' => ['Core Skills']
                        ]
                    ],
                    'styling' => [
                        'font_family' => 'Helvetica, Arial, sans-serif',
                        'font_size' => '10.5px',
                        'line_height' => '1.3',
                        'margins' => '0.6in',
                        'section_spacing' => '12px',
                        'colors' => [
                            'text' => '#000000',
                            'headers' => '#000000',
                            'lines' => '#000000'
                        ]
                    ]
                ]),
                'is_active' => true,
                'is_premium' => false,
                'sort_order' => 5
            ]
        ];

        foreach ($templates as $template) {
            Template::firstOrCreate(
                ['name' => $template['name']],
                $template
            );
        }
    }
}
