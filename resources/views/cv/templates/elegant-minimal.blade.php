<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <title>{{ $content['personal_info']['full_name'] ?? 'CV' }}</title>
  <style>
    /* Elegant Minimal Template - Sophisticated Two-Column Layout */
    html, body { 
      direction: ltr; 
      width: 794px; 
      margin: 0; 
      font-family: Georgia, 'Times New Roman', serif;
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
      color: #111;
      line-height: 1.5;
      min-height: 1123px;
      overflow-wrap: break-word;
    }
    
    .header { 
      text-align: center; 
      margin-bottom: 20px;
      padding-bottom: 12px;
      border-bottom: 1px solid #ddd;
    }
    
    .name { 
      font-size: 22px; 
      font-weight: 700; 
      letter-spacing: 0.5px;
      color: #111;
      margin-bottom: 8px;
    }
    
    .rule { 
      height: 2px; 
      background: #111; 
      width: 60px; 
      margin: 8px auto;
    }
    
    .contact { 
      color: #444; 
      font-size: 11px;
      line-height: 1.4;
    }
    
    .contact span {
      margin: 0 4px;
    }
    
    h2 { 
      font-size: 12px; 
      font-weight: 700;
      margin: 14px 0 8px 0; 
      letter-spacing: 0.8px; 
      text-transform: uppercase;
      color: #111;
      border-bottom: 1px solid #eee;
      padding-bottom: 3px;
    }
    
    .muted { 
      color: #444;
    }
    
    .two-col { 
      display: grid; 
      grid-template-columns: 1fr 1fr; 
      gap: 24px;
      margin-top: 16px;
    }
    
    .item { 
      margin-bottom: 12px;
      page-break-inside: avoid;
    }
    
    .item strong {
      font-weight: 700;
      color: #111;
      display: block;
      margin-bottom: 2px;
    }
    
    .item .company {
      color: #555;
      font-style: italic;
      margin-bottom: 2px;
    }
    
    .item .date {
      color: #666;
      font-size: 10px;
      margin-bottom: 4px;
    }
    
    .item .description {
      color: #333;
      margin-top: 4px;
    }
    
    p {
      margin-bottom: 8px;
      text-align: justify;
    }
    
    ul {
      margin-left: 18px;
      margin-bottom: 6px;
    }
    
    li {
      margin-bottom: 2px;
    }
    
    .skills-list {
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
    }
    
    .skills-list span {
      background: #f8f8f8;
      padding: 3px 8px;
      border-radius: 3px;
      font-size: 10px;
      color: #333;
      border: 1px solid #eee;
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
    <div class="name">{{ $content['personal_info']['full_name'] ?? 'Full Name' }}</div>
    <div class="rule"></div>
    <div class="contact">
      @if(!empty($content['personal_info']['email'])){{ $content['personal_info']['email'] }}@endif
      @if(!empty($content['personal_info']['phone'])) • {{ $content['personal_info']['phone'] }}@endif
      @if(!empty($content['personal_info']['linkedin'])) • {{ $content['personal_info']['linkedin'] }}@endif
      @if(!empty($content['personal_info']['website'])) • {{ $content['personal_info']['website'] }}@endif
    </div>
  </div>

  @if(!empty($content['professional_summary']))
    <h2>Profile</h2>
    <p class="muted">{{ $content['professional_summary'] }}</p>
  @endif

  <div class="two-col">
    <div>
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

      @if(!empty($content['projects']))
        <h2>Projects</h2>
        @foreach($content['projects'] as $p)
          <div class="item">
            <strong>{{ $p['project_name'] ?? '' }}</strong>
            @if(!empty($p['description']))<div class="description muted">{{ $p['description'] }}</div>@endif
            @if(!empty($p['technologies']))<div class="muted"><em>Technologies:</em> {{ $p['technologies'] }}</div>@endif
            @if(!empty($p['url']))<div class="muted"><em>URL:</em> {{ $p['url'] }}</div>@endif
          </div>
        @endforeach
      @endif
    </div>
    <div>
      @if(!empty($content['education']))
        <h2>Education</h2>
        @foreach($content['education'] as $ed)
          <div class="item">
            <strong>{{ $ed['degree'] ?? '' }}</strong>
            <div class="company">{{ $ed['institution'] ?? '' }}@if(!empty($ed['location'])) — {{ $ed['location'] }}@endif</div>
            @if(!empty($ed['graduation_date']))<div class="date">{{ \Carbon\Carbon::parse($ed['graduation_date'])->format('M Y') }}</div>@endif
            @if(!empty($ed['gpa']))<div class="muted">GPA: {{ $ed['gpa'] }}</div>@endif
            @if(!empty($ed['honors']))<div class="muted">{{ $ed['honors'] }}</div>@endif
          </div>
        @endforeach
      @endif

      @if(!empty($content['technical_skills']) || !empty($content['soft_skills']) || !empty($content['languages']))
        <h2>Skills</h2>
        <div class="skills-list">
          @foreach((array)($content['technical_skills'] ?? []) as $s)<span>{{ $s }}</span>@endforeach
          @foreach((array)($content['soft_skills'] ?? []) as $s)<span>{{ $s }}</span>@endforeach
          @foreach((array)($content['languages'] ?? []) as $s)<span>{{ $s }}</span>@endforeach
        </div>
      @endif

      @if(!empty($content['certifications']))
        <h2>Certifications</h2>
        @foreach($content['certifications'] as $c)
          <div class="item">
            <strong>{{ $c['name'] ?? '' }}</strong>
            @if(!empty($c['issuer']))<div class="company">{{ $c['issuer'] }}</div>@endif
            @if(!empty($c['date']))<div class="date">{{ \Carbon\Carbon::parse($c['date'])->format('M Y') }}</div>@endif
            @if(!empty($c['credential_id']))<div class="muted">ID: {{ $c['credential_id'] }}</div>@endif
          </div>
        @endforeach
      @endif
    </div>
  </div>
</body>
</html>
