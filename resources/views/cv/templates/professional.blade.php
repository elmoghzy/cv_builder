<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content['personal_info']['full_name'] ?? 'CV' }} - Professional</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --accent-color: {{ $cv->accent_color ?? '#1f2937' }};
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .name {
            font-size: 24pt;
            font-weight: bold;
            color: var(--accent-color);
        }
        .contact-info {
            margin-top: 5px;
            font-size: 11pt;
        }
        .section-title {
            font-size: 14pt;
            font-weight: bold;
            color: var(--accent-color);
            border-bottom: 1px solid var(--accent-color);
            padding-bottom: 5px;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .job-title {
            font-size: 13pt;
            font-weight: bold;
        }
        .company {
            font-style: italic;
        }
        .date-location {
            float: right;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body class="max-w-4xl mx-auto bg-white p-12">
    <div class="header">
        <div class="name">{{ $content['personal_info']['full_name'] ?? '' }}</div>
        <div class="contact-info">
            {{ $content['personal_info']['phone'] ?? '' }} | 
            {{ $content['personal_info']['email'] ?? '' }} | 
            {{ $content['personal_info']['address'] ?? '' }}
            @if(!empty($content['personal_info']['linkedin']))
                | <a href="{{ $content['personal_info']['linkedin'] }}">LinkedIn</a>
            @endif
        </div>
    </div>

    @if(!empty($content['professional_summary']))
        <div>
            <div class="section-title">ملخص احترافي</div>
            <p>{{ $content['professional_summary'] }}</p>
        </div>
    @endif

    @if(!empty($content['work_experience']))
        <div>
            <div class="section-title">الخبرة العملية</div>
            @foreach($content['work_experience'] as $exp)
                <div style="margin-bottom: 15px;">
                    <div>
                        <span class="job-title">{{ $exp['job_title'] ?? '' }}</span>
                        <span class="date-location">{{ $exp['start_date'] ?? '' }} - {{ $exp['current'] ? 'الحاضر' : ($exp['end_date'] ?? '') }}</span>
                    </div>
                    <div class="clear"></div>
                    <div>
                        <span class="company">{{ $exp['company'] ?? '' }}</span>
                        <span class="date-location">{{ $exp['location'] ?? '' }}</span>
                    </div>
                    <div class="clear"></div>
                    <ul style="list-style-type: disc; margin-right: 20px; margin-top: 5px;">
                        @if(!empty($exp['description']))
                            @foreach(explode("\n", $exp['description']) as $line)
                                @if(trim($line))
                                    <li>{{ $line }}</li>
                                @endif
                            @endforeach
                        @endif
                    </ul>
                </div>
            @endforeach
        </div>
    @endif

    @if(!empty($content['education']))
        <div>
            <div class="section-title">التعليم</div>
            @foreach($content['education'] as $edu)
                <div style="margin-bottom: 10px;">
                    <div>
                        <span class="job-title">{{ $edu['degree'] ?? '' }}</span>
                        <span class="date-location">{{ $edu['graduation_date'] ?? '' }}</span>
                    </div>
                    <div class="clear"></div>
                    <p class="company">{{ $edu['institution'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    @endif

    @if(!empty($content['technical_skills']))
        <div>
            <div class="section-title">المهارات التقنية</div>
            <p>{{ implode(', ', $content['technical_skills']) }}</p>
        </div>
    @endif

</body>
</html>
