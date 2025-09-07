<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <title>{{ $content['personal_info']['full_name'] ?? 'CV' }}</title>
  <style>
    html, body { direction:ltr; width:794px; margin:0 }
    *{box-sizing:border-box;margin:0;padding:0}
  body{font-family: Arial, sans-serif; padding:48px; font-size:12px; line-height:1.45; color:#111; min-height:1123px}
    .header{margin-bottom:10px}
    .name{font-size:20px;font-weight:700}
    .contact{color:#444}
    h2{font-size:12px;margin:10px 0 6px 0;border-bottom:1px solid #ddd;padding-bottom:2px}
    .item{margin-bottom:6px}
    .pill{display:inline-block;background:#f3f4f6;color:#374151;padding:2px 8px;border-radius:999px;font-size:10px;margin-right:6px;margin-bottom:4px}
  </style>
</head>
<body>
  <div class="header">
    <div class="name">{{ $content['personal_info']['full_name'] ?? 'Full Name' }}</div>
    <div class="contact">
      @if(!empty($content['personal_info']['email'])){{ $content['personal_info']['email'] }}@endif
      @if(!empty($content['personal_info']['phone'])) • {{ $content['personal_info']['phone'] }}@endif
      @if(!empty($content['personal_info']['linkedin'])) • {{ $content['personal_info']['linkedin'] }}@endif
      @if(!empty($content['personal_info']['website'])) • {{ $content['personal_info']['website'] }}@endif
    </div>
  </div>

  @if(!empty($content['professional_summary']))<h2>Summary</h2><p>{{ $content['professional_summary'] }}</p>@endif

  @if(!empty($content['work_experience']))<h2>Experience</h2>
    @foreach($content['work_experience'] as $e)
      <div class="item">
        <strong>{{ $e['job_title'] ?? '' }}</strong> — {{ $e['company'] ?? '' }}
        <div style="color:#555">
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
        @if(!empty($ed['graduation_date']))<span style="color:#555"> ({{ \Carbon\Carbon::parse($ed['graduation_date'])->format('M Y') }})</span>@endif
      </div>
    @endforeach
  @endif

  @if(!empty($content['technical_skills']) || !empty($content['soft_skills']) || !empty($content['languages']))
    <h2>Skills</h2>
    <div>
      @foreach((array)($content['technical_skills'] ?? []) as $s)<span class="pill">{{ $s }}</span>@endforeach
      @foreach((array)($content['soft_skills'] ?? []) as $s)<span class="pill">{{ $s }}</span>@endforeach
      @foreach((array)($content['languages'] ?? []) as $s)<span class="pill">{{ $s }}</span>@endforeach
    </div>
  @endif
</body>
</html>
