<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <title>{{ $content['personal_info']['full_name'] ?? 'CV' }}</title>
  <style>
    /* Professional Simple Template - Clean and ATS-friendly */
    html, body { 
      direction: ltr; 
      width: 794px; 
      margin: 0; 
      font-family: Arial, sans-serif;
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
      line-height: 1.4;
      min-height: 1123px;
      overflow-wrap: break-word;
    }
    
    .header {
      margin-bottom: 12px;
      border-bottom: 1px solid #ddd;
      padding-bottom: 8px;
    }
    
    .name {
      font-size: 20px;
      font-weight: 700;
      color: #111;
      margin-bottom: 4px;
      letter-spacing: 0.3px;
    }
    
    .contact {
      color: #444;
      font-size: 10px;
      line-height: 1.3;
    }
    
    .contact div {
      display: inline-block;
      margin-right: 12px;
    }
    
    .contact div:last-child {
      margin-right: 0;
    }
    
    h2 {
      font-size: 12px;
      font-weight: 700;
      margin: 14px 0 8px 0;
      border-bottom: 1px solid #ddd;
      padding-bottom: 3px;
      color: #111;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    
    .item {
      margin-bottom: 10px;
      page-break-inside: avoid;
    }
    
    .item strong {
      font-weight: 700;
      color: #111;
    }
    
    .item .company {
      color: #333;
      font-style: italic;
    }
    
    .item .date {
      color: #555;
      font-size: 10px;
      margin-top: 2px;
    }
    
    .item .description {
      margin-top: 4px;
      color: #333;
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
    
    .skills-list {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }
    
    .skills-list span {
      background: #f5f5f5;
      padding: 2px 8px;
      border-radius: 3px;
      font-size: 10px;
      color: #333;
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
        <div><strong>{{ $e['job_title'] ?? '' }}</strong></div>
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
        <div><strong>{{ $ed['degree'] ?? '' }}</strong></div>
        <div class="company">{{ $ed['institution'] ?? '' }}@if(!empty($ed['location'])) — {{ $ed['location'] }}@endif</div>
        @if(!empty($ed['graduation_date']))<div class="date">{{ \Carbon\Carbon::parse($ed['graduation_date'])->format('M Y') }}</div>@endif
        @if(!empty($ed['gpa']))<div class="description">GPA: {{ $ed['gpa'] }}</div>@endif
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
</body>
</html>
