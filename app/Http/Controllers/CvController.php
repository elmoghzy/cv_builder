<?php

namespace App\Http\Controllers;

use App\Models\Cv;
use App\Models\Template;
use App\Http\Requests\StoreCvRequest;
use App\Http\Requests\UpdateCvRequest;
use App\Services\CvService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CvController extends Controller
{
    protected $cvService;

    public function __construct(CvService $cvService)
    {
        $this->cvService = $cvService;
        $this->middleware('auth');
    }

    /**
     * Display user's CVs
     */
    public function index()
    {
        $cvs = Auth::user()->cvs()
            ->with('template')
            ->latest()
            ->paginate(12);

        return view('cv.index', compact('cvs'));
    }

    /**
     * Show CV builder form
     */
    public function create(Request $request)
    {
        // Add debugging
        \Log::info('CV Builder accessed', [
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email ?? 'guest'
        ]);

        $templates = Template::active()->orderBy('sort_order')->get();
        
        // If no templates exist, create a default one
        if ($templates->isEmpty()) {
            \Log::info('No templates found, creating default template');
            $defaultTemplate = Template::create([
                'name' => 'Professional Template',
                'description' => 'A clean and professional CV template',
                'content' => [
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
                'sort_order' => 1
            ]);
            $templates = collect([$defaultTemplate]);
        }
        
        $selectedTemplateId = $request->get('template_id', $templates->first()->id ?? null);
        $selectedTemplate = $templates->find($selectedTemplateId);

        if (!$selectedTemplate) {
            $selectedTemplate = $templates->first();
        }

        \Log::info('CV Builder data prepared', [
            'templates_count' => $templates->count(),
            'selected_template' => $selectedTemplate->name ?? 'none'
        ]);

        return view('cv.builder', compact('templates', 'selectedTemplate'));
    }

    /**
     * Store new CV
     */
    public function store(StoreCvRequest $request)
    {
        try {
            // Add debugging info
            \Log::info('CV creation attempt', [
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email,
                'request_data' => $request->all()
            ]);

            // Ensure user_id is always set
            $validatedData = $request->validated();
            $validatedData['user_id'] = auth()->id();
            
            $cv = $this->cvService->createCv($validatedData, auth()->user());
            
            \Log::info('CV created successfully', ['cv_id' => $cv->id]);
            
            return redirect()
                ->route('cv.preview', $cv)
                ->with('success', 'CV created successfully! Review and proceed to payment for download.');
        } catch (\Exception $e) {
            \Log::error('CV creation failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create CV: ' . $e->getMessage());
        }
    }

    /**
     * Show CV for editing
     */
    public function edit(Cv $cv)
    {
        Gate::authorize('update', $cv);
        
        $templates = Template::active()->orderBy('sort_order')->get();
        
        return view('cv.edit', compact('cv', 'templates'));
    }

    /**
     * Update CV
     */
    public function update(UpdateCvRequest $request, Cv $cv)
    {
        Gate::authorize('update', $cv);

        try {
            $this->cvService->updateCv($cv, $request->validated());
            
            return redirect()
                ->route('cv.preview', $cv)
                ->with('success', 'CV updated successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update CV. Please try again.');
        }
    }

    /**
     * Preview CV before download
     */
    public function preview(Cv $cv)
    {
        Gate::authorize('view', $cv);
        
        $html = $this->cvService->generateHtml($cv);
        
        return view('cv.preview', compact('cv', 'html'));
    }

    /**
     * Download CV as PDF
     */
    public function download(Cv $cv)
    {
        Gate::authorize('download', $cv);

        if (!$cv->is_paid) {
            return redirect()
                ->route('payment.initiate', $cv)
                ->with('info', 'Payment required to download CV.');
        }

        try {
            $pdf = $this->cvService->generatePdf($cv);
            $cv->increment('download_count');
            
            return $pdf->download($cv->title . '.pdf');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to generate PDF. Please try again.');
        }
    }
}
