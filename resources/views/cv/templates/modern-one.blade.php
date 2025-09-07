<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $content['personal_info']['full_name'] ?? 'CV' }}</title>
    <style>
        /* Modern One Template - Clean and Professional with Blue Accents */
        html, body { 
            direction: ltr; 
            width: 794px; 
            margin: 0; 
            font-family: {{ $styling['font_family'] ?? 'Inter, Arial, sans-serif' }};
            background: #ffffff;
        }
        
        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
        }
        
        body { 
            color: #111; 
            padding: 48px; 
            font-size: 12px; 
            line-height: 1.45; 
            min-height: 1123px;
            overflow-wrap: break-word;
        }
        
        .header { 
            display: flex; 
            justify-content: space-between; 
            align-items: flex-end; 
            border-bottom: 3px solid #0ea5e9; 
            padding-bottom: 8px; 
            margin-bottom: 12px;
            gap: 20px;
        }
        
        .name { 
            font-size: 20px; 
            font-weight: 800; 
            letter-spacing: 0.2px;
            color: #111;
        }
        
        .role { 
            color: #0ea5e9; 
            font-weight: 600; 
            margin-top: 2px;
            font-size: 12px;
        }
        
        .contact { 
            text-align: right; 
            font-size: 11px;
            color: #374151;
            flex-shrink: 0;
        }
        
        .contact div {
            margin-bottom: 2px;
        }
        
        .section { 
            margin-top: 14px; 
        }
        
        .section h2 { 
            font-size: 12px; 
            font-weight: 700;
            text-transform: uppercase; 
            letter-spacing: 0.6px; 
            color: #0ea5e9; 
            border-bottom: 1px solid #e5e7eb; 
            padding-bottom: 4px; 
            margin: 16px 0 8px; 
        }
        
        .muted { 
            color: #374151; 
        }
        
        ul { 
            margin-left: 18px; 
        }
        
        .grid { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 10px 16px; 
        }
        
        .entry { 
            margin-bottom: 8px;
            page-break-inside: avoid;
        }
        
        .entry .meta { 
            display: flex; 
            justify-content: space-between; 
            color: #374151; 
            font-size: 11px;
            align-items: baseline;
            gap: 8px;
        }
        
        .entry strong {
            font-weight: 700;
            color: #111;
        }
        
        .entry .company {
            color: #475569;
            font-style: italic;
        }
        
        .entry .description {
            margin-top: 4px;
            color: #374151;
        }
        
        .pill { 
            display: inline-block; 
            background: #e0f2fe; 
            color: #0369a1; 
            padding: 2px 8px; 
            border-radius: 999px; 
            font-size: 10px; 
            margin-right: 6px; 
            margin-bottom: 4px;
            border: 1px solid #bae6fd;
        }
        
        p {
            margin-bottom: 6px;
            text-align: justify;
        }
        
        @media print { 
            body { 
                margin: 0.6in; 
                font-size: 11px; 
            } 
        }
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
                        @if(!empty($exp['current'])) - Present @elseif(!empty($exp['end_date'])) - {{ \Carbon\Carbon::parse($exp['end_date'])->format('M Y') }} @endif
                    </span>
                </div>
                <div class="company">{{ $exp['company'] ?? '' }}@if(!empty($exp['location'])) — {{ $exp['location'] }}@endif</div>
                @if(!empty($exp['description']))<div class="description">{{ $exp['description'] }}</div>@endif
                @if(!empty($exp['achievements']))<div class="description">{!! nl2br(e($exp['achievements'])) !!}</div>@endif
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
