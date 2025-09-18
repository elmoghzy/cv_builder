<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content['personal_info']['full_name'] ?? 'CV' }} - Modern</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --accent-color: {{ $cv->accent_color ?? '#2563eb' }};
        }
        body {
            font-family: 'Cairo', sans-serif;
        }
        .accent-text {
            color: var(--accent-color);
        }
        .accent-bg {
            background-color: var(--accent-color);
        }
        .section-title {
            border-bottom: 2px solid var(--accent-color);
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white shadow-lg p-10">
        <header class="flex items-center justify-between mb-10">
            <div>
                <h1 class="text-4xl font-bold text-gray-800">{{ $content['personal_info']['full_name'] ?? '' }}</h1>
                <p class="text-xl font-semibold accent-text mt-1">{{ $content['personal_info']['target_role'] ?? '' }}</p>
            </div>
            <div class="text-right text-sm text-gray-600">
                @if(!empty($content['personal_info']['email']))
                    <div>{{ $content['personal_info']['email'] }}</div>
                @endif
                @if(!empty($content['personal_info']['phone']))
                    <div>{{ $content['personal_info']['phone'] }}</div>
                @endif
                @if(!empty($content['personal_info']['address']))
                    <div>{{ $content['personal_info']['address'] }}</div>
                @endif
                @if(!empty($content['personal_info']['linkedin']))
                    <div><a href="{{ $content['personal_info']['linkedin'] }}" class="accent-text">LinkedIn</a></div>
                @endif
                @if(!empty($content['personal_info']['website']))
                    <div><a href="{{ $content['personal_info']['website'] }}" class="accent-text">Portfolio</a></div>
                @endif
            </div>
        </header>

        <main>
            @if(!empty($content['professional_summary']))
                <section class="mb-8">
                    <h2 class="text-2xl font-bold section-title pb-2 mb-4">الملخص الاحترافي</h2>
                    <p class="text-gray-700">{{ $content['professional_summary'] }}</p>
                </section>
            @endif

            @if(!empty($content['work_experience']))
                <section class="mb-8">
                    <h2 class="text-2xl font-bold section-title pb-2 mb-4">الخبرة العملية</h2>
                    @foreach($content['work_experience'] as $experience)
                        <div class="mb-6">
                            <div class="flex justify-between items-baseline">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $experience['job_title'] ?? '' }}</h3>
                                <div class="text-sm text-gray-500">
                                    <span>{{ $experience['start_date'] ?? '' }} - {{ $experience['current'] ? 'الحاضر' : ($experience['end_date'] ?? '') }}</span>
                                </div>
                            </div>
                            <p class="text-md italic text-gray-600">{{ $experience['company'] ?? '' }} | {{ $experience['location'] ?? '' }}</p>
                            <ul class="list-disc list-inside mt-2 text-gray-700 space-y-1">
                                @if(!empty($experience['description']))
                                    @foreach(explode("\n", $experience['description']) as $line)
                                        @if(trim($line))
                                            <li>{{ $line }}</li>
                                        @endif
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    @endforeach
                </section>
            @endif

            @if(!empty($content['education']))
                <section class="mb-8">
                    <h2 class="text-2xl font-bold section-title pb-2 mb-4">التعليم</h2>
                    @foreach($content['education'] as $edu)
                        <div class="mb-4">
                            <div class="flex justify-between items-baseline">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $edu['degree'] ?? '' }}</h3>
                                <p class="text-sm text-gray-500">{{ $edu['graduation_date'] ?? '' }}</p>
                            </div>
                            <p class="text-md italic text-gray-600">{{ $edu['institution'] ?? '' }}</p>
                        </div>
                    @endforeach
                </section>
            @endif

            @if(!empty($content['technical_skills']) || !empty($content['soft_skills']))
                <section class="mb-8">
                    <h2 class="text-2xl font-bold section-title pb-2 mb-4">المهارات</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($content['technical_skills'] ?? [] as $skill)
                            <span class="accent-bg text-white px-3 py-1 rounded-full text-sm">{{ $skill }}</span>
                        @endforeach
                        @foreach($content['soft_skills'] ?? [] as $skill)
                            <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm">{{ $skill }}</span>
                        @endforeach
                    </div>
                </section>
            @endif
            
            @if(!empty($content['projects']))
                <section class="mb-8">
                    <h2 class="text-2xl font-bold section-title pb-2 mb-4">المشاريع</h2>
                    @foreach($content['projects'] as $project)
                        <div class="mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $project['project_name'] ?? '' }}</h3>
                            <p class="text-gray-700">{{ $project['description'] ?? '' }}</p>
                            @if(!empty($project['technologies']))
                                <p class="text-sm text-gray-500 mt-1"><strong>التقنيات:</strong> {{ $project['technologies'] }}</p>
                            @endif
                        </div>
                    @endforeach
                </section>
            @endif

        </main>
    </div>
</body>
</html>
