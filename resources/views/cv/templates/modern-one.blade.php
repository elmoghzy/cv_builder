<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content['personal_info']['full_name'] ?? 'CV' }}</title>
    <style>
        /* ATS-Compliant Modern Template */
        html, body { direction: ltr; unicode-bidi: isolate; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: {{ $styling['font_family'] ?? 'Inter, Arial, sans-serif' }};
            font-size: 11px;
            line-height: 1.45;
            color: #111;
            background-color: #ffffff;
            margin: 0.75in;
            max-width: 8.27in;
            overflow-wrap: anywhere;
            word-break: break-word;
            hyphens: auto;
        }
        
        /* Modern Header with Blue Accent */
        .header { 
            display: flex; 
            justify-content: space-between; 
            align-items: flex-end; 
            border-bottom: 3px solid #0ea5e9; 
            padding-bottom: 8px; 
            margin-bottom: 16px; 
        }
        .name { font-size: 18px; font-weight: 700; letter-spacing: .2px; text-align: left; }
        .role { color: #0ea5e9; font-weight: 600; margin-top: 2px; font-size: 12px; }
        .contact { text-align: right; font-size: 11px; color: #374151; }
        .contact div { margin-bottom: 2px; overflow-wrap: anywhere; }
        
        /* Section Headers */
        h2 { 
            font-size: 13px; 
            font-weight: 700;
            margin-top: 16px;
            margin-bottom: 8px;
            color: #0ea5e9; 
            text-transform: uppercase; 
            border-bottom: 1px solid #e5e7eb; 
            padding-bottom: 3px; 
            letter-spacing: .6px;
        }
        
        h3 {
            font-size: 12px;
            font-weight: bold;
            margin-top: 8px;
            margin-bottom: 4px;
            color: #111;
        }
        
        /* Content */
        p { margin-bottom: 6px; }
        ul { margin-left: 18px; margin-bottom: 8px; padding-left: 0; }
        li { margin-bottom: 2px; }
        
        /* Experience/Education Entries */
        .experience-entry, .education-entry { margin-bottom: 12px; page-break-inside: avoid; }
        .entry-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: baseline; 
            margin-bottom: 2px; 
            gap: 8px; 
        }
        .entry-title { font-weight: bold; }
        .entry-date { font-style: italic; color: #666; white-space: nowrap; font-size: 10px; }
        .entry-company, .entry-location { margin-bottom: 4px; color: #374151; }
        
        /* Skills */
        .skills-section { margin-bottom: 8px; }
        .skills-category { margin-bottom: 6px; }
        .skills-category strong { display: inline-block; min-width: 120px; }
        
        @media print { body { margin: 0.75in; font-size: 11px; } }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="name">{{ $content['personal_info']['full_name'] ?? 'Full Name' }}</div>
            @if(!empty($content['target_role']))
                <div class="role">{{ $content['target_role'] }}</div>
            @endif
        </div>
        <div class="contact">
            @if(!empty($content['personal_info']['email']))<div>{{ $content['personal_info']['email'] }}</div>@endif
            @if(!empty($content['personal_info']['phone']))<div>{{ $content['personal_info']['phone'] }}</div>@endif
            @if(!empty($content['personal_info']['linkedin']))<div>{{ $content['personal_info']['linkedin'] }}</div>@endif
            @if(!empty($content['personal_info']['website']))<div>{{ $content['personal_info']['website'] }}</div>@endif
        </div>
    </div>

    @if(!empty($content['professional_summary']))
    <div class="section">
        <h2>Summary</h2>
        <p class="muted">{{ $content['professional_summary'] }}</p>
    </div>
    @endif

    @if(!empty($content['work_experience']))
    <div class="section">
        <h2>Experience</h2>
        @foreach($content['work_experience'] as $exp)
            <div class="entry">
                <div class="meta">
                    <strong>{{ $exp['job_title'] ?? '' }}</strong>
                    <span>
                        {{ isset($exp['start_date']) ? \Carbon\Carbon::parse($exp['start_date'])->format('M Y') : '' }}
                        @if(!empty($exp['current']))@elseif(!empty($exp['end_date'])) - {{ \Carbon\Carbon::parse($exp['end_date'])->format('M Y') }} @endif
                    </span>
                </div>
                <div class="muted">{{ $exp['company'] ?? '' }} @if(!empty($exp['location'])) — {{ $exp['location'] }} @endif</div>
                @if(!empty($exp['description']))<p>{{ $exp['description'] }}</p>@endif
                @if(!empty($exp['achievements']))<ul>{!! nl2br(e($exp['achievements'])) !!}</ul>@endif
            </div>
        @endforeach
    </div>
    @endif

    @if(!empty($content['education']))
    <div class="section">
        <h2>Education</h2>
        <div class="grid">
        @foreach($content['education'] as $ed)
            <div>
                <strong>{{ $ed['degree'] ?? '' }}</strong>
                <div class="muted">{{ $ed['institution'] ?? '' }} @if(!empty($ed['location'])) — {{ $ed['location'] }} @endif</div>
                @if(!empty($ed['graduation_date']))<div class="muted">{{ \Carbon\Carbon::parse($ed['graduation_date'])->format('M Y') }}</div>@endif
            </div>
        @endforeach
        </div>
    </div>
    @endif

    @if(!empty($content['technical_skills']) || !empty($content['soft_skills']) || !empty($content['languages']))
    <div class="section">
        <h2>Skills</h2>
        <div>
            @if(!empty($content['technical_skills']))
                <div class="muted" style="margin-bottom:6px;">
                    @foreach((array)$content['technical_skills'] as $s)<span class="pill">{{ $s }}</span>@endforeach
                </div>
            @endif
            @if(!empty($content['soft_skills']))
                <div class="muted" style="margin-bottom:6px;">
                    @foreach((array)$content['soft_skills'] as $s)<span class="pill">{{ $s }}</span>@endforeach
                </div>
            @endif
            @if(!empty($content['languages']))
                <div class="muted">
                    @foreach((array)$content['languages'] as $s)<span class="pill">{{ $s }}</span>@endforeach
                </div>
            @endif
        </div>
    </div>
    @endif

    @if(!empty($content['projects']))
    <div class="section">
        <h2>Projects</h2>
        @foreach($content['projects'] as $proj)
            <div class="entry">
                <div class="meta">
                    <strong>{{ $proj['project_name'] ?? '' }}</strong>
                    @if(!empty($proj['duration']))<span>{{ $proj['duration'] }}</span>@endif
                </div>
                @if(!empty($proj['description']))<p class="muted">{{ $proj['description'] }}</p>@endif
                @if(!empty($proj['technologies']))<p class="muted"><strong>Technologies:</strong> {{ $proj['technologies'] }}</p>@endif
                @if(!empty($proj['url']))<p class="muted"><strong>URL:</strong> {{ $proj['url'] }}</p>@endif
            </div>
        @endforeach
    </div>
    @endif

    @if(!empty($content['certifications']))
    <div class="section">
        <h2>Certifications</h2>
        @foreach($content['certifications'] as $c)
            <div class="entry">
                <div class="meta"><strong>{{ $c['name'] ?? '' }}</strong>@if(!empty($c['date']))<span>{{ \Carbon\Carbon::parse($c['date'])->format('M Y') }}</span>@endif</div>
                @if(!empty($c['issuer']))<div class="muted">{{ $c['issuer'] }}</div>@endif
                @if(!empty($c['credential_id']))<div class="muted">Credential ID: {{ $c['credential_id'] }}</div>@endif
            </div>
        @endforeach
    </div>
    @endif
</body>
</html>
