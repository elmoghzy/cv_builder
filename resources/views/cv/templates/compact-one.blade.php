<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content['personal_info']['full_name'] ?? 'CV' }}</title>
    <style>
        /* Compact Professional Template - ATS Compliant */
        html, body { direction: ltr; unicode-bidi: isolate; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: {{ $styling['font_family'] ?? 'Arial, Helvetica, sans-serif' }};
            font-size: 10px;
            line-height: 1.4;
            color: #111;
            background-color: #ffffff;
            /* reduce side margins so content can expand */
            margin: 0.35in 0.35in;
            /* allow full width usage for one-page layout */
            max-width: 100%;
            overflow-wrap: anywhere;
            word-break: break-word;
            hyphens: auto;
        }
        
        /* Compact Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #333;
        }
        
        .name-section {
            flex: 1;
        }
        
        .name {
            font-size: 20px;
            font-weight: 700;
            color: #111;
            margin-bottom: 2px;
        }
        
        .title {
            font-size: 12px;
            color: #666;
            font-weight: 500;
        }
        
        .contact-info {
            text-align: right;
            font-size: 9px;
            color: #444;
            line-height: 1.3;
        }
        
        .contact-info div {
            margin-bottom: 1px;
        }
        
        /* Two Column Layout */
        .main-content {
            display: grid;
            /* stack columns vertically: left column first, then right column */
            grid-template-columns: 1fr;
            gap: 12px;
        }

        /* ensure the right column has breathing room when stacked */
        .right-column {
            margin-top: 6px;
        }
        
        /* Section Headers */
        h2 {
            font-size: 11px;
            font-weight: 700;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin: 10px 0 6px 0;
            padding-bottom: 2px;
            border-bottom: 1px solid #ddd;
        }
        
        h3 {
            font-size: 10px;
            font-weight: bold;
            margin-top: 6px;
            margin-bottom: 2px;
            color: #111;
        }

        /* Center content under each section heading */
        section * {
            /* text-align: center; */
        }

        /* Keep lists readable: center the list block but left-align items */
        section ul {
            display: inline-block;
            text-align: left;
            margin: 6px 0;
        }

        /* Make entry headers stack and center when centered layout is applied */
        .entry-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            margin-bottom: 1px;
        }
        
        /* Content */
        p { margin-bottom: 4px; font-size: 10px; }
        ul { margin-left: 12px; margin-bottom: 6px; padding-left: 0; }
        li { margin-bottom: 1px; font-size: 9px; }
        
        /* Compact Entries */
        .entry {
            margin-bottom: 8px;
            page-break-inside: avoid;
        }
        
        .entry-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 1px;
            gap: 4px;
        }
        
        .entry-title {
            font-weight: bold;
            font-size: 10px;
        }
        
        .entry-date {
            font-size: 8px;
            color: #666;
            white-space: nowrap;
        }
        
        .entry-company {
            font-size: 9px;
            color: #444;
            margin-bottom: 2px;
        }
        
        .entry-description {
            font-size: 9px;
            margin-bottom: 3px;
        }
        
        /* Skills Compact */
        .skills-compact {
            font-size: 9px;
        }
        
        .skill-category {
            margin-bottom: 3px;
        }
        
        .skill-category strong {
            color: #333;
            display: inline-block;
            min-width: 60px;
        }
        
        /* Summary */
        .summary {
            font-size: 10px;
            margin-bottom: 8px;
            text-align: justify;
        }
        
        @media print {
            body { margin: 0.5in 0.75in; font-size: 10px; }
            .header { page-break-after: avoid; }
            .entry { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    <?php
        // Backward-compatible mappings: allow old/legacy keys to still render
        $__cv_summary = $content['summary'] ?? $content['professional_summary'] ?? null;
        $__cv_skills = $content['skills'] ?? $content['technical_skills'] ?? $content['skill_groups'] ?? null;
        $__cv_experience = $content['experience'] ?? $content['work_experience'] ?? null;
        $__cv_education = $content['education'] ?? $content['qualifications'] ?? null;
        $__cv_projects = $content['projects'] ?? $content['project_list'] ?? null;
        $__cv_certifications = $content['certifications'] ?? $content['professional_certifications'] ?? null;
        $__cv_languages = $content['languages'] ?? $content['language_skills'] ?? null;
    ?>
    <!-- Header Section -->
    <header class="header">
        <div class="name-section">
            <h1 class="name">{{ $content['personal_info']['full_name'] ?? 'Full Name' }}</h1>
            @if(!empty($content['personal_info']['title']))
            <div class="title">{{ $content['personal_info']['title'] }}</div>
            @endif
        </div>
        <div class="contact-info">
            @if(!empty($content['personal_info']['email']))
            <div>{{ $content['personal_info']['email'] }}</div>
            @endif
            @if(!empty($content['personal_info']['phone']))
            <div>{{ $content['personal_info']['phone'] }}</div>
            @endif
            @if(!empty($content['personal_info']['location']))
            <div>{{ $content['personal_info']['location'] }}</div>
            @endif
            @if(!empty($content['personal_info']['linkedin']))
            <div>{{ $content['personal_info']['linkedin'] }}</div>
            @endif
            @if(!empty($content['personal_info']['website']))
            <div>{{ $content['personal_info']['website'] }}</div>
            @endif
        </div>
    </header>

    <div class="main-content">
        <!-- Left Column -->
        <div class="left-column">
            <!-- Professional Summary -->
            @if(!empty($__cv_summary))
            <section>
                <h2>Professional Summary</h2>
                <div class="summary">{{ $__cv_summary }}</div>
            </section>
            @endif

            <!-- Experience Section -->
            @if(!empty($__cv_experience) && is_array($__cv_experience))
            <section>
                <h2>Professional Experience</h2>
                @foreach($__cv_experience as $exp)
                <div class="entry">
                    <div class="entry-header">
                        <div class="entry-title">{{ $exp['position'] ?? 'Position Title' }}</div>
                        <div class="entry-date">{{ $exp['start_date'] ?? '' }}@if(!empty($exp['end_date'])) - {{ $exp['end_date'] }}@endif</div>
                    </div>
                    <div class="entry-company">{{ $exp['company'] ?? 'Company Name' }}@if(!empty($exp['location'])), {{ $exp['location'] }}@endif</div>
                    @if(!empty($exp['description']))
                    <div class="entry-description">{{ $exp['description'] }}</div>
                    @endif
                    @if(!empty($exp['achievements']) && is_array($exp['achievements']))
                    <ul>
                        @foreach(array_slice($exp['achievements'], 0, 3) as $achievement)
                        <li>{{ $achievement }}</li>
                        @endforeach
                    </ul>
                    @endif
                </div>
                @endforeach
            </section>
            @endif

            <!-- Projects Section -->
            @if(!empty($__cv_projects) && is_array($__cv_projects))
            <section>
                <h2>Key Projects</h2>
                @foreach(array_slice($__cv_projects, 0, 2) as $project)
                <div class="entry">
                    <h3>{{ $project['name'] ?? 'Project Name' }}</h3>
                    @if(!empty($project['description']))
                    <div class="entry-description">{{ $project['description'] }}</div>
                    @endif
                    @if(!empty($project['technologies']))
                    <div class="entry-description"><strong>Tech:</strong> {{ is_array($project['technologies']) ? implode(', ', $project['technologies']) : $project['technologies'] }}</div>
                    @endif
                </div>
                @endforeach
            </section>
            @endif
        </div>

        <!-- Right Column -->
        <div class="right-column">
            <!-- Education Section -->
            @if(!empty($__cv_education) && is_array($__cv_education))
            <section>
                <h2>Education</h2>
                @foreach($__cv_education as $edu)
                <div class="entry">
                    <div class="entry-title">{{ $edu['degree'] ?? 'Degree' }}</div>
                    <div class="entry-company">{{ $edu['institution'] ?? 'Institution' }}</div>
                    <div class="entry-date">{{ $edu['graduation_date'] ?? 'Year' }}</div>
                    @if(!empty($edu['gpa']))
                    <div class="entry-description">GPA: {{ $edu['gpa'] }}</div>
                    @endif
                </div>
                @endforeach
            </section>
            @endif

            <!-- Skills Section -->
            @if(!empty($__cv_skills))
            <section>
                <h2>Core Skills</h2>
                <div class="skills-compact">
                    @if(is_array($__cv_skills))
                        @foreach($__cv_skills as $skillGroup)
                            @if(is_array($skillGroup) && isset($skillGroup['category']) && isset($skillGroup['skills']))
                            <div class="skill-category">
                                <strong>{{ $skillGroup['category'] }}:</strong> {{ is_array($skillGroup['skills']) ? implode(', ', $skillGroup['skills']) : $skillGroup['skills'] }}
                            </div>
                            @endif
                        @endforeach
                    @else
                        <div>{{ $__cv_skills }}</div>
                    @endif
                </div>
            </section>
            @endif

            <!-- Certifications Section -->
            @if(!empty($__cv_certifications) && is_array($__cv_certifications))
            <section>
                <h2>Certifications</h2>
                @foreach($__cv_certifications as $cert)
                <div class="entry">
                    <div class="entry-title">{{ $cert['name'] ?? 'Certification' }}</div>
                    @if(!empty($cert['issuer']))
                    <div class="entry-company">{{ $cert['issuer'] }}</div>
                    @endif
                    @if(!empty($cert['date']))
                    <div class="entry-date">{{ $cert['date'] }}</div>
                    @endif
                </div>
                @endforeach
            </section>
            @endif

        <!-- Languages Section -->
        @if(!empty($__cv_languages) && is_array($__cv_languages))
            <section>
                <h2>Languages</h2>
                <div class="skills-compact">
            @foreach($__cv_languages as $lang)
                    <div class="skill-category">
                        @if(is_array($lang))
                        <strong>{{ $lang['language'] ?? 'Language' }}:</strong> {{ $lang['proficiency'] ?? 'Level' }}
                        @else
                        {{ $lang }}
                        @endif
                    </div>
                    @endforeach
                </div>
            </section>
            @endif
        </div>
    </div>
</body>
</html>
