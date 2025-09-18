<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content['personal_info']['full_name'] ?? 'CV' }}</title>
    <style>
        /* Professional Simple Template - Executive Style */
        html, body { direction: ltr; unicode-bidi: isolate; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: {{ $styling['font_family'] ?? 'Calibri, Arial, sans-serif' }};
            font-size: 11px;
            line-height: 1.5;
            color: #111;
            background-color: #ffffff;
            /* reduced margins and allow full printable width so content stretches */
            margin: 0.5in;
            max-width: none;
            width: auto;
            /* better wrapping for very long/continuous strings */
            overflow-wrap: break-word;
            word-wrap: break-word;
            word-break: break-word;
            hyphens: auto;
            white-space: normal;
            padding-left: 0.25in;
            padding-right: 0.25in;
        }
        
        /* Executive Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #2c3e50;
        }
        
        .name {
            font-size: 24px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }
        
        .professional-title {
            font-size: 14px;
            color: #34495e;
            font-weight: 500;
            margin-bottom: 12px;
            font-style: italic;
        }
        
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 8px;
            justify-items: start; /* align contact items to left to avoid narrow centered columns */
            font-size: 10px;
            color: #555;
            word-wrap: break-word;
        }
        
        /* Section Headers */
        h2 {
            font-size: 14px;
            font-weight: 700;
            color: #2c3e50;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #3498db;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        h3 {
            font-size: 12px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 4px;
            color: #2c3e50;
        }
        
        /* Content */
        p { margin-bottom: 8px; }
        ul { margin-left: 20px; margin-bottom: 10px; padding-left: 0; }
        li { margin-bottom: 3px; }
        
        /* Professional Entries */
        .entry {
            margin-bottom: 15px;
            page-break-inside: avoid;
            padding-left: 8px;
            border-left: 2px solid #ecf0f1;
        }
        
        .entry-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 4px;
            gap: 10px;
        }
        
        .entry-title {
            font-weight: bold;
            font-size: 12px;
            color: #2c3e50;
        }
        
        .entry-date {
            font-size: 10px;
            color: #7f8c8d;
            white-space: nowrap;
            font-weight: 500;
        }
        
        .entry-company {
            font-size: 11px;
            color: #34495e;
            margin-bottom: 4px;
            font-weight: 500;
        }
        
        .entry-location {
            font-size: 10px;
            color: #7f8c8d;
            margin-bottom: 6px;
        }
        
        .entry-description {
            margin-bottom: 6px;
            text-align: justify;
            overflow-wrap: break-word;
            word-wrap: break-word;
            word-break: break-word;
            white-space: normal;
        }
        
        /* Skills Professional */
        .skills-professional {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 12px;
        }
        
        .skill-group {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            border-left: 4px solid #3498db;
        }
        
        .skill-group strong {
            color: #2c3e50;
            display: block;
            margin-bottom: 4px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Summary Professional */
        .summary-professional {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            border-left: 4px solid #2c3e50;
            margin-bottom: 10px;
            text-align: justify;
            font-style: italic;
            overflow-wrap: break-word;
            word-wrap: break-word;
            word-break: break-word;
            white-space: normal;
        }
        
        @media print {
            body { margin: 0.75in; font-size: 11px; }
            .header { page-break-after: avoid; }
            .entry { page-break-inside: avoid; }
            .skill-group { background: #f5f5f5; }
            .summary-professional { background: #f5f5f5; }
        }
    </style>
</head>
<body>
    <?php
    // Backward-compatible mappings: allow old/legacy keys to still render
    $__cv_summary = $content['summary'] ?? $content['professional_summary'] ?? null;
    // Career objective may be stored under different keys in older data
    $__cv_objective = $content['career_objective'] ?? $content['objective'] ?? $content['career_summary'] ?? null;
    $__cv_skills = $content['skills'] ?? $content['technical_skills'] ?? $content['skill_groups'] ?? null;
    $__cv_experience = $content['experience'] ?? $content['work_experience'] ?? null;
    $__cv_education = $content['education'] ?? $content['qualifications'] ?? null;
    $__cv_projects = $content['projects'] ?? $content['project_list'] ?? null;
    $__cv_certifications = $content['certifications'] ?? $content['professional_certifications'] ?? null;
    $__cv_languages = $content['languages'] ?? $content['language_skills'] ?? null;

    // Safe string caster to avoid htmlspecialchars on arrays/objects
    $S = function ($v) {
        if (is_array($v)) {
            if (array_key_exists('text', $v) && is_string($v['text'])) {
                return $v['text'];
            }
            $flat = [];
            $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($v));
            foreach ($it as $vv) {
                if (is_scalar($vv)) { $flat[] = (string) $vv; }
            }
            return implode(', ', array_filter(array_unique($flat)));
        }
        if (is_object($v)) {
            return method_exists($v, '__toString') ? (string) $v : json_encode($v, JSON_UNESCAPED_UNICODE);
        }
        return (string) ($v ?? '');
    };
    ?>
    <!-- Executive Header -->
    <header class="header">
        <h1 class="name">{{ $content['personal_info']['full_name'] ?? 'Full Name' }}</h1>
        @if(!empty($content['personal_info']['title']))
        <div class="professional-title">{{ $content['personal_info']['title'] }}</div>
        @endif
        <div class="contact-grid">
            @php($pi = $content['personal_info'] ?? [])
            @if(!empty($pi['email']))
            <div>{{ $pi['email'] }}</div>
            @endif
            @if(!empty($pi['phone']))
            <div>{{ $pi['phone'] }}</div>
            @endif
            @php($loc = $pi['location'] ?? ($pi['address'] ?? null))
            @if(!empty($loc))
            <div>{{ $loc }}</div>
            @endif
            @if(!empty($pi['linkedin']))
            <div>{{ $pi['linkedin'] }}</div>
            @endif
            @if(!empty($pi['website']))
            <div>{{ $pi['website'] }}</div>
            @endif
            @if(!empty($pi['github']))
            <div>{{ $pi['github'] }}</div>
            @endif
        </div>
    </header>

    <!-- Professional Summary -->
    @if(!empty($__cv_summary))
    <section>
        <h2>PROFESSIONAL SUMMARY</h2>
        <div class="summary-professional">{{ $__cv_summary }}</div>
    </section>
    @endif

    <!-- Career Objective -->
    @if(!empty($__cv_objective))
    <section>
        <h2>CAREER OBJECTIVE</h2>
        <div class="summary-professional">{{ $__cv_objective }}</div>
    </section>
    @endif

    <!-- Work Experience -->
    @if(!empty($__cv_experience) && is_array($__cv_experience))
    <section>
        <h2>WORK EXPERIENCE</h2>
        @foreach($__cv_experience as $exp)
        <div class="entry">
            <div class="entry-header">
                <div class="entry-title">{{ $S($exp['position'] ?? ($exp['job_title'] ?? 'Position Title')) }}</div>
                <div class="entry-date">{{ $S($exp['start_date'] ?? '') }}@if(!empty($exp['end_date'])) - {{ $S($exp['end_date']) }}@endif</div>
            </div>
            <div class="entry-company">{{ $S($exp['company'] ?? 'Company Name') }}</div>
            @if(!empty($exp['location']))
            <div class="entry-location">{{ $S($exp['location']) }}</div>
            @endif
            @if(!empty($exp['description']))
            <div class="entry-description">{{ $S($exp['description']) }}</div>
            @endif
            @if(!empty($exp['achievements']))
                @php($ach = $exp['achievements'])
                @if(is_array($ach))
                    <ul>
                        @foreach($ach as $achievement)
                        <li>{{ $S($achievement) }}</li>
                        @endforeach
                    </ul>
                @elseif(is_string($ach))
                    @php($achLines = array_filter(preg_split("/\r?\n/", $ach)))
                    @if(!empty($achLines))
                        <ul>
                            @foreach($achLines as $line)
                            <li>{{ $S($line) }}</li>
                            @endforeach
                        </ul>
                    @endif
                @endif
            @endif
        </div>
        @endforeach
    </section>
    @endif

    <!-- Education -->
    @if(!empty($__cv_education) && is_array($__cv_education))
    <section>
        <h2>EDUCATION</h2>
    @foreach($__cv_education as $edu)
        <div class="entry">
            <div class="entry-header">
        <div class="entry-title">{{ $S($edu['degree'] ?? 'Degree') }}</div>
        <div class="entry-date">{{ $S($edu['graduation_date'] ?? 'Year') }}</div>
            </div>
        <div class="entry-company">{{ $S($edu['institution'] ?? 'Institution') }}</div>
            @if(!empty($edu['location']))
        <div class="entry-location">{{ $S($edu['location']) }}</div>
            @endif
            @if(!empty($edu['gpa']))
        <div class="entry-description">GPA: {{ $S($edu['gpa']) }}</div>
            @endif
            @if(!empty($edu['honors']))
        <div class="entry-description">{{ $S($edu['honors']) }}</div>
            @endif
            @if(!empty($edu['relevant_coursework']))
        <div class="entry-description">Relevant Coursework: {{ $S($edu['relevant_coursework']) }}</div>
            @endif
        </div>
        @endforeach
    </section>
    @endif

    <!-- Core Competencies / Skills -->
    @if(!empty($__cv_skills))
    <section>
        <h2>SKILLS</h2>
        <div class="skills-professional">
            @if(is_array($__cv_skills))
                @foreach($__cv_skills as $skillGroup)
                    @if(is_array($skillGroup) && isset($skillGroup['category']) && isset($skillGroup['skills']))
                    <div class="skill-group">
                        <strong>{{ $skillGroup['category'] }}</strong>
                        <div>{{ is_array($skillGroup['skills']) ? implode(' • ', $skillGroup['skills']) : $skillGroup['skills'] }}</div>
                    </div>
                    @endif
                @endforeach
            @else
                <div class="skill-group">
                    <strong>Skills</strong>
                    <div>{{ $__cv_skills }}</div>
                </div>
            @endif
        </div>
    </section>
    @endif

    <!-- Notable Projects -->
    @if(!empty($__cv_projects) && is_array($__cv_projects))
    <section>
        <h2>Notable Projects</h2>
        @foreach($__cv_projects as $project)
        <div class="entry">
            <h3>{{ $S($project['name'] ?? 'Project Name') }}</h3>
            @if(!empty($project['date']))
            <div class="entry-date">{{ $S($project['date']) }}</div>
            @endif
            @if(!empty($project['description']))
            <div class="entry-description">{{ $S($project['description']) }}</div>
            @endif
            @if(!empty($project['technologies']))
            <div class="entry-description"><strong>Technologies:</strong> {{ is_array($project['technologies']) ? implode(', ', $project['technologies']) : $project['technologies'] }}</div>
            @endif
            @if(!empty($project['url']))
            <div class="entry-description"><strong>URL:</strong> {{ $S($project['url']) }}</div>
            @endif
        </div>
        @endforeach
    </section>
    @endif

    <!-- Professional Certifications -->
    @if(!empty($__cv_certifications) && is_array($__cv_certifications))
    <section>
        <h2>Professional Certifications</h2>
        @foreach($__cv_certifications as $cert)
        <div class="entry">
            <div class="entry-header">
                <div class="entry-title">{{ $S($cert['name'] ?? 'Certification Name') }}</div>
                @if(!empty($cert['date']))
                <div class="entry-date">{{ $S($cert['date']) }}</div>
                @endif
            </div>
            @if(!empty($cert['issuer']))
            <div class="entry-company">{{ $S($cert['issuer']) }}</div>
            @endif
            @if(!empty($cert['credential_id']))
            <div class="entry-description"><strong>Credential ID:</strong> {{ $S($cert['credential_id']) }}</div>
            @endif
        </div>
        @endforeach
    </section>
    @endif

    <!-- Languages -->
    @if(!empty($__cv_languages) && is_array($__cv_languages))
    <section>
        <h2>Languages</h2>
        <div class="skills-professional">
            @foreach($__cv_languages as $lang)
            <div class="skill-group">
                @if(is_array($lang))
                <strong>{{ $S($lang['language'] ?? 'Language') }}</strong>
                <div>{{ $S($lang['proficiency'] ?? 'Proficiency Level') }}</div>
                @else
                <div>{{ $S($lang) }}</div>
                @endif
            </div>
            @endforeach
        </div>
    </section>
    @endif
</body>
</html>
