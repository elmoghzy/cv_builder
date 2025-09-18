<?php

namespace App\Filament\Components\Traits;

use Filament\Forms\Components\Component;
use Illuminate\Support\Facades\Log;

trait HasAIGeneration
{
    public static function generateAIContent($component, $state, $set, $get): void
    {
        try {
            Log::info('=== AI Generation Started ===');
            
            $aiService = app(\App\Services\AiCvContentService::class);
            
            // Debug: Log the component details
            $fieldName = $component->getName();
            $statePath = $component->getStatePath();
            
            Log::info('Field Name: ' . ($fieldName ?? 'null'));
            Log::info('State Path: ' . ($statePath ?? 'null'));
            Log::info('Current State: ' . json_encode($state));
            
            // Get the form itself to access all data
            $livewire = $component->getLivewire();
            $targetRole = 'data analyst'; // Default
            $persona = 'experienced'; // Default
            
            if ($livewire && method_exists($livewire, 'data')) {
                $formData = $livewire->data ?? [];
                Log::info('Livewire Form Data: ' . json_encode($formData, JSON_UNESCAPED_UNICODE));
                
                // Try to extract target_role from different possible locations
                $targetRole = $formData['target_role'] ?? 
                             $formData['data']['target_role'] ?? 
                             $livewire->target_role ?? 
                             'data analyst';
                             
                $persona = $formData['persona'] ?? 
                          $formData['data']['persona'] ?? 
                          $livewire->persona ?? 
                          'experienced';
                          
                Log::info('Context - Target Role: ' . $targetRole);
                Log::info('Context - Persona: ' . $persona);
            } else {
                Log::info('No livewire component found, using defaults');
            }
            
            // Create context-aware prompts based on the target role
            $targetRole = $targetRole ?? 'data analyst'; // Default to data analyst for now
            $prompt = static::buildPromptForField($fieldName, $targetRole, $persona, []);
            
            Log::info('Final Generated Prompt: ' . $prompt);
            
            $generatedContent = $aiService->generateSimpleText($prompt);
            
            // Clean the generated content
            $generatedContent = static::cleanGeneratedContent($generatedContent);
            
            Log::info('AI Response Length: ' . strlen($generatedContent));
            Log::info('AI Response Preview: ' . substr($generatedContent, 0, 100) . '...');
            
            // Try to set the content using different approaches for Filament
            Log::info('Setting field content...');
            
            // Method 1: Try direct state path
            $set($statePath, $generatedContent);
            Log::info('Content set via state path: ' . $statePath);
            
            // Method 2: Also try relative path for repeaters
            if (str_contains($statePath, '.')) {
                $parts = explode('.', $statePath);
                $relativePath = end($parts);
                $set($relativePath, $generatedContent);
                Log::info('Content also set via relative path: ' . $relativePath);
            }
            
            Log::info('Field content set successfully');
            
            // Show success notification
            \Filament\Notifications\Notification::make()
                ->title('تم الإنشاء بنجاح! ⭐')
                ->body($generatedContent)
                ->success()
                ->duration(5000)
                ->send();
                
            Log::info('=== AI Generation Completed ===');
            
        } catch (\Exception $e) {
            Log::error('=== AI Generation Error ===');
            Log::error('Error Message: ' . $e->getMessage());
            Log::error('Stack Trace: ' . $e->getTraceAsString());
            
            \Filament\Notifications\Notification::make()
                ->title('خطأ في الذكاء الاصطناعي')
                ->body('حدث خطأ: ' . $e->getMessage())
                ->danger()
                ->duration(5000)
                ->send();
        }
    }

    protected static function buildPromptForField($fieldName, $targetRole = 'Professional', $persona = 'experienced', $personalInfo = []): string
    {
        $name = $personalInfo['full_name'] ?? '';
        
        // Build context-aware prompts based on target role
        $prompts = [
            'job_title' => "Generate a specific job title for someone targeting the role: {$targetRole}. Be precise and relevant. Examples for data analyst: 'Senior Data Analyst', 'Business Intelligence Analyst', 'Data Scientist'. Only return the job title.",
            
            'company' => "Generate a realistic company name that would hire someone in {$targetRole} field. Consider companies in tech, consulting, finance, or relevant industry. Examples: 'DataTech Solutions', 'Analytics Corp', 'TechFlow Industries'. Only return company name.",
            
            'location' => "Generate a professional location suitable for {$targetRole} jobs. Use format: City, Country. Examples: 'San Francisco, USA', 'London, UK', 'Dubai, UAE'. Only return location.",
            
            'description' => "Write 2-3 professional sentences describing the responsibilities and achievements of someone working as {$targetRole}. Be specific to this role. Focus on relevant skills, tools, and accomplishments. Use action verbs and quantifiable results when possible.",
            
            'achievements' => "List 2-3 specific achievements for someone working in {$targetRole} field. Use bullet points. Include metrics and results. Examples for data analyst: '• Improved data processing efficiency by 40%', '• Built predictive models with 95% accuracy', '• Reduced reporting time from 2 days to 2 hours'.",
            
            'professional_summary' => "Write a 2-3 sentence professional summary for someone targeting {$targetRole} position with {$persona} level experience. Highlight relevant skills, experience, and career goals specific to this field.",
            
            'objective' => "Write 1-2 sentences about career objectives for someone targeting {$targetRole} position. Be specific about goals in this field.",
            
            'full_name' => 'Generate a realistic professional full name. Example: Ahmed Mohamed',
            'email' => 'Generate a professional email address. Example: ahmed.mohamed@email.com',
            'phone' => 'Generate a phone number. Example: +20 100 123 4567',
            'address' => 'Generate a professional address. Example: 123 Main Street, Cairo, Egypt',
            'linkedin' => 'Generate a LinkedIn URL. Example: linkedin.com/in/ahmed-mohamed',
            'website' => 'Generate a professional portfolio website URL. Example: www.ahmed-portfolio.com',
            
            'degree' => "Generate a degree relevant to {$targetRole} field. Examples for data analyst: 'Bachelor of Statistics', 'Master of Data Science', 'Bachelor of Computer Science'.",
            
            'institution' => "Generate a university name suitable for {$targetRole} education. Example: 'Cairo University', 'American University', 'Tech Institute'.",
            
            'honors' => "Generate 1-2 academic honors relevant to {$targetRole} field. Example: 'Dean List in Statistics', 'Outstanding Performance in Data Analysis'.",
        ];

        return $prompts[$fieldName] ?? "Generate professional content for {$fieldName} specifically relevant to {$targetRole} position. Be precise and contextual.";
    }

    protected static function cleanGeneratedContent($content): string
    {
        // Remove markdown formatting
        $content = preg_replace('/\*\*(.*?)\*\*/', '$1', $content);
        $content = preg_replace('/\*(.*?)\*/', '$1', $content);
        
        // Remove bullet points and formatting
        $content = preg_replace('/^[\*\-\•]\s*/', '', $content);
        $content = preg_replace('/^\d+\.\s*/', '', $content);
        
        // Remove multiple lines and keep only the first meaningful sentence(s)
        $lines = explode("\n", $content);
        $cleanLines = [];
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line) && !preg_match('/^[\*\-\•\d]/', $line)) {
                $cleanLines[] = $line;
                if (count($cleanLines) >= 2) break; // Max 2 lines
            }
        }
        
        $result = implode(' ', $cleanLines);
        
        // Remove any remaining formatting
        $result = preg_replace('/\[.*?\]/', '', $result);
        $result = preg_replace('/\(.*?\)/', '', $result);
        $result = preg_replace('/\s+/', ' ', $result);
        
        return trim($result);
    }
}
