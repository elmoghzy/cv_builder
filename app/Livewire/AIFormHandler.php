<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\AiCvContentService;
use Filament\Notifications\Notification;

class AIFormHandler extends Component
{
    public $formData = [];

    protected $listeners = ['generate-ai-content' => 'generateAIContent'];

    public function generateAIContent($data)
    {
        try {
            $aiService = app(AiCvContentService::class);
            
            $statePath = $data['statePath'] ?? '';
            $fieldName = $data['fieldName'] ?? '';
            
            // Get context from form data
            $personalInfo = $this->formData['content']['personal_info'] ?? [];
            $targetRole = $this->formData['target_role'] ?? 'Professional';
            $persona = $this->formData['persona'] ?? 'experienced';
            
            // Build AI prompt
            $prompt = $this->buildPromptForField($fieldName, $personalInfo, $targetRole, $persona);
            
            if ($prompt) {
                $generatedContent = $aiService->generateSimpleText($prompt);
                
                // Update the form data
                data_set($this->formData, $statePath, $generatedContent);
                
                // Emit event to update the form
                $this->dispatch('form-updated', $this->formData);
                
                Notification::make()
                    ->title('تم الإنشاء بنجاح! ⭐')
                    ->body('تم إنشاء محتوى احترافي بواسطة الذكاء الاصطناعي')
                    ->success()
                    ->send();
            }
            
        } catch (\Exception $e) {
            Notification::make()
                ->title('خطأ في الذكاء الاصطناعي')
                ->body('حدث خطأ أثناء إنشاء المحتوى: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function buildPromptForField(string $fieldName, array $personalInfo, string $targetRole, string $persona): ?string
    {
        $name = $personalInfo['full_name'] ?? 'المستخدم';
        
        $prompts = [
            'address' => "اقترح عنوان مناسب لشخص يعمل في مجال {$targetRole} في مصر. اذكر مدينة ومنطقة فقط.",
            'linkedin' => "اقترح رابط LinkedIn احترافي مناسب لـ {$name} في مجال {$targetRole}. استخدم صيغة: https://linkedin.com/in/username",
            'website' => "اقترح رابط موقع شخصي أو portfolio مناسب لـ {$name} في مجال {$targetRole}. استخدم صيغة: https://portfolio.example.com",
            'professional_summary' => "اكتب ملخص مهني احترافي باللغة العربية لشخص اسمه {$name} يعمل في مجال {$targetRole} ومستواه المهني {$persona}. يجب أن يكون الملخص من 3-4 جمل ويبرز المهارات والخبرات الرئيسية.",
            'objective' => "اكتب هدف مهني واضح ومحدد باللغة العربية لشخص يعمل في مجال {$targetRole}. يجب أن يكون جملتين فقط ويركز على الأهداف المستقبلية.",
            'job_title' => "اقترح مسمى وظيفي احترافي مناسب لشخص في مجال {$targetRole} ومستواه {$persona}.",
            'company' => "اقترح اسم شركة مناسبة ومعروفة في مجال {$targetRole} في مصر أو الشرق الأوسط.",
            'location' => "اقترح موقع عمل مناسب لشركة في مجال {$targetRole}. اذكر مدينة ومنطقة فقط.",
            'description' => "اكتب وصف وظيفي احترافي باللغة العربية لمنصب {$targetRole}. اذكر 3-4 نقاط رئيسية عن المهام والمسؤوليات والإنجازات باستخدام علامات النقاط (•).",
        ];

        return $prompts[$fieldName] ?? "اقترح محتوى احترافي مناسب لحقل {$fieldName} في سيرة ذاتية لشخص يعمل في مجال {$targetRole}.";
    }

    public function render()
    {
        return view('livewire.ai-form-handler');
    }
}
