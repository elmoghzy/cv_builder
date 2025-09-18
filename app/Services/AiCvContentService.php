<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiCvContentService
{
    public function enabled(): bool
    {
        $provider = config('ai.provider', 'openai');
        if ($provider === 'gemini') {
            return (bool) config('gemini.api_key');
        }
        return (bool) data_get(config('ai'), 'openai.api_key');
    }

    /**
     * Generate CV content blocks given minimal inputs.
     * Inputs: persona, target_role, language, user (optional array with name/email)
     */
    public function generate(array $inputs): array
    {
        if (!$this->enabled()) {
            return $this->fallback($inputs);
        }

    $lang = $inputs['language'] ?? config('ai.language', 'en');
        $persona = $inputs['persona'] ?? 'graduate';
        $role = $inputs['target_role'] ?? 'Software Engineer';
        $name = data_get($inputs, 'user.name', '');

                if (strtolower($lang) === 'ar') {
                        $prompt = trim(<<<PROMPT
اكتب محتوى سيرة ذاتية احترافي ومقنع باللغة العربية لشخص بروفايل "{$persona}" يتقدم لوظيفة "{$role}".
أعد الناتج بصيغة JSON صالحة فقط بدون أي شرح أو زخارف أو Markdown.
المخطط المطلوب:
{
    "professional_summary": "فقرة قوية 3-4 جمل تبرز القيمة والنتائج بالأرقام إن أمكن",
    "work_experience": [
        {"job_title":"","company":"","location":"","start_date":"YYYY-MM","end_date":"YYYY-MM","current":false,
         "description":"3-5 نقاط مختصرة توضح نطاق العمل والتقنيات",
         "achievements":"• إنجاز قابل للقياس\n• إنجاز آخر"}
    ],
    "education": [ {"degree":"","institution":"","location":"","graduation_date":"YYYY"} ],
    "technical_skills": [""],
    "soft_skills": [""],
    "languages": [""]
}
استخدم بيانات واقعية عامة بدون أسماء شركات حساسة. اجعل الأسلوب احترافيًا ومناسبًا لأنظمة ATS.
اسم المستخدم (إن وُجد): {$name}
PROMPT);
                } else {
                        $prompt = trim(<<<PROMPT
Write an expert, compelling resume content in English for a "{$persona}" profile applying to "{$role}".
Return ONLY valid JSON (no explanation, no markdown).
Schema:
{
    "professional_summary": "3-4 sentence strong summary highlighting value and measurable results where possible",
    "work_experience": [
        {"job_title":"","company":"","location":"","start_date":"YYYY-MM","end_date":"YYYY-MM","current":false,
         "description":"3-5 concise bullets describing scope & tech",
         "achievements":"• Measurable achievement\n• Another achievement"}
    ],
    "education": [ {"degree":"","institution":"","location":"","graduation_date":"YYYY"} ],
    "technical_skills": [""],
    "soft_skills": [""],
    "languages": [""]
}
Use neutral, realistic data; keep it ATS-friendly.
User name (if any): {$name}
PROMPT);
                }

        $provider = config('ai.provider', 'openai');

        try {
            if ($provider === 'gemini') {
                $content = $this->callGemini($prompt);
            } else {
                $content = $this->callOpenAI($prompt);
            }

            $json = $this->safeJsonDecode($content);
            if (!is_array($json)) {
                return $this->fallback($inputs);
            }

            return [
                'professional_summary' => (string) ($json['professional_summary'] ?? ''),
                'work_experience' => array_values((array) ($json['work_experience'] ?? [])),
                'education' => array_values((array) ($json['education'] ?? [])),
                'technical_skills' => array_values((array) ($json['technical_skills'] ?? [])),
                'soft_skills' => array_values((array) ($json['soft_skills'] ?? [])),
                'languages' => array_values((array) ($json['languages'] ?? [])),
            ];
        } catch (\Throwable $e) {
            Log::warning('AI generate failed, using fallback', ['error' => $e->getMessage()]);
            return $this->fallback($inputs);
        }
    }

    // (removed old generateSimpleText implementation to avoid duplication)

    private function fallback(array $inputs): array
    {
        $role = $inputs['target_role'] ?? 'Software Engineer';
        $lang = strtolower($inputs['language'] ?? config('ai.language', 'en'));

        if ($lang === 'en') {
            return [
                'professional_summary' => "Motivated {$role} candidate focused on delivering measurable outcomes, quick to learn, and effective in cross-functional teams.",
                'work_experience' => [[
                    'job_title' => 'Intern / Junior Developer',
                    'company' => 'Tech Company',
                    'location' => 'Cairo, Egypt',
                    'start_date' => now()->subMonths(18)->format('Y-m'),
                    'end_date' => now()->format('Y-m'),
                    'current' => false,
                    'description' => 'Contributed to feature development, performance tuning, and bug fixing across the stack.',
                    'achievements' => "• Improved page speed by 20%\n• Wrote initial unit tests\n• Documented key workflows",
                ]],
                'education' => [[
                    'degree' => 'B.Sc. Computer Science',
                    'institution' => 'Recognized University',
                    'location' => 'Cairo, Egypt',
                    'graduation_date' => now()->subYears(1)->format('Y'),
                ]],
                'technical_skills' => ['Laravel', 'PHP', 'MySQL', 'Git'],
                'soft_skills' => ['Communication', 'Problem-Solving', 'Teamwork'],
                'languages' => ['English', 'Arabic'],
            ];
        }

        // Arabic fallback
        return [
            'professional_summary' => "مرشح قوي لوظيفة {$role} مع تركيز على النتائج، قادر على التعلم السريع والعمل ضمن فرق متعددة التخصصات.",
            'work_experience' => [[
                'job_title' => 'متدرب / مطور مبتدئ',
                'company' => 'شركة تقنية',
                'location' => 'القاهرة، مصر',
                'start_date' => now()->subMonths(18)->format('Y-m'),
                'end_date' => now()->format('Y-m'),
                'current' => false,
                'description' => 'المساهمة في تطوير ميزات وتحسين الأداء ومعالجة المشاكل.',
                'achievements' => "• تحسين سرعة الصفحات 20%\n• تغطية اختبارات مبدئية\n• توثيق العمليات",
            ]],
            'education' => [[
                'degree' => 'بكالوريوس علوم الحاسب',
                'institution' => 'جامعة معروفة',
                'location' => 'القاهرة، مصر',
                'graduation_date' => now()->subYears(1)->format('Y'),
            ]],
            'technical_skills' => ['Laravel', 'PHP', 'MySQL', 'Git'],
            'soft_skills' => ['التواصل', 'حل المشكلات', 'العمل الجماعي'],
            'languages' => ['العربية', 'الإنجليزية'],
        ];
    }

    /**
     * Simple text generation routed by provider (for chatbot)
     */
    public function generateSimpleText(string $prompt): string
    {
        if (!$this->enabled()) {
            return 'ميزة الذكاء الاصطناعي غير مفعلة حالياً.';
        }

        $provider = config('ai.provider', 'openai');
        try {
            if ($provider === 'gemini') {
                return (string) $this->callGemini($prompt);
            }
            $content = $this->callOpenAI($prompt);
            return (string) $content;
        } catch (\Throwable $e) {
            Log::error('AI simple text error: ' . $e->getMessage());
            return 'حدث خطأ أثناء التواصل مع خدمة الذكاء الاصطناعي.';
        }
    }

    // --- Provider helpers ---
    private function callOpenAI(string $prompt): string
    {
        $response = Http::withToken(config('ai.openai.api_key'))
            ->timeout(config('ai.openai.timeout'))
            ->post(rtrim(config('ai.openai.base_url'), '/') . '/chat/completions', [
                'model' => config('ai.openai.model', 'gpt-4o-mini'),
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a senior CV writer. Output plain text or JSON only as requested.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
            ]);

        if (!$response->ok()) {
            throw new \RuntimeException('OpenAI request failed: ' . $response->status());
        }
        return (string) data_get($response->json(), 'choices.0.message.content', '');
    }

    private function callGemini(string $prompt): string
    {
        $model = config('gemini.model', 'gemini-1.5-flash');
        $base = rtrim(config('gemini.base_url', 'https://generativelanguage.googleapis.com/v1beta'), '/');
        $key = (string) config('gemini.api_key');

        $url = sprintf('%s/models/%s:generateContent?key=%s', $base, $model, $key);
        $payload = [
            'contents' => [[
                'parts' => [[ 'text' => $prompt ]]
            ]],
        ];

        $response = Http::timeout((int) config('ai.openai.timeout', 20))
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($url, $payload);

        if (!$response->ok()) {
            throw new \RuntimeException('Gemini request failed: ' . $response->status());
        }

        $text = data_get($response->json(), 'candidates.0.content.parts.0.text');
        return (string) ($text ?? '');
    }

    private function safeJsonDecode(?string $content): array|string|null
    {
        if ($content === null) return null;
        // strip markdown fences if present
        $clean = preg_replace('/```json|```/i', '', (string) $content);
        $clean = trim($clean ?? '');
        $json = json_decode($clean, true);
        return $json ?? null;
    }
}
