<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\AiCvContentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class CvAssistant extends Component
{
    public $isOpen = false;
    public $message = '';
    public $messages = [];
    public $isLoading = false;
    public $language = 'ar';
    public $autoFillEnabled = true;
    public $currentCvId = null;
    public $suggestions = [];
    public $lastUpdateNotification = '';

    public function mount()
    {
        // Initialize language from app locale
        $this->language = app()->getLocale() === 'en' ? 'en' : 'ar';
        
        // Try to detect current CV if we're on a CV page
        $this->detectCurrentCv();
        
        // Add intelligent welcome message
        $this->messages = [
            [
                'id' => 1,
                'type' => 'assistant',
                'content' => $this->getWelcomeMessage(),
                'time' => now()->format('H:i')
            ]
        ];
    }

    private function detectCurrentCv()
    {
        // Try to get CV ID from URL or route
        $currentUrl = request()->url();
        
        if (preg_match('/\/cvs\/(\d+)/', $currentUrl, $matches)) {
            $cvId = $matches[1];
            $cv = \App\Models\Cv::where('id', $cvId)
                              ->where('user_id', auth()->id())
                              ->first();
            if ($cv) {
                $this->currentCvId = $cvId;
            }
        }
    }

    private function getWelcomeMessage()
    {
        if ($this->language === 'ar') {
            if ($this->currentCvId) {
                return "👋 مرحباً! أنا كرافتي، مساعدك الذكي للسيرة الذاتية.\n\n✨ يمكنني مساعدتك في:\n• ملء السيرة الذاتية تلقائياً\n• اقتراح محتوى احترافي\n• تحسين الأقسام الموجودة\n\n💡 فقط أخبرني عن خبراتك أو مهاراتك وسأقوم بملء الحقول تلقائياً!";
            } else {
                return "👋 مرحباً! أنا كرافتي، مساعدك الذكي للسيرة الذاتية.\n\nيمكنني مساعدتك في إنشاء سيرة ذاتية احترافية. ابدأ بإنشاء سيرة ذاتية جديدة وسأساعدك في ملئها!";
            }
        } else {
            if ($this->currentCvId) {
                return "👋 Hi! I'm Crafty, your smart CV assistant.\n\n✨ I can help you:\n• Auto-fill your CV\n• Suggest professional content\n• Improve existing sections\n\n💡 Just tell me about your experience or skills and I'll fill the fields automatically!";
            } else {
                return "👋 Hi! I'm Crafty, your smart CV assistant.\n\nI can help you create a professional CV. Start by creating a new CV and I'll help you fill it!";
            }
        }
    }

    public function sendMessage()
    {
        if (empty(trim($this->message)) || $this->isLoading) {
            return;
        }

        // Add user message
        $this->messages[] = [
            'id' => count($this->messages) + 1,
            'type' => 'user',
            'content' => trim($this->message),
            'time' => now()->format('H:i')
        ];

        $userMessage = trim($this->message);
        $this->message = '';
        $this->isLoading = true;

        // Use smart AI-powered auto-fill if CV is detected
        if ($this->autoFillEnabled && $this->currentCvId) {
            $this->handleSmartAutoFill($userMessage);
        } else {
            $this->handleRegularChat($userMessage);
        }
    }

    private function handleSmartAutoFill($userMessage)
    {
        try {
            $response = Http::post(route('api.ai.chat'), [
                'message' => $userMessage,
                'cv_id' => $this->currentCvId,
                'language' => $this->language,
                'auto_fill' => true
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                $botMessage = $data['reply'] ?? 'تم معالجة رسالتك بنجاح!';
                
                // إضافة رسالة البوت
                $this->messages[] = [
                    'id' => count($this->messages) + 1,
                    'type' => 'assistant',
                    'content' => $botMessage,
                    'time' => now()->format('H:i')
                ];

                // إذا تم تحديث البيانات، أضف إشعار
                if (isset($data['data_updated']) && $data['data_updated']) {
                    $this->addUpdateNotification($data);
                    
                    // إضافة الاقتراحات
                    if (isset($data['suggestions'])) {
                        $this->suggestions = $data['suggestions'];
                        $this->addSuggestionsMessage();
                    }
                    
                    // تحديث الصفحة لإظهار البيانات الجديدة
                    $this->dispatch('cvDataUpdated', $data['cv_data'] ?? []);
                }
                
            } else {
                $this->handleRegularChat($userMessage);
            }
        } catch (\Exception $e) {
            $this->handleRegularChat($userMessage);
        }

        $this->isLoading = false;
        $this->dispatch('messageAdded');
        $this->dispatch('messageSent');
    }

    private function handleRegularChat($userMessage)
    {
        // Provider-backed multi-turn chat with memory
        $response = null;
        try {
            /** @var AiCvContentService $ai */
            $ai = app(AiCvContentService::class);
            if ($ai->enabled()) {
                // Build conversation history (map our messages to roles)
                $history = [];
                foreach ($this->messages as $m) {
                    $role = $m['type'] === 'assistant' ? 'assistant' : ($m['type'] === 'system' ? 'system' : 'user');
                    $history[] = ['role' => $role, 'content' => $m['content']];
                }
                // Append the latest user message already pushed above
                $response = (string) $ai->chat($history, $this->language, 0.6);
                if ($response === '') {
                    $response = $this->processAiResponse($userMessage);
                }
            } else {
                $response = $this->processAiResponse($userMessage);
            }
        } catch (\Throwable $e) {
            $response = $this->processAiResponse($userMessage);
        }
        
        $this->messages[] = [
            'id' => count($this->messages) + 1,
            'type' => 'assistant',
            'content' => $response,
            'time' => now()->format('H:i')
        ];

        $this->isLoading = false;
        $this->dispatch('messageAdded');
        $this->dispatch('messageSent');
    }

    private function addUpdateNotification($data)
    {
        $updatedFields = $data['updated_fields'] ?? [];
        
        if (!empty($updatedFields)) {
            $fieldNames = [
                'personal_info' => $this->language === 'ar' ? 'المعلومات الشخصية' : 'Personal Information',
                'experience' => $this->language === 'ar' ? 'الخبرة العملية' : 'Work Experience',
                'education' => $this->language === 'ar' ? 'التعليم' : 'Education',
                'skills' => $this->language === 'ar' ? 'المهارات' : 'Skills',
                'languages' => $this->language === 'ar' ? 'اللغات' : 'Languages'
            ];

            $updatedFieldsNames = array_map(function($field) use ($fieldNames) {
                return $fieldNames[$field] ?? $field;
            }, $updatedFields);

            $notificationText = $this->language === 'ar' 
                ? "🎉 تم تحديث الأقسام التالية تلقائياً:\n• " . implode("\n• ", $updatedFieldsNames)
                : "🎉 The following sections were updated automatically:\n• " . implode("\n• ", $updatedFieldsNames);

            $this->messages[] = [
                'id' => count($this->messages) + 1,
                'type' => 'system',
                'content' => $notificationText,
                'time' => now()->format('H:i')
            ];
        }
    }

    private function addSuggestionsMessage()
    {
        if (!empty($this->suggestions)) {
            $suggestionsText = $this->language === 'ar' 
                ? "💡 اقتراحات لتحسين سيرتك الذاتية:\n• " . implode("\n• ", $this->suggestions)
                : "💡 Suggestions to improve your CV:\n• " . implode("\n• ", $this->suggestions);

            $this->messages[] = [
                'id' => count($this->messages) + 1,
                'type' => 'assistant',
                'content' => $suggestionsText,
                'time' => now()->format('H:i')
            ];
        }
    }

    public function quickMessage($message)
    {
        $this->message = $message;
        $this->sendMessage();
    }

    public function toggleChat()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function setLanguage($lang)
    {
        if (! in_array($lang, ['en', 'ar'])) {
            return;
        }
        $this->language = $lang;
        app()->setLocale($lang);
        $this->dispatch('languageChanged');
    }

    private function processAiResponse($message)
    {
        $message = strtolower($message);
        
        if (str_contains($message, 'ملخص') || str_contains($message, 'summary') || str_contains($message, 'هدف')) {
            return 'للحصول على ملخص مهني قوي، ركز على: 1) منصبك الحالي/مستواك 2) المهارات الأساسية 3) سنوات الخبرة 4) الأهداف المهنية. اجعله 2-3 جمل وموجه نحو العمل!';
        }
        
        if (str_contains($message, 'مهارات') || str_contains($message, 'skills')) {
            return 'أدرج المهارات التقنية والشخصية ذات الصلة بالوظيفة المستهدفة. اكتب 6-10 مهارات أساسية، مع التركيز على تلك المذكورة في الوظائف التي تهتم بها.';
        }
        
        if (str_contains($message, 'خبرة') || str_contains($message, 'experience') || str_contains($message, 'عمل')) {
            return 'للخبرة العملية، استخدم طريقة STAR: الموقف، المهمة، الإجراء، النتيجة. ركز على الإنجازات بالأرقام عندما يكون ذلك ممكناً (مثل "زيادة المبيعات بنسبة 20%").';
        }
        
        if (str_contains($message, 'قالب') || str_contains($message, 'template') || str_contains($message, 'تصميم')) {
            return 'اختر قالباً يتناسب مع مجال عملك. المجالات الإبداعية يمكنها استخدام تصاميم ملونة، بينما الأدوار المؤسسية يجب أن تلتزم بتخطيطات نظيفة ومهنية.';
        }
        
        return 'سأكون سعيداً لمساعدتك! يمكنك سؤالي عن أقسام السيرة الذاتية، نصائح الكتابة، اختيار القوالب، أو أي أسئلة محددة حول بناء سيرتك الذاتية.';
    }

    private function buildPrompt(string $message): string
    {
        $user = Auth::user();
        $name = $user->name ?? ($this->language === 'ar' ? 'صديقي' : 'Friend');
        if ($this->language === 'ar') {
            return "أنت مساعد ذكي وخبير في كتابة السير الذاتية لمنصة اسمها CVCraft. اسمك 'كرافتـي'.\nهدفك مساعدة المستخدم في إنشاء سيرة ذاتية احترافية باللغة العربية، واضحة ومناسبة لأنظمة ATS.\nاسم المستخدم: {$name}\n\nرسالة المستخدم الآن: '{$message}'\nرجاءً أجب بإيجاز وبنقاط عملية قابلة للنسخ عند الحاجة.";
        }
        return "You are a smart CV-writing assistant for a platform called CVCraft. Your name is 'Crafty'.\nYour goal is to help the user craft an ATS-friendly, professional resume in English.\nUser name: {$name}\n\nUser message: '{$message}'\nReply concisely and include ready-to-paste bullets when useful.";
    }

    public function render()
    {
        return view('livewire.cv-assistant');
    }
}
