<?php

namespace App\Services;

use App\Models\Cv;
use App\Models\User;
use App\Models\Template;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CvService
{
    /**
     * Create a new CV
     */
    public function createCv(array $data, User $user): Cv
    {
        $template = Template::findOrFail($data['template_id']);
        
        // Clean and validate content against template structure
        $content = $this->validateAndCleanContent($data['content'], $template);
        
        // Always set user_id explicitly for direct Cv::create usage
        return Cv::create([
            'user_id' => $user->id,
            'template_id' => $template->id,
            'title' => $data['title'],
            'content' => $content,
            'is_paid' => false,
        ]);
    }

    /**
     * Update existing CV
     */
    public function updateCv(Cv $cv, array $data): bool
    {
        if ($cv->is_paid) {
            throw new \Exception('Cannot edit a paid CV');
        }

        $template = Template::findOrFail($data['template_id']);
        $content = $this->validateAndCleanContent($data['content'], $template);
        
        return $cv->update([
            'template_id' => $template->id,
            'title' => $data['title'],
            'content' => $content,
        ]);
    }

    /**
     * Generate HTML for CV preview
     */
    public function generateHtml(Cv $cv): string
    {
        $template = $cv->template;
        $content = $cv->content;
        $styling = $template->content['styling'] ?? [];

        return view('cv.templates.ats-compliant', [
            'cv' => $cv,
            'content' => $content,
            'styling' => $styling,
            'template' => $template
        ])->render();
    }

    /**
     * Generate PDF for CV download
     */
    public function generatePdf(Cv $cv)
    {
        $html = $this->generateHtml($cv);
        
        $pdf = Pdf::loadHTML($html);
        
        // Configure PDF settings for ATS compliance
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'dpi' => 96,
            'defaultFont' => 'Arial',
            'isRemoteEnabled' => false,
            'isHtml5ParserEnabled' => true,
            'debugPng' => false,
            'debugKeepTemp' => false,
            'debugCss' => false,
            'debugLayout' => false,
            'debugLayoutLines' => false,
            'debugLayoutBlocks' => false,
            'debugLayoutInline' => false,
            'debugLayoutPaddingBox' => false,
        ]);

        return $pdf;
    }

    /**
     * Mark CV as paid and store PDF
     */
    public function markAsPaid(Cv $cv, string $paymentId): bool
    {
        $pdf = $this->generatePdf($cv);
        $filename = 'cvs/' . $cv->user_id . '/' . Str::slug($cv->title) . '-' . time() . '.pdf';
        
        // Store PDF file
        Storage::put($filename, $pdf->output());
        
        return $cv->update([
            'is_paid' => true,
            'paid_at' => now(),
            'pdf_path' => $filename,
        ]);
    }

    /**
     * Validate and clean content against template structure
     */
    private function validateAndCleanContent(array $content, Template $template): array
    {
        $templateSections = $template->content['sections'] ?? [];
        $cleanedContent = [];

        foreach ($templateSections as $sectionKey => $sectionConfig) {
            if (isset($content[$sectionKey])) {
                $cleanedContent[$sectionKey] = $this->cleanSectionContent(
                    $content[$sectionKey],
                    $sectionConfig
                );
            }
        }

        return $cleanedContent;
    }

    /**
     * Clean individual section content
     */
    private function cleanSectionContent($content, array $sectionConfig)
    {
        $type = $sectionConfig['type'] ?? 'simple';

        switch ($type) {
            case 'textarea':
                return $this->cleanText($content, $sectionConfig['max_chars'] ?? 1000);
                
            case 'repeatable':
                if (!is_array($content)) return [];
                return array_map(function($item) use ($sectionConfig) {
                    return $this->cleanRepeatableItem($item, $sectionConfig['fields'] ?? []);
                }, array_values($content));
                
            case 'tags':
            case 'categorized_list':
                return is_array($content) ? array_values($content) : [];
                
            default:
                return is_array($content) ? $content : [];
        }
    }

    /**
     * Clean repeatable item content
     */
    private function cleanRepeatableItem($item, array $allowedFields): array
    {
        if (!is_array($item)) return [];
        
        $cleaned = [];
        foreach ($allowedFields as $field) {
            if (isset($item[$field])) {
                $cleaned[$field] = $this->sanitizeField($item[$field], $field);
            }
        }
        
        return $cleaned;
    }

    /**
     * Sanitize individual field
     */
    private function sanitizeField($value, string $fieldType)
    {
        if (is_string($value)) {
            $value = strip_tags($value);
            $value = trim($value);
            
            // Specific cleaning based on field type
            if (str_contains($fieldType, 'email')) {
                return filter_var($value, FILTER_VALIDATE_EMAIL) ?: '';
            }
            
            if (str_contains($fieldType, 'url') || str_contains($fieldType, 'website') || str_contains($fieldType, 'linkedin')) {
                return filter_var($value, FILTER_VALIDATE_URL) ?: '';
            }
            
            if (str_contains($fieldType, 'date')) {
                try {
                    return \Carbon\Carbon::parse($value)->format('Y-m-d');
                } catch (\Exception $e) {
                    return '';
                }
            }
            
            if (str_contains($fieldType, 'phone')) {
                return preg_replace('/[^+\d\s()-]/', '', $value);
            }
            
            return $value;
        }
        
        return $value;
    }

    /**
     * Clean text content
     */
    private function cleanText(string $text, int $maxLength = 1000): string
    {
        $text = strip_tags($text);
        $text = trim($text);
        return Str::limit($text, $maxLength);
    }

    /**
     * Get CV statistics for dashboard
     */
    public function getCvStats(User $user): array
    {
        return [
            'total_cvs' => $user->cvs()->count(),
            'paid_cvs' => $user->cvs()->where('is_paid', true)->count(),
            'unpaid_cvs' => $user->cvs()->where('is_paid', false)->count(),
            'total_downloads' => $user->cvs()->sum('download_count'),
            'recent_cvs' => $user->cvs()->latest()->limit(3)->get(),
        ];
    }
}