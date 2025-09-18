<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\Cv;
use App\Services\CvService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TemplateController extends Controller
{
    /**
     * Preview a template with sample data in an iframe-friendly page.
     */
    public function preview(Template $template, CvService $cvService)
    {
        // Require authentication (route group enforces), and optionally restrict to admins in non-local
        $user = Auth::user();
        if (!app()->isLocal()) {
            if (! ($user instanceof User) || ! $user->hasRole('admin')) {
                abort(403);
            }
        }

        // Minimal sample content for a clean preview
        $sampleContent = [
            'personal_info' => [
                'full_name' => 'Jane Doe',
                'email' => 'jane.doe@example.com',
                'phone' => '+1 555 123 4567',
                'address' => 'Cairo, Egypt',
                'linkedin' => 'linkedin.com/in/janedoe',
                'website' => 'janedoe.dev',
            ],
            'professional_summary' => 'Results-driven Software Engineer with 5+ years of experience building reliable web applications and APIs.',
            'work_experience' => [
                [
                    'job_title' => 'Senior Software Engineer',
                    'company' => 'Tech Corp',
                    'description' => 'Led a team to deliver scalable services and optimized performance.',
                ],
                [
                    'job_title' => 'Backend Engineer',
                    'company' => 'Acme Inc.',
                    'description' => 'Built RESTful APIs and integrated third-party services.',
                ],
            ],
            'education' => [
                [
                    'degree' => 'B.Sc. Computer Science',
                    'institution' => 'ABC University',
                ],
            ],
            'skills' => ['PHP', 'Laravel', 'MySQL', 'REST APIs', 'Docker'],
        ];

        // Build an unsaved CV model and attach the template relation
        $cv = new Cv([
            'title' => 'Template Preview: ' . ($template->name ?? 'CV'),
            'content' => $sampleContent,
            'is_paid' => true,
            'status' => 'completed',
        ]);
        $cv->setRelation('template', $template);

        $html = $cvService->generateHtml($cv);

        return view('templates.preview', [
            'template' => $template,
            'html' => $html,
        ]);
    }
}
