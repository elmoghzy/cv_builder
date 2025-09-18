<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Cv;
use App\Models\Template;
use App\Services\CvService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class CvGenerationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $template;
    protected $cvService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->template = Template::factory()->create([
            'name' => 'ATS Compliant Template',
            'is_active' => true,
            'content' => [
                'layout' => 'single-column',
                'sections' => [
                    'personal_info' => [
                        'type' => 'object',
                        'fields' => ['full_name', 'email', 'phone', 'address']
                    ],
                    'professional_summary' => [
                        'type' => 'textarea',
                        'max_chars' => 500
                    ],
                    'work_experience' => [
                        'type' => 'repeatable',
                        'fields' => ['job_title', 'company', 'description']
                    ],
                    'education' => [
                        'type' => 'repeatable', 
                        'fields' => ['degree', 'institution']
                    ],
                    'skills' => [
                        'type' => 'tags'
                    ]
                ]
            ],
            'styling' => [
                'font_family' => 'Arial, sans-serif',
                'font_size' => '11px',
                'colors' => [
                    'text' => '#000000',
                    'headers' => '#000000'
                ]
            ]
        ]);
        
        $this->cvService = app(CvService::class);
    }

    /** @test */
    public function user_can_create_cv_with_valid_data()
    {
        $this->actingAs($this->user);

        $cvData = [
            'title' => 'Software Engineer CV',
            'template_id' => $this->template->id,
            'personal_info' => [
                'full_name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '+201234567890',
                'address' => 'Cairo, Egypt'
            ],
            'professional_summary' => 'Experienced software engineer with 5+ years in web development.',
            'work_experience' => [
                [
                    'job_title' => 'Senior Developer',
                    'company' => 'Tech Corp',
                    'start_date' => '2022-01-01',
                    'current' => true,
                    'description' => 'Lead development of web applications using Laravel and React.'
                ]
            ],
            'education' => [
                [
                    'degree' => 'Bachelor of Computer Science',
                    'institution' => 'Cairo University',
                    'graduation_date' => '2019-06-01'
                ]
            ],
            'technical_skills' => ['PHP', 'Laravel', 'JavaScript', 'React']
        ];

        $response = $this->post(route('cv.store'), $cvData);

        $response->assertRedirect();
        $this->assertDatabaseHas('cvs', [
            'user_id' => $this->user->id,
            'title' => 'Software Engineer CV',
            'template_id' => $this->template->id,
            'status' => 'completed'
        ]);
    }

    /** @test */
    public function cv_validation_prevents_invalid_data()
    {
        $this->actingAs($this->user);

        // Missing required fields
        $invalidData = [
            'title' => '', // Empty title
            'template_id' => 999, // Non-existent template
            'personal_info' => [
                'full_name' => '',
                'email' => 'invalid-email'
            ]
        ];

        $response = $this->post(route('cv.store'), $invalidData);

        $response->assertSessionHasErrors(['title', 'template_id', 'personal_info.full_name', 'personal_info.email']);
    }

    /** @test */
    public function cv_service_validates_content_against_template()
    {
        $content = [
            'personal_info' => [
                'full_name' => 'John Doe',
                'email' => 'john@example.com'
            ]
        ];

        $result = $this->cvService->validateContent($content, $this->template);

        $this->assertTrue($result['valid']);
        $this->assertEmpty($result['errors']);
    }

    /** @test */
    public function cv_service_generates_pdf_successfully()
    {
        $cv = Cv::factory()->create([
            'user_id' => $this->user->id,
            'template_id' => $this->template->id,
            'content' => [
                'personal_info' => [
                    'full_name' => 'John Doe',
                    'email' => 'john@example.com',
                    'phone' => '+201234567890'
                ],
                'professional_summary' => 'Software engineer with expertise in web development.'
            ]
        ]);

        $pdfPath = $this->cvService->generatePdf($cv);

        $this->assertFileExists(storage_path('app/' . $pdfPath));
        $this->assertStringContainsString('.pdf', $pdfPath);
    }

    /** @test */
    public function user_can_update_existing_cv()
    {
        $this->actingAs($this->user);

        $cv = Cv::factory()->create([
            'user_id' => $this->user->id,
            'template_id' => $this->template->id,
            'title' => 'Original Title'
        ]);

        $updateData = [
            'title' => 'Updated CV Title',
            'template_id' => $this->template->id,
            'personal_info' => [
                'full_name' => 'Jane Doe',
                'email' => 'jane@example.com'
            ]
        ];

        $response = $this->put(route('cv.update', $cv), $updateData);

        // In testing, controller redirects to home to avoid Filament routes
        $response->assertRedirect('/');
        $this->assertDatabaseHas('cvs', [
            'id' => $cv->id,
            'title' => 'Updated CV Title'
        ]);
    }

    /** @test */
    public function user_cannot_edit_others_cv()
    {
        $otherUser = User::factory()->create();
        $this->actingAs($this->user);

        $othersCv = Cv::factory()->create([
            'user_id' => $otherUser->id,
            'template_id' => $this->template->id
        ]);

        $response = $this->get(route('cv.edit', $othersCv));

        $response->assertForbidden();
    }

    /** @test */
    public function cv_download_count_increments_correctly()
    {
        $this->actingAs($this->user);

        $cv = Cv::factory()->create([
            'user_id' => $this->user->id,
            'template_id' => $this->template->id,
            'is_paid' => true,
            'download_count' => 0
        ]);

        $response = $this->get(route('cv.download', $cv));

        $cv->refresh();
        $this->assertEquals(1, $cv->download_count);
    }

    /** @test */
    public function cv_content_is_properly_sanitized()
    {
        $this->actingAs($this->user);

        $maliciousData = [
            'title' => 'Test CV',
            'template_id' => $this->template->id,
            'personal_info' => [
                'full_name' => '<script>alert("xss")</script>John Doe',
                'email' => 'john@example.com'
            ],
            'professional_summary' => '<img src=x onerror=alert(1)>Summary text'
        ];

        $response = $this->post(route('cv.store'), $maliciousData);

        $cv = Cv::where('user_id', $this->user->id)->first();
        $this->assertNotNull($cv, 'CV should be created');
        
        $content = $cv->content;
        
        // Check that malicious scripts are removed
        $this->assertStringNotContainsString('<script>', $content['personal_info']['full_name']);
        $this->assertStringNotContainsString('onerror=', $content['professional_summary']);
        $this->assertStringContainsString('John Doe', $content['personal_info']['full_name']);
    }
}
