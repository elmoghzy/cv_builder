<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <title>{{ $content['personal_info']['full_name'] ?? 'CV' }}</title>
  <style>
    html, body { direction:ltr; width:794px; margin:0 }
    *{box-sizing:border-box;margin:0;padding:0}
  body{font-family: Georgia, 'Times New Roman', serif; padding:48px; font-size:11px; color:#111; min-height:1123px}
    .header{ text-align:center; margin-bottom:12px }
    .name{ font-size:22px; font-weight:700; letter-spacing:.5px }
    .rule{ height:2px; background:#111; width:60px; margin:8px auto }
    .contact{ color:#444; font-size:11px }
    h2{ font-size:12px; margin:12px 0 6px 0; letter-spacing:.8px; text-transform:uppercase }
    .muted{ color:#444 }
    .two-col{ display:grid; grid-template-columns:1fr 1fr; gap:16px }
    .item{ margin-bottom:8px }
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
      @if(!empty($content['work_experience']))<h2>Experience</h2>
        @foreach($content['work_experience'] as $e)
          <div class="item">
            <strong>{{ $e['job_title'] ?? '' }}</strong> — {{ $e['company'] ?? '' }}
            <div class="muted">
              {{ isset($e['start_date']) ? \Carbon\Carbon::parse($e['start_date'])->format('M Y') : '' }}
              @if(!empty($e['current'])) - Present @elseif(!empty($e['end_date'])) - {{ \Carbon\Carbon::parse($e['end_date'])->format('M Y') }} @endif
            </div>
            @if(!empty($e['description']))<div>{{ $e['description'] }}</div>@endif
          </div>
        @endforeach
      @endif

      @if(!empty($content['projects']))<h2>Projects</h2>
        @foreach($content['projects'] as $p)
          <div class="item">
            <strong>{{ $p['project_name'] ?? '' }}</strong>
            @if(!empty($p['description']))<div class="muted">{{ $p['description'] }}</div>@endif
          </div>
        @endforeach
      @endif
    </div>
    <div>
      @if(!empty($content['education']))<h2>Education</h2>
        @foreach($content['education'] as $ed)
          <div class="item">
            <strong>{{ $ed['degree'] ?? '' }}</strong>
            <div class="muted">{{ $ed['institution'] ?? '' }}</div>
            @if(!empty($ed['graduation_date']))<div class="muted">{{ \Carbon\Carbon::parse($ed['graduation_date'])->format('M Y') }}</div>@endif
          </div>
        @endforeach
      @endif

      @if(!empty($content['technical_skills']) || !empty($content['soft_skills']) || !empty($content['languages']))
        <h2>Skills</h2>
        <div class="muted">
          @foreach((array)($content['technical_skills'] ?? []) as $s)<span>{{ $s }}</span>@endforeach
          @foreach((array)($content['soft_skills'] ?? []) as $s)<span> • {{ $s }}</span>@endforeach
          @foreach((array)($content['languages'] ?? []) as $s)<span> • {{ $s }}</span>@endforeach
        </div>
      @endif
    </div>
  </div>
</body>
</html>
