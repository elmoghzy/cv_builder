<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AIController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Enhance a specific section of the CV.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function enhanceSection(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'text' => 'required|string|max:3000',
        ]);

        $enhancedText = $this->aiService->enhanceSection($validated['text']);

        return response()->json(['enhanced_text' => $enhancedText]);
    }

    /**
     * Analyze the full CV for ATS compliance.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function analyzeCv(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'cv_data' => 'required|array',
        ]);

        $analysisResult = $this->aiService->analyzeCvForAts($validated['cv_data']);

        return response()->json(['analysis' => $analysisResult]);
    }
}
