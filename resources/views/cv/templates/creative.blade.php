<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content['personal_info']['full_name'] ?? 'CV' }} - Creative</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --accent-color: {{ $cv->accent_color ?? '#ef4444' }};
        }
        body {
            font-family: 'Tajawal', sans-serif;
        }
        .accent-text { color: var(--accent-color); }
        .accent-bg { background-color: var(--accent-color); }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto bg-white shadow-lg my-8">
        <div class="grid grid-cols-3">
            <!-- Sidebar -->
            <aside class="col-span-1 accent-bg text-white p-8">
                <div class="text-center mb-10">
                    <h1 class="text-3xl font-bold">{{ $content['personal_info']['full_name'] ?? '' }}</h1>
                    <p class="text-lg mt-1">{{ $content['personal_info']['target_role'] ?? '' }}</p>
                </div>

                <div class="space-y-6">
                    <div>
                        <h2 class="font-bold text-lg border-b-2 border-white pb-1 mb-2">التواصل</h2>
                        <div class="text-sm space-y-1">
                            @if(!empty($content['personal_info']['email']))
                                <p>{{ $content['personal_info']['email'] }}</p>
                            @endif
                            @if(!empty($content['personal_info']['phone']))
                                <p>{{ $content['personal_info']['phone'] }}</p>
                            @endif
                            @if(!empty($content['personal_info']['address']))
                                <p>{{ $content['personal_info']['address'] }}</p>
                            @endif
                            @if(!empty($content['personal_info']['linkedin']))
                                <p><a href="{{ $content['personal_info']['linkedin'] }}" class="hover:underline">LinkedIn</a></p>
                            @endif
                        </div>
                    </div>

                    @if(!empty($content['education']))
                        <div>
                            <h2 class="font-bold text-lg border-b-2 border-white pb-1 mb-2">التعليم</h2>
                            @foreach($content['education'] as $edu)
                                <div class="mb-3">
                                    <h3 class="font-bold">{{ $edu['degree'] ?? '' }}</h3>
                                    <p class="text-sm">{{ $edu['institution'] ?? '' }}</p>
                                    <p class="text-xs opacity-75">{{ $edu['graduation_date'] ?? '' }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if(!empty($content['technical_skills']))
                        <div>
                            <h2 class="font-bold text-lg border-b-2 border-white pb-1 mb-2">المهارات التقنية</h2>
                            <div class="flex flex-wrap gap-2 mt-2">
                                @foreach($content['technical_skills'] as $skill)
                                    <span class="bg-white/20 text-white px-2 py-1 rounded text-xs">{{ $skill }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </aside>

            <!-- Main Content -->
            <main class="col-span-2 p-8">
                @if(!empty($content['professional_summary']))
                    <section class="mb-8">
                        <h2 class="text-2xl font-bold accent-text border-b-2 border-gray-200 pb-2 mb-4">عني</h2>
                        <p class="text-gray-700">{{ $content['professional_summary'] }}</p>
                    </section>
                @endif

                @if(!empty($content['work_experience']))
                    <section class="mb-8">
                        <h2 class="text-2xl font-bold accent-text border-b-2 border-gray-200 pb-2 mb-4">الخبرة العملية</h2>
                        <div class="space-y-6">
                            @foreach($content['work_experience'] as $experience)
                                <div>
                                    <div class="flex justify-between items-baseline">
                                        <h3 class="text-lg font-bold text-gray-800">{{ $experience['job_title'] ?? '' }}</h3>
                                        <div class="text-sm text-gray-500">
                                            <span>{{ $experience['start_date'] ?? '' }} - {{ $experience['current'] ? 'الحاضر' : ($experience['end_date'] ?? '') }}</span>
                                        </div>
                                    </div>
                                    <p class="text-md italic text-gray-600">{{ $experience['company'] ?? '' }}</p>
                                    <ul class="list-disc list-inside mt-2 text-gray-700 text-sm space-y-1">
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
                        </div>
                    </section>
                @endif
                
                @if(!empty($content['projects']))
                    <section>
                        <h2 class="text-2xl font-bold accent-text border-b-2 border-gray-200 pb-2 mb-4">المشاريع</h2>
                        <div class="space-y-4">
                            @foreach($content['projects'] as $project)
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800">{{ $project['project_name'] ?? '' }}</h3>
                                    <p class="text-gray-700 text-sm">{{ $project['description'] ?? '' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            </main>
        </div>
    </div>
</body>
</html>
