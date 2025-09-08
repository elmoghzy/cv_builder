<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content['personal_info']['full_name'] ?? 'CV' }}</title>
    <style>
        /* ATS-Compliant Elegant Template */
        html, body { direction: ltr; unicode-bidi: isolate; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: {{ $styling['font_family'] ?? 'Georgia, Times New Roman, serif' }};
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
        
        /* Elegant Centered Header */
        .header { text-align: ; margin-bottom: 16px; }
        .name { font-size: 20px; font-weight: 700; letter-spacing: .5px; margin-bottom: 4px; }
        .rule { height: 2px; background: #111; width: 80px; margin: 8px auto; }
        .contact { color: #444; font-size: 11px; }
        .contact p { margin-bottom: 3px; overflow-wrap: anywhere; }
        
        /* Section Headers */
        h2 { 
            font-size: 13px; 
            font-weight: 700;
            margin-top: 16px;
            margin-bottom: 8px;
            color: #111;
            text-transform: uppercase; 
            letter-spacing: .8px;
            text-align: center;
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
        .entry-company, .entry-location { margin-bottom: 4px; color: #444; }
        
        /* Skills */
        .skills-section { margin-bottom: 8px; }
        .skills-category { margin-bottom: 6px; }
        .skills-category strong { display: inline-block; min-width: 120px; }
        
        @media print { body { margin: 0.75in; font-size: 11px; } }
    </style>
</head>
<body>
    <!-- Header/Personal Information -->
    <div class="header">
        <div class="name">{{ $content['personal_info']['full_name'] ?? 'Full Name' }}</div>
        <div class="rule"></div>
        <div class="contact">
            @if(isset($content['personal_info']['email']) && !empty($content['personal_info']['email']))
                <p>{{ $content['personal_info']['email'] }}</p>
            @endif
            @if(isset($content['personal_info']['phone']) && !empty($content['personal_info']['phone']))
                <p>{{ $content['personal_info']['phone'] }}</p>
            @endif
            @if(isset($content['personal_info']['address']) && !empty($content['personal_info']['address']))
                <p>{{ $content['personal_info']['address'] }}</p>
            @endif
            @if(isset($content['personal_info']['linkedin']) && !empty($content['personal_info']['linkedin']))
                <p>{{ $content['personal_info']['linkedin'] }}</p>
            @endif
            @if(isset($content['personal_info']['website']) && !empty($content['personal_info']['website']))
                <p>{{ $content['personal_info']['website'] }}</p>
            @endif
        </div>
    </div>

    <!-- Professional Summary -->
    @if(isset($content['professional_summary']) && !empty($content['professional_summary']))
    <h2>Profile</h2>
    <p>{{ $content['professional_summary'] }}</p>
    @endif

    <!-- Career Objective -->
    @if(isset($content['objective']) && !empty($content['objective']))
    <h2>Career Objective</h2>
    <p>{{ $content['objective'] }}</p>
    @endif

    <!-- Work Experience -->
    @if(isset($content['work_experience']) && is_array($content['work_experience']) && count($content['work_experience']) > 0)
    <h2>Experience</h2>
    @foreach($content['work_experience'] as $experience)
        @if(isset($experience['job_title']) && isset($experience['company']))
        <div class="experience-entry">
            <div class="entry-header">
                <div class="entry-title">{{ $experience['job_title'] }}</div>
                <div class="entry-date">
                    {{ isset($experience['start_date']) ? \Carbon\Carbon::parse($experience['start_date'])->format('M Y') : '' }}
                    @if(isset($experience['current']) && $experience['current'])
                        
                    @elseif(isset($experience['end_date']))
                        - {{ \Carbon\Carbon::parse($experience['end_date'])->format('M Y') }}
                    @endif
                </div>
            </div>
            <p class="entry-company">
                <strong>{{ $experience['company'] }}</strong>
                @if(isset($experience['location']) && !empty($experience['location']))
                    - {{ $experience['location'] }}
                @endif
            </p>
            @if(isset($experience['description']) && !empty($experience['description']))
                <p>{{ $experience['description'] }}</p>
            @endif
            @if(isset($experience['achievements']) && !empty($experience['achievements']))
                <div>{!! nl2br(e($experience['achievements'])) !!}</div>
            @endif
        </div>
        @endif
    @endforeach
    @endif

    <!-- Education -->
    @if(isset($content['education']) && is_array($content['education']) && count($content['education']) > 0)
    <h2>Education</h2>
    @foreach($content['education'] as $education)
        @if(isset($education['degree']) && isset($education['institution']))
        <div class="education-entry">
            <div class="entry-header">
                <div class="entry-title">{{ $education['degree'] }}</div>
                <div class="entry-date">
                    @if(isset($education['graduation_date']))
                        {{ \Carbon\Carbon::parse($education['graduation_date'])->format('M Y') }}
                    @endif
                </div>
            </div>
            <p class="entry-company">
                <strong>{{ $education['institution'] }}</strong>
                @if(isset($education['location']) && !empty($education['location']))
                    - {{ $education['location'] }}
                @endif
            </p>
            @if(isset($education['gpa']) && !empty($education['gpa']))
                <p>GPA: {{ $education['gpa'] }}</p>
            @endif
            @if(isset($education['honors']) && !empty($education['honors']))
                <p>{{ $education['honors'] }}</p>
            @endif
        </div>
        @endif
    @endforeach
    @endif

    <!-- Skills -->
    @if(isset($content['technical_skills']) || isset($content['soft_skills']) || isset($content['languages']))
    <h2>Skills</h2>
    <div class="skills-section">
        @if(isset($content['technical_skills']) && !empty($content['technical_skills']))
        <div class="skills-category">
            <strong>Technical Skills:</strong> {{ is_array($content['technical_skills']) ? implode(', ', $content['technical_skills']) : $content['technical_skills'] }}
        </div>
        @endif
        
        @if(isset($content['soft_skills']) && !empty($content['soft_skills']))
        <div class="skills-category">
            <strong>Soft Skills:</strong> {{ is_array($content['soft_skills']) ? implode(', ', $content['soft_skills']) : $content['soft_skills'] }}
        </div>
        @endif
        
        @if(isset($content['languages']) && !empty($content['languages']))
        <div class="skills-category">
            <strong>Languages:</strong> {{ is_array($content['languages']) ? implode(', ', $content['languages']) : $content['languages'] }}
        </div>
        @endif
    </div>
    @endif

    <!-- Projects -->
    @if(isset($content['projects']) && is_array($content['projects']) && count($content['projects']) > 0)
    <h2>Key Projects</h2>
    @foreach($content['projects'] as $project)
        @if(isset($project['project_name']))
        <div class="experience-entry">
            <h3>{{ $project['project_name'] }}</h3>
            @if(isset($project['description']) && !empty($project['description']))
                <p>{{ $project['description'] }}</p>
            @endif
            @if(isset($project['technologies']) && !empty($project['technologies']))
                <p><strong>Technologies:</strong> {{ $project['technologies'] }}</p>
            @endif
            @if(isset($project['url']) && !empty($project['url']))
                <p><strong>URL:</strong> {{ $project['url'] }}</p>
            @endif
        </div>
        @endif
    @endforeach
    @endif

    <!-- Certifications -->
    @if(isset($content['certifications']) && is_array($content['certifications']) && count($content['certifications']) > 0)
    <h2>Certifications</h2>
    @foreach($content['certifications'] as $cert)
        @if(isset($cert['name']))
        <div class="experience-entry">
            <div class="entry-header">
                <div class="entry-title">{{ $cert['name'] }}</div>
                <div class="entry-date">
                    @if(isset($cert['date']))
                        {{ \Carbon\Carbon::parse($cert['date'])->format('M Y') }}
                    @endif
                </div>
            </div>
            @if(isset($cert['issuer']) && !empty($cert['issuer']))
                <p><strong>{{ $cert['issuer'] }}</strong></p>
            @endif
            @if(isset($cert['credential_id']) && !empty($cert['credential_id']))
                <p>Credential ID: {{ $cert['credential_id'] }}</p>
            @endif
        </div>
        @endif
    @endforeach
    @endif
</body>
</html>
