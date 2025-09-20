<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\AiCvContentService;

class ChatbotController extends Controller
{
    protected $aiService;

    public function __construct(AiCvContentService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function handle(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'cv_data' => 'nullable|array',
            'cv_id' => 'nullable|integer',
            'language' => 'nullable|in:ar,en',
            'auto_fill' => 'nullable|boolean'
        ]);

        $message = $request->input('message');
        $cvData = $request->input('cv_data', []);
        $cvId = $request->input('cv_id');
        $language = $request->input('language', config('ai.language', 'ar'));
        $autoFill = $request->input('auto_fill', false);
        $user = $request->user();

        try {
            // إذا كان المستخدم يريد ملء تلقائي، استخدم AI لتحليل الرسالة وإنشاء بيانات
            if ($autoFill && $cvId) {
                return $this->handleAutoFill($message, $cvId, $user, $language);
            }

            // محادثة مختصرة بدون ترحيب/تعريف متكرر، مع دعم سياق البيانات الحالية
            $system = $language === 'ar'
                ? 'أنت كرافتي، مساعد احترافي للسيرة الذاتية. لا تُرسل تحية أو مقدمة تعريفية. أجب بإيجاز وبنقاط عملية جاهزة للصق عند الحاجة. لا تُكرر طلب نفس المعلومات. اسأل سؤال متابعة واحد قصير فقط إذا لزم.'
                : "You are Crafty, a professional resume assistant. Do not send greetings or self-introductions. Reply concisely with actionable points. Do not repeat asking for the same info. Ask at most one short follow-up if needed.";

            // إذا وُجدت بيانات سيرة حالية، أضِفها كنص نظامي ليستخدمها الذكاء الاصطناعي كمرجع
            if (!empty($cvData)) {
                $system .= $language === 'ar'
                    ? "\nهذه بيانات السيرة الحالية المرجعية (استخدمها لتحسين الردود):\n" . json_encode($cvData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                    : "\nHere is current CV data as reference (use it to tailor the reply):\n" . json_encode($cvData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }

            $messages = [
                ['role' => 'system', 'content' => $system],
                ['role' => 'user', 'content' => (string) $message],
            ];

            $response = $this->aiService->chat($messages, $language, 0.6);

            return response()->json([
                'reply' => (string) $response,
                'suggestions' => $this->generateSuggestions($message, $cvData, $language)
            ]);

        } catch (\Exception $e) {
            Log::error('Chatbot Error: ' . $e->getMessage());
            return response()->json(['error' => 'عذراً، حدث خطأ. حاول مرة أخرى.'], 500);
        }
    }

    /**
     * Generate structured CV content blocks from minimal inputs.
     * Accepts: persona, target_role, language (ar|en), and optional user info.
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'persona' => 'nullable|string|max:100',
            'target_role' => 'required|string|max:120',
            'language' => 'nullable|in:ar,en',
            'user' => 'nullable|array',
        ]);

        try {
            $inputs = [
                'persona' => $validated['persona'] ?? 'professional',
                'target_role' => $validated['target_role'],
                'language' => $validated['language'] ?? config('ai.language', 'en'),
                'user' => array_merge([
                    'name' => $request->user()->name ?? null,
                    'email' => $request->user()->email ?? null,
                ], $validated['user'] ?? []),
            ];

            $result = $this->aiService->generate($inputs);

            return response()->json(['content' => $result]);
        } catch (\Throwable $e) {
            Log::error('AI Generate CV content error: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to generate content'], 500);
        }
    }

    private function createPrompt($message, $cvData, $user, $language = 'en')
    {
        $userName = $user->name ?? 'Friend';
        if ($language === 'ar') {
            $prompt = "أنت مساعد ذكي وخبير في كتابة السير الذاتية لمنصة اسمها CVCraft. اسمك 'كرافتـي'.\nهدفك مساعدة المستخدم في إنشاء سيرة ذاتية احترافية باللغة العربية، واضحة ومناسبة لأنظمة ATS.\nاسم المستخدم: {$userName}\n\nرسالة المستخدم الآن: '{$message}'\n";
        } else {
            $prompt = "You are a smart CV-writing assistant for a platform called CVCraft. Your name is 'Crafty'.\nYour goal is to help the user craft an ATS-friendly, professional resume in English.\nUser name: {$userName}\n\nUser message: '{$message}'\n";
        }

        if (!empty($cvData)) {
            if ($language === 'ar') {
                $prompt .= "\nهذه بيانات السيرة الحالية التي يعمل عليها المستخدم:\n" . json_encode($cvData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                $prompt .= "\nاعتمد على هذه البيانات والرسالة لتقديم رد عملي مختصر، يتضمن:\n- تحسينات فورية مقترحة بصياغة احترافية\n- أسئلة توضيحية قصيرة عند الحاجة\n- أمثلة نقطية جاهزة للصق\nرجاءً خاطِب المستخدم باسمه ({$userName}) وبالعربية الفصحى المبسطة.";
            } else {
                $prompt .= "\nHere is the current CV data the user is working on:\n" . json_encode($cvData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                $prompt .= "\nUse this data and the message to provide a concise, actionable reply that includes:\n- Professional phrasing improvements\n- Short clarifying questions if needed\n- Ready-to-paste bullet point examples\nAlways address the user by name ({$userName}) and keep it clear and professional in English.";
            }
        } else {
            if ($language === 'ar') {
                $prompt .= "\nالمستخدم يبدأ للتو. رحّب به وقدّم نفسك واسأله عن الوظيفة المستهدفة وخبرته الحالية لتبدأ في صياغة قسم الملخص والمهارات.";
            } else {
                $prompt .= "\nThe user is just starting. Greet them, ask for the target role and current experience, then help draft the summary and skills sections.";
            }
        }

        $prompt .= $language === 'ar'
            ? "\nأجب دائماً بالعربية وبأسلوب ودود واحترافي. لا تكتب مقدمات طويلة؛ اعرض نقاط جاهزة مباشرة عند الاقتضاء."
            : "\nAlways answer in English with a friendly, professional tone. Avoid long introductions; provide ready-to-paste bullets when helpful.";

        return $prompt;
    }

    /**
     * Insert AI-generated content into CV
     */
    public function insertContent(Request $request, $cv)
    {
        $request->validate([
            'path' => 'required|string',
            'value' => 'required|string',
            'mode' => 'nullable|in:replace,append'
        ]);

        try {
            // Find the CV
            $cvModel = \App\Models\Cv::findOrFail($cv);
            
            // Check if user owns this CV
            if ($cvModel->user_id !== auth()->id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $path = $request->input('path');
            $value = $request->input('value');
            $mode = $request->input('mode', 'replace');

            // Get current CV data
            $content = $cvModel->content ?? [];

            // Insert/update the content based on path
            $pathParts = explode('.', $path);
            $current = &$content;

            // Navigate to the target location
            for ($i = 0; $i < count($pathParts) - 1; $i++) {
                if (!isset($current[$pathParts[$i]])) {
                    $current[$pathParts[$i]] = [];
                }
                $current = &$current[$pathParts[$i]];
            }

            $lastKey = end($pathParts);

            if ($mode === 'append' && isset($current[$lastKey])) {
                $current[$lastKey] .= "\n" . $value;
            } else {
                $current[$lastKey] = $value;
            }

            // Save the updated CV
            $cvModel->content = $content;
            $cvModel->save();

            return response()->json(['success' => true, 'message' => 'Content inserted successfully']);

        } catch (\Exception $e) {
            Log::error('AI Insert Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to insert content'], 500);
        }
    }

    /**
     * Handle auto-fill functionality
     */
    private function handleAutoFill($message, $cvId, $user, $language)
    {
        $cv = \App\Models\Cv::where('id', $cvId)->where('user_id', $user->id)->first();
        
        if (!$cv) {
            return response()->json(['error' => 'CV not found'], 404);
        }

        // تحليل الرسالة لاستخراج المعلومات
        $extractedData = $this->extractDataFromMessage($message, $language);
        
        // دمج البيانات المستخرجة مع البيانات الحالية
        $currentContent = $cv->content ?? [];
        $updatedContent = $this->mergeData($currentContent, $extractedData);
        
        // حفظ البيانات المحدثة
        $cv->content = $updatedContent;
        $cv->save();

        // إنشاء رد ذكي مع اقتراحات
        $response = $this->generateIntelligentResponse($extractedData, $language);
        $suggestions = $this->generateAdvancedSuggestions($updatedContent, $language);

        return response()->json([
            'reply' => $response,
            'data_updated' => true,
            'updated_fields' => array_keys($extractedData),
            'suggestions' => $suggestions,
            'cv_data' => $updatedContent
        ]);
    }

    /**
     * Extract structured data from user message using AI
     */
    private function extractDataFromMessage($message, $language)
    {
        $prompt = $language === 'ar' 
            ? "استخرج المعلومات التالية من الرسالة وأرجعها كـ JSON صالح:\n\n"
            : "Extract the following information from the message and return it as valid JSON:\n\n";

        $prompt .= "الرسالة: \"$message\"\n\n";
        
        $prompt .= $language === 'ar'
            ? "استخرج أي من المعلومات التالية إذا وُجدت:\n"
            : "Extract any of the following information if found:\n";

        $fields = [
            'personal_info' => ['full_name', 'email', 'phone', 'address', 'summary'],
            'experience' => ['job_title', 'company', 'start_date', 'end_date', 'description'],
            'education' => ['degree', 'institution', 'graduation_year', 'grade'],
            'skills' => 'array of skills',
            'languages' => ['language', 'level']
        ];

        $prompt .= json_encode($fields, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";
        
        $prompt .= $language === 'ar'
            ? "أرجع JSON فقط بدون أي نص إضافي. إذا لم تجد معلومات، أرجع {}."
            : "Return only JSON without any additional text. If no information found, return {}.";

        try {
            $response = $this->aiService->generateSimpleText($prompt);
            $cleanResponse = $this->cleanJsonResponse($response);
            return json_decode($cleanResponse, true) ?? [];
        } catch (\Exception $e) {
            Log::error('Data extraction error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Clean JSON response from AI
     */
    private function cleanJsonResponse($response)
    {
        // إزالة النص الإضافي والحصول على JSON فقط
        $response = trim($response);
        
        // البحث عن أول { وآخر }
        $startPos = strpos($response, '{');
        $endPos = strrpos($response, '}');
        
        if ($startPos !== false && $endPos !== false) {
            return substr($response, $startPos, $endPos - $startPos + 1);
        }
        
        return '{}';
    }

    /**
     * Merge extracted data with existing CV data
     */
    private function mergeData($currentData, $extractedData)
    {
        foreach ($extractedData as $section => $data) {
            if ($section === 'personal_info' && is_array($data)) {
                if (!isset($currentData['personal_info'])) {
                    $currentData['personal_info'] = [];
                }
                $currentData['personal_info'] = array_merge($currentData['personal_info'], $data);
            } 
            elseif ($section === 'skills' && is_array($data)) {
                if (!isset($currentData['skills'])) {
                    $currentData['skills'] = [];
                }
                $currentData['skills'] = array_unique(array_merge($currentData['skills'], $data));
            }
            elseif (in_array($section, ['experience', 'education', 'languages']) && is_array($data)) {
                if (!isset($currentData[$section])) {
                    $currentData[$section] = [];
                }
                // إضافة البيانات الجديدة
                if (isset($data[0])) {
                    // إذا كانت مصفوفة من الكائنات
                    $currentData[$section] = array_merge($currentData[$section], $data);
                } else {
                    // إذا كان كائن واحد
                    $currentData[$section][] = $data;
                }
            }
        }
        
        return $currentData;
    }

    /**
     * Generate intelligent response based on extracted data
     */
    private function generateIntelligentResponse($extractedData, $language)
    {
        if (empty($extractedData)) {
            return $language === 'ar' 
                ? "فهمت! هل يمكنك إخباري بمزيد من التفاصيل عن خبراتك أو مهاراتك؟"
                : "Got it! Can you tell me more details about your experience or skills?";
        }

        $responses = [];
        
        if (isset($extractedData['personal_info'])) {
            $responses[] = $language === 'ar' 
                ? "✅ تم حفظ معلوماتك الشخصية بنجاح!"
                : "✅ Personal information saved successfully!";
        }
        
        if (isset($extractedData['experience'])) {
            $responses[] = $language === 'ar' 
                ? "💼 رائع! أضفت خبرة عملية جديدة."
                : "💼 Great! Added new work experience.";
        }
        
        if (isset($extractedData['skills'])) {
            $skillCount = count($extractedData['skills']);
            $responses[] = $language === 'ar' 
                ? "🎯 ممتاز! أضفت $skillCount مهارة جديدة."
                : "🎯 Excellent! Added $skillCount new skills.";
        }

        $baseResponse = implode(' ', $responses);
        
        $suggestion = $language === 'ar'
            ? " هل تريد إضافة المزيد من التفاصيل أو الانتقال لقسم آخر؟"
            : " Would you like to add more details or move to another section?";

        return $baseResponse . $suggestion;
    }

    /**
     * Generate suggestions based on current CV content
     */
    private function generateSuggestions($message, $cvData, $language)
    {
        $suggestions = [];
        
        if ($language === 'ar') {
            $suggestions = [
                "أضف خبرة عملية جديدة",
                "حدث المهارات",
                "أضف مؤهل تعليمي",
                "اكتب ملخص شخصي قوي",
                "أضف شهادات أو دورات"
            ];
        } else {
            $suggestions = [
                "Add new work experience",
                "Update skills",
                "Add educational qualification", 
                "Write a strong personal summary",
                "Add certificates or courses"
            ];
        }

        return $suggestions;
    }

    /**
     * Generate advanced suggestions based on CV completeness
     */
    private function generateAdvancedSuggestions($cvData, $language)
    {
        $suggestions = [];
        
        // فحص اكتمال الأقسام
        if (!isset($cvData['personal_info']['summary']) || empty($cvData['personal_info']['summary'])) {
            $suggestions[] = $language === 'ar' 
                ? "💡 اقتراح: أضف ملخص شخصي يبرز نقاط قوتك"
                : "💡 Suggestion: Add a personal summary highlighting your strengths";
        }
        
        if (!isset($cvData['experience']) || count($cvData['experience']) < 2) {
            $suggestions[] = $language === 'ar' 
                ? "📈 اقتراح: أضف المزيد من الخبرات العملية"
                : "📈 Suggestion: Add more work experiences";
        }
        
        if (!isset($cvData['skills']) || count($cvData['skills']) < 5) {
            $suggestions[] = $language === 'ar' 
                ? "🎯 اقتراح: أضف المزيد من المهارات التقنية والشخصية"
                : "🎯 Suggestion: Add more technical and soft skills";
        }

        return $suggestions;
    }
}
