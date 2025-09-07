<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <title>{{ $content['personal_info']['full_name'] ?? 'CV' }}</title>
  <style>
    html, body { direction:ltr; width:794px; margin:0 }
    *{box-sizing:border-box;margin:0;padding:0}
  body{font-family: 'Segoe UI', Arial, sans-serif; padding:48px; font-size:11px; color:#0b1220; min-height:1123px}
    .bar{height:6px;background:linear-gradient(90deg,#6366f1,#06b6d4);border-radius:999px;margin-bottom:10px}
    .header{display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:8px}
    .name{font-size:20px;font-weight:800}
    .role{color:#6366f1;font-weight:600}
    .contact{text-align:right;color:#334155}
    h2{font-size:12px;margin:10px 0 6px 0;color:#0ea5e9;text-transform:uppercase;letter-spacing:.6px;border-bottom:1px solid #e2e8f0;padding-bottom:3px}
    .item{margin-bottom:6px}
    .tags span{display:inline-block;background:#e2e8f0;color:#0b1220;padding:2px 8px;border-radius:999px;font-size:10px;margin:0 6px 6px 0}
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

  @if(!empty($content['professional_summary']))<h2>Summary</h2><p>{{ $content['professional_summary'] }}</p>@endif

  @if(!empty($content['work_experience']))<h2>Experience</h2>
    @foreach($content['work_experience'] as $e)
      <div class="item">
        <strong>{{ $e['job_title'] ?? '' }}</strong> — {{ $e['company'] ?? '' }}
        <div style="color:#475569">
          {{ isset($e['start_date']) ? \Carbon\Carbon::parse($e['start_date'])->format('M Y') : '' }}
          @if(!empty($e['current'])) - Present @elseif(!empty($e['end_date'])) - {{ \Carbon\Carbon::parse($e['end_date'])->format('M Y') }} @endif
        </div>
        @if(!empty($e['description']))<div>{{ $e['description'] }}</div>@endif
      </div>
    @endforeach
  @endif

  @if(!empty($content['education']))<h2>Education</h2>
    @foreach($content['education'] as $ed)
      <div class="item">
        <strong>{{ $ed['degree'] ?? '' }}</strong> — {{ $ed['institution'] ?? '' }}
        @if(!empty($ed['graduation_date']))<span style="color:#475569"> ({{ \Carbon\Carbon::parse($ed['graduation_date'])->format('M Y') }})</span>@endif
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

  @if(!empty($content['projects']))<h2>Projects</h2>
    @foreach($content['projects'] as $p)
      <div class="item">
        <strong>{{ $p['project_name'] ?? '' }}</strong>
        @if(!empty($p['description']))<div>{{ $p['description'] }}</div>@endif
        @if(!empty($p['technologies']))<div><em>Tech:</em> {{ $p['technologies'] }}</div>@endif
        @if(!empty($p['url']))<div><em>URL:</em> {{ $p['url'] }}</div>@endif
      </div>
    @endforeach
  @endif
</body>
</html>
