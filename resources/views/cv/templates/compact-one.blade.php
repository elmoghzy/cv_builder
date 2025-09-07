<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <title>{{ $content['personal_info']['full_name'] ?? 'CV' }}</title>
  <style>
    html, body { direction:ltr; width:794px; margin:0 }
    *{box-sizing:border-box;margin:0;padding:0}
  body{font-family: Arial, sans-serif; padding:48px; font-size:11px; color:#111; min-height:1123px}
    .top{display:flex;justify-content:space-between;border-bottom:2px solid #111;padding-bottom:6px;margin-bottom:10px}
    .name{font-weight:800;font-size:18px}
    .right{text-align:right}
    h2{font-size:12px;margin:10px 0 6px 0;border-bottom:1px solid #ddd;padding-bottom:2px;text-transform:uppercase}
    .item{margin-bottom:6px}
    ul{margin-left:18px}
  </style>
</head>
<body>
  <div class="top">
    <div>
      <div class="name">{{ $content['personal_info']['full_name'] ?? 'Full Name' }}</div>
      @if(!empty($content['target_role']))<div>{{ $content['target_role'] }}</div>@endif
    </div>
    <div class="right">
      @if(!empty($content['personal_info']['email']))<div>{{ $content['personal_info']['email'] }}</div>@endif
      @if(!empty($content['personal_info']['phone']))<div>{{ $content['personal_info']['phone'] }}</div>@endif
      @if(!empty($content['personal_info']['linkedin']))<div>{{ $content['personal_info']['linkedin'] }}</div>@endif
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
        @if(!empty($e['achievements']))<div>{!! nl2br(e($e['achievements'])) !!}</div>@endif
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
      @foreach((array)($content['technical_skills'] ?? []) as $s)<span>{{ $s }}</span>@endforeach
      @foreach((array)($content['soft_skills'] ?? []) as $s)<span> • {{ $s }}</span>@endforeach
      @foreach((array)($content['languages'] ?? []) as $s)<span> • {{ $s }}</span>@endforeach
    </div>
  @endif

  @if(!empty($content['projects']))<h2>Projects</h2>
    @foreach($content['projects'] as $p)
      <div class="item">
        <strong>{{ $p['project_name'] ?? '' }}</strong>
        @if(!empty($p['description']))<div>{{ $p['description'] }}</div>@endif
        @if(!empty($p['technologies']))<div><em>Tech:</em> {{ $p['technologies'] }}</div>@endif
      </div>
    @endforeach
  @endif
</body>
</html>
