<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <title>{{ $content['personal_info']['full_name'] ?? 'CV' }}</title>
  <style>
    /* Tech Focus Template - Modern and Tech-oriented */
    html, body { 
      direction: ltr; 
      width: 794px; 
      margin: 0; 
      font-family: 'Segoe UI', Arial, sans-serif;
      background: #ffffff;
    }
    
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    
    body {
      padding: 48px;
      font-size: 11px;
      color: #0b1220;
      line-height: 1.4;
      min-height: 1123px;
      overflow-wrap: break-word;
    }
    
    .bar {
      height: 6px;
      background: linear-gradient(90deg, #6366f1, #06b6d4);
      border-radius: 999px;
      margin-bottom: 12px;
    }
    
    .header {
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      margin-bottom: 12px;
      gap: 20px;
    }
    
    .name {
      font-size: 20px;
      font-weight: 800;
      color: #0b1220;
      letter-spacing: 0.3px;
    }
    
    .role {
      color: #6366f1;
      font-weight: 600;
      margin-top: 2px;
      font-size: 12px;
    }
    
    .contact {
      text-align: right;
      color: #334155;
      font-size: 10px;
      flex-shrink: 0;
    }
    
    .contact div {
      margin-bottom: 2px;
    }
    
    h2 {
      font-size: 12px;
      font-weight: 700;
      margin: 12px 0 8px 0;
      color: #0ea5e9;
      text-transform: uppercase;
      letter-spacing: 0.6px;
      border-bottom: 1px solid #e2e8f0;
      padding-bottom: 4px;
    }
    
    .item {
      margin-bottom: 10px;
      page-break-inside: avoid;
    }
    
    .item strong {
      font-weight: 700;
      color: #0b1220;
      display: block;
      margin-bottom: 2px;
    }
    
    .item .company {
      color: #475569;
      font-style: italic;
      margin-bottom: 2px;
    }
    
    .item .date {
      color: #64748b;
      font-size: 10px;
      margin-bottom: 4px;
    }
    
    .item .description {
      color: #334155;
      margin-top: 4px;
    }
    
    p {
      margin-bottom: 6px;
      text-align: justify;
    }
    
    ul {
      margin-left: 18px;
      margin-bottom: 6px;
    }
    
    li {
      margin-bottom: 2px;
    }
    
    .tags {
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
      margin-top: 4px;
    }
    
    .tags span {
      display: inline-block;
      background: #e2e8f0;
      color: #0b1220;
      padding: 3px 8px;
      border-radius: 999px;
      font-size: 10px;
      font-weight: 500;
      border: 1px solid #cbd5e1;
    }
    
    .projects .item .tech {
      margin-top: 4px;
    }
    
    .projects .item .tech span {
      background: #dbeafe;
      color: #1e40af;
      border-color: #bfdbfe;
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
  <div class="bar"></div>
  <div class="header">
    <div>
      <div class="name">{{ $content['personal_info']['full_name'] ?? 'Full Name' }}</div>
      @if(!empty($content['target_role']))<div class="role">{{ $content['target_role'] }}</div>@endif
    </div>
    <div class="contact">
      @if(!empty($content['personal_info']['email']))<div>{{ $content['personal_info']['email'] }}</div>@endif
      @if(!empty($content['personal_info']['phone']))<div>{{ $content['personal_info']['phone'] }}</div>@endif
      @if(!empty($content['personal_info']['linkedin']))<div>{{ $content['personal_info']['linkedin'] }}</div>@endif
      @if(!empty($content['personal_info']['website']))<div>{{ $content['personal_info']['website'] }}</div>@endif
    </div>
  </div>

  @if(!empty($content['professional_summary']))
    <h2>Summary</h2>
    <p>{{ $content['professional_summary'] }}</p>
  @endif

  @if(!empty($content['work_experience']))
    <h2>Experience</h2>
    @foreach($content['work_experience'] as $e)
      <div class="item">
        <strong>{{ $e['job_title'] ?? '' }}</strong>
        <div class="company">{{ $e['company'] ?? '' }}@if(!empty($e['location'])) — {{ $e['location'] }}@endif</div>
        <div class="date">
          {{ isset($e['start_date']) ? \Carbon\Carbon::parse($e['start_date'])->format('M Y') : '' }}
          @if(!empty($e['current'])) - Present @elseif(!empty($e['end_date'])) - {{ \Carbon\Carbon::parse($e['end_date'])->format('M Y') }} @endif
        </div>
        @if(!empty($e['description']))<div class="description">{{ $e['description'] }}</div>@endif
        @if(!empty($e['achievements']))<div class="description">{!! nl2br(e($e['achievements'])) !!}</div>@endif
      </div>
    @endforeach
  @endif

  @if(!empty($content['education']))
    <h2>Education</h2>
    @foreach($content['education'] as $ed)
      <div class="item">
        <strong>{{ $ed['degree'] ?? '' }}</strong>
        <div class="company">{{ $ed['institution'] ?? '' }}@if(!empty($ed['location'])) — {{ $ed['location'] }}@endif</div>
        @if(!empty($ed['graduation_date']))<div class="date">{{ \Carbon\Carbon::parse($ed['graduation_date'])->format('M Y') }}</div>@endif
        @if(!empty($ed['gpa']))<div class="description">GPA: {{ $ed['gpa'] }}</div>@endif
      </div>
    @endforeach
  @endif

  @if(!empty($content['technical_skills']) || !empty($content['soft_skills']) || !empty($content['languages']))
    <h2>Skills</h2>
    <div class="tags">
      @foreach((array)($content['technical_skills'] ?? []) as $s)<span>{{ $s }}</span>@endforeach
      @foreach((array)($content['soft_skills'] ?? []) as $s)<span>{{ $s }}</span>@endforeach
      @foreach((array)($content['languages'] ?? []) as $s)<span>{{ $s }}</span>@endforeach
    </div>
  @endif

  @if(!empty($content['projects']))
    <h2>Projects</h2>
    <div class="projects">
      @foreach($content['projects'] as $p)
        <div class="item">
          <strong>{{ $p['project_name'] ?? '' }}</strong>
          @if(!empty($p['description']))<div class="description">{{ $p['description'] }}</div>@endif
          @if(!empty($p['technologies']))
            <div class="tech">
              @foreach(explode(',', $p['technologies']) as $tech)
                <span>{{ trim($tech) }}</span>
              @endforeach
            </div>
          @endif
          @if(!empty($p['url']))<div class="description"><strong>URL:</strong> {{ $p['url'] }}</div>@endif
        </div>
      @endforeach
    </div>
  @endif

  @if(!empty($content['certifications']))
    <h2>Certifications</h2>
    @foreach($content['certifications'] as $c)
      <div class="item">
        <strong>{{ $c['name'] ?? '' }}</strong>
        @if(!empty($c['issuer']))<div class="company">{{ $c['issuer'] }}</div>@endif
        @if(!empty($c['date']))<div class="date">{{ \Carbon\Carbon::parse($c['date'])->format('M Y') }}</div>@endif
        @if(!empty($c['credential_id']))<div class="description">Credential ID: {{ $c['credential_id'] }}</div>@endif
      </div>
    @endforeach
  @endif
</body>
</html>
