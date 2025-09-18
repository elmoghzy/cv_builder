<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content['personal_info']['full_name'] ?? 'CV' }}</title>
    <style>
        /* Tech-Focused Template - Developer/Engineer Style */
        html, body { direction: ltr; unicode-bidi: isolate; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: {{ $styling['font_family'] ?? '"SF Mono", "Monaco", "Consolas", "Liberation Mono", monospace' }};
            font-size: 11px;
            line-height: 1.45;
            color: #2d3748;
            background-color: #ffffff;
            margin: 0.75in;
            max-width: 8.27in;
            overflow-wrap: anywhere;
            word-break: break-word;
            hyphens: auto;
        }
        
        /* Tech Header */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .name {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        
        .tech-title {
            font-size: 14px;
            opacity: 0.9;
            font-weight: 500;
            margin-bottom: 12px;
        }
        
        .contact-tech {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            font-size: 10px;
            opacity: 0.9;
        }
        
        .contact-tech > div {
            background: rgba(255, 255, 255, 0.1);
            padding: 4px 8px;
            border-radius: 4px;
        }
        
        /* Tech Section Headers */
        h2 {
            font-size: 13px;
            font-weight: 700;
            color: #4a5568;
            margin: 18px 0 10px 0;
            padding: 8px 12px;
            background: #f7fafc;
            border-left: 4px solid #667eea;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 0 4px 4px 0;
        }
        
        h3 {
            font-size: 12px;
            font-weight: bold;
            margin-top: 8px;
            margin-bottom: 4px;
            color: #2d3748;
        }
        
        /* Content */
        p { margin-bottom: 8px; font-family: 'Arial', sans-serif; }
        ul { margin-left: 20px; margin-bottom: 10px; padding-left: 0; }
        li { margin-bottom: 3px; font-family: 'Arial', sans-serif; }
        
        /* Tech Entries */
        .tech-entry {
            margin-bottom: 16px;
            padding: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            background: #fcfcfc;
            page-break-inside: avoid;
        }
        
        .entry-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 6px;
            gap: 10px;
        }
        
        .entry-title {
            font-weight: bold;
            font-size: 12px;
            color: #2d3748;
            font-family: 'Arial', sans-serif;
        }
        
        .entry-date {
            font-size: 10px;
            color: #718096;
            white-space: nowrap;
            background: #edf2f7;
            padding: 2px 6px;
            border-radius: 3px;
        }
        
        .entry-company {
            font-size: 11px;
            color: #4a5568;
            margin-bottom: 6px;
            font-weight: 500;
            font-family: 'Arial', sans-serif;
        }
        
        .entry-location {
            font-size: 10px;
            color: #718096;
            margin-bottom: 6px;
        }
        
        .entry-description {
            margin-bottom: 8px;
            font-family: 'Arial', sans-serif;
        }
        
        /* Tech Skills Grid */
        .tech-skills {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
            margin-bottom: 10px;
        }
        
        .skill-tech-group {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            padding: 10px;
            border-radius: 6px;
            border-left: 4px solid #28a745;
        }
        
        .skill-tech-group strong {
            color: #2d3748;
            display: block;
            margin-bottom: 6px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .tech-stack {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }
        
        .tech-tag {
            background: #667eea;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: 500;
        }
        
        /* Skills with Rating */
        .skills-with-rating {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .skill-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 4px 0;
        }
        
        .skill-name {
            font-size: 10px;
            color: #2d3748;
            font-weight: 500;
        }
        
        .skill-rating {
            display: flex;
            gap: 1px;
        }
        
        .star {
            font-size: 12px;
            color: #ffd700;
        }
        
        .star.filled {
            color: #ffd700;
        }
        
        .star.empty {
            color: #e2e8f0;
        }
        
        /* Code Block Style */
        .code-section {
            background: #1a202c;
            color: #e2e8f0;
            padding: 12px;
            border-radius: 6px;
            margin: 10px 0;
            font-family: 'SF Mono', 'Monaco', 'Consolas', monospace;
            font-size: 10px;
            border-left: 4px solid #667eea;
        }
        
        /* Summary Tech */
        .summary-tech {
            background: #f7fafc;
            padding: 15px;
            border-radius: 6px;
            border-left: 4px solid #667eea;
            margin-bottom: 10px;
            font-family: 'Arial', sans-serif;
        }
        
        @media print {
            body { margin: 0.75in; font-size: 11px; }
            .header { 
                background: #667eea !important; 
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
            .tech-entry { border: 1px solid #ddd; background: #fafafa; }
            .skill-tech-group { background: #f5f5f5; border: 1px solid #ccc; }
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
    <!-- Tech Header -->
    <header class="header">
        <h1 class="name">{{ $content['personal_info']['full_name'] ?? 'Full Name' }}</h1>
        @if(!empty($content['personal_info']['title']))
        <div class="tech-title">{{ $content['personal_info']['title'] }}</div>
        @endif
        <div class="contact-tech">
            @if(!empty($content['personal_info']['email']))
            <div>📧 {{ $content['personal_info']['email'] }}</div>
            @endif
            @if(!empty($content['personal_info']['phone']))
            <div>📱 {{ $content['personal_info']['phone'] }}</div>
            @endif
            @if(!empty($content['personal_info']['location']))
            <div>📍 {{ $content['personal_info']['location'] }}</div>
            @endif
            @if(!empty($content['personal_info']['linkedin']))
            <div>🔗 {{ $content['personal_info']['linkedin'] }}</div>
            @endif
            @if(!empty($content['personal_info']['github']))
            <div>💻 {{ $content['personal_info']['github'] }}</div>
            @endif
            @if(!empty($content['personal_info']['website']))
            <div>🌐 {{ $content['personal_info']['website'] }}</div>
            @endif
        </div>
    </header>

    <!-- Technical Summary -->
    @if(!empty($__cv_summary))
    <section>
        <h2>🎯 Technical Profile</h2>
        <div class="summary-tech">{{ $__cv_summary }}</div>
    </section>
    @endif

    <!-- Technical Skills -->
    @if(!empty($__cv_skills) || !empty($content['technical_skills']) || !empty($content['soft_skills']))
    <section>
        <h2>⚡ Technical Stack</h2>
        <div class="tech-skills">
            {{-- New format with ratings --}}
            @if(!empty($content['technical_skills']) && is_array($content['technical_skills']))
                <div class="skill-tech-group">
                    <strong>Technical Skills</strong>
                    <div class="skills-with-rating">
                        @foreach($content['technical_skills'] as $skill)
                            @if(is_array($skill) && isset($skill['skill']))
                            <div class="skill-item">
                                <span class="skill-name">{{ $skill['skill'] }}</span>
                                <div class="skill-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= ($skill['level'] ?? 3))
                                            <span class="star filled">★</span>
                                        @else
                                            <span class="star empty">☆</span>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            @else
                                <span class="tech-tag">{{ is_string($skill) ? $skill : $skill['skill'] ?? '' }}</span>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            @if(!empty($content['soft_skills']) && is_array($content['soft_skills']))
                <div class="skill-tech-group">
                    <strong>Soft Skills</strong>
                    <div class="skills-with-rating">
                        @foreach($content['soft_skills'] as $skill)
                            @if(is_array($skill) && isset($skill['skill']))
                            <div class="skill-item">
                                <span class="skill-name">{{ $skill['skill'] }}</span>
                                <div class="skill-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= ($skill['level'] ?? 3))
                                            <span class="star filled">★</span>
                                        @else
                                            <span class="star empty">☆</span>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            @else
                                <span class="tech-tag">{{ is_string($skill) ? $skill : $skill['skill'] ?? '' }}</span>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Legacy format support --}}
            @if(!empty($__cv_skills) && is_array($__cv_skills))
                @foreach($__cv_skills as $skillGroup)
                    @if(is_array($skillGroup) && isset($skillGroup['category']) && isset($skillGroup['skills']))
                    <div class="skill-tech-group">
                        <strong>{{ $skillGroup['category'] }}</strong>
                        <div class="tech-stack">
                            @if(is_array($skillGroup['skills']))
                                @foreach($skillGroup['skills'] as $skill)
                                <span class="tech-tag">{{ $skill }}</span>
                                @endforeach
                            @else
                                <span class="tech-tag">{{ $skillGroup['skills'] }}</span>
                            @endif
                        </div>
                    </div>
                    @endif
                @endforeach
            @elseif(!empty($__cv_skills))
                <div class="skill-tech-group">
                    <strong>Technologies</strong>
                    <div>{{ $__cv_skills }}</div>
                </div>
            @endif
        </div>
    </section>
    @endif

    <!-- Professional Experience -->
    @if(!empty($__cv_experience) && is_array($__cv_experience))
    <section>
        <h2>💼 Professional Experience</h2>
        @foreach($__cv_experience as $exp)
        <div class="tech-entry">
            <div class="entry-header">
                <div class="entry-title">{{ $exp['position'] ?? 'Position Title' }}</div>
                <div class="entry-date">{{ $exp['start_date'] ?? '' }}@if(!empty($exp['end_date'])) - {{ $exp['end_date'] }}@endif</div>
            </div>
            <div class="entry-company">{{ $exp['company'] ?? 'Company Name' }}</div>
            @if(!empty($exp['location']))
            <div class="entry-location">📍 {{ $exp['location'] }}</div>
            @endif
            @if(!empty($exp['description']))
            <div class="entry-description">{{ $exp['description'] }}</div>
            @endif
            @if(!empty($exp['achievements']) && is_array($exp['achievements']))
            <ul>
                @foreach($exp['achievements'] as $achievement)
                <li>{{ $achievement }}</li>
                @endforeach
            </ul>
            @endif
        </div>
        @endforeach
    </section>
    @endif

    <!-- Projects -->
    @if(!empty($__cv_projects) && is_array($__cv_projects))
    <section>
        <h2>🚀 Featured Projects</h2>
        @foreach($__cv_projects as $project)
        <div class="tech-entry">
            <h3>{{ $project['name'] ?? 'Project Name' }}</h3>
            @if(!empty($project['date']))
            <div class="entry-date">{{ $project['date'] }}</div>
            @endif
            @if(!empty($project['description']))
            <div class="entry-description">{{ $project['description'] }}</div>
            @endif
            @if(!empty($project['technologies']))
            <div class="tech-stack">
                @if(is_array($project['technologies']))
                    @foreach($project['technologies'] as $tech)
                    <span class="tech-tag">{{ $tech }}</span>
                    @endforeach
                @else
                    <span class="tech-tag">{{ $project['technologies'] }}</span>
                @endif
            </div>
            @endif
            @if(!empty($project['url']))
            <div class="entry-description"><strong>🔗 URL:</strong> {{ $project['url'] }}</div>
            @endif
        </div>
        @endforeach
    </section>
    @endif

    <!-- Education -->
    @if(!empty($__cv_education) && is_array($__cv_education))
    <section>
        <h2>🎓 Education</h2>
        @foreach($__cv_education as $edu)
        <div class="tech-entry">
            <div class="entry-header">
                <div class="entry-title">{{ $edu['degree'] ?? 'Degree' }}</div>
                <div class="entry-date">{{ $edu['graduation_date'] ?? 'Year' }}</div>
            </div>
            <div class="entry-company">{{ $edu['institution'] ?? 'Institution' }}</div>
            @if(!empty($edu['location']))
            <div class="entry-location">📍 {{ $edu['location'] }}</div>
            @endif
            @if(!empty($edu['gpa']))
            <div class="entry-description">GPA: {{ $edu['gpa'] }}</div>
            @endif
            @if(!empty($edu['honors']))
            <div class="entry-description">{{ $edu['honors'] }}</div>
            @endif
            @if(!empty($edu['relevant_coursework']))
            <div class="entry-description">Relevant Coursework: {{ $edu['relevant_coursework'] }}</div>
            @endif
        </div>
        @endforeach
    </section>
    @endif

    <!-- Certifications -->
    @if(!empty($__cv_certifications) && is_array($__cv_certifications))
    <section>
        <h2>🏆 Certifications</h2>
        @foreach($__cv_certifications as $cert)
        <div class="tech-entry">
            <div class="entry-header">
                <div class="entry-title">{{ $cert['name'] ?? 'Certification Name' }}</div>
                @if(!empty($cert['date']))
                <div class="entry-date">{{ $cert['date'] }}</div>
                @endif
            </div>
            @if(!empty($cert['issuer']))
            <div class="entry-company">{{ $cert['issuer'] }}</div>
            @endif
            @if(!empty($cert['credential_id']))
            <div class="entry-description"><strong>Credential ID:</strong> {{ $cert['credential_id'] }}</div>
            @endif
        </div>
        @endforeach
    </section>
    @endif

    <!-- Languages -->
    @if(!empty($__cv_languages) && is_array($__cv_languages))
    <section>
        <h2>🌍 Languages</h2>
        <div class="tech-skills">
            @foreach($__cv_languages as $lang)
            <div class="skill-tech-group">
                @if(is_array($lang))
                <strong>{{ $lang['language'] ?? 'Language' }}</strong>
                <div>{{ $lang['proficiency'] ?? 'Proficiency Level' }}</div>
                @else
                <strong>Language</strong>
                <div>{{ $lang }}</div>
                @endif
            </div>
            @endforeach
        </div>
    </section>
    @endif

</body>
</html>
