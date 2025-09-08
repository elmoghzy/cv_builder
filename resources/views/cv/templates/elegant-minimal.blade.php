<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{ $content['personal_info']['full_name'] ?? 'CV' }}</title>
	<style>
		/* Elegant Minimal Template - ATS Compliant */
		html, body { direction: ltr; unicode-bidi: isolate; }
		* { margin: 0; padding: 0; box-sizing: border-box; }

		body {
			font-family: {{ $styling['font_family'] ?? 'Georgia, Times New Roman, serif' }};
			font-size: 11px;
			line-height: 1.5;
			color: #111;
			background: #ffffff;
			margin: 0.75in;
			max-width: 8.27in;
			overflow-wrap: anywhere;
			word-break: break-word;
			hyphens: auto;
		}

		/* Header */
		.header { text-align: center; margin-bottom: 16px; }
		.name { font-size: 22px; font-weight: 700; letter-spacing: .4px; }
		.sub { color: #444; margin-top: 4px; font-style: italic; }
		.rule { height: 2px; width: 80px; background: #111; margin: 10px auto; }
		.contact { color: #555; font-size: 11px; }
		.contact div { margin-bottom: 2px; }

		/* Sections */
		h2 { 
			font-size: 13px; 
			font-weight: 700; 
			color: #111; 
			text-transform: uppercase; 
			letter-spacing: .8px; 
			text-align:; 
			margin: 16px 0 8px; 
		}
		h3 { font-size: 12px; font-weight: 700; margin: 6px 0 2px; color: #111; }
		p { margin-bottom: 6px; }
		ul { margin-left: 18px; margin-bottom: 8px; }
		li { margin-bottom: 2px; }

		/* Entries */
		.entry { margin-bottom: 12px; page-break-inside: avoid; }
		.meta { display: flex; justify-content: space-between; align-items: baseline; gap: 8px; }
		.meta .date { color: #666; font-size: 10px; white-space: nowrap; font-style: italic; }
		.muted { color: #555; font-size: 10.5px; }

		/* Grid helpers */
		.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

		/* Pills (for skills/languages) */
		.pill { display: inline-block; background: #f3f4f6; border: 1px solid #e5e7eb; color: #374151; padding: 2px 8px; border-radius: 999px; font-size: 9.5px; margin: 2px 4px 2px 0; }

		@media print {
			body { margin: 0.75in; font-size: 11px; }
		}
	</style>
	</head>
<body>
	<!-- Personal Info -->
	<header class="header">
		<div class="name">{{ $content['personal_info']['full_name'] ?? 'Full Name' }}</div>
		@if(!empty($content['target_role']))
			<div class="sub">{{ $content['target_role'] }}</div>
		@endif
		<div class="rule"></div>
		<div class="contact">
			@if(!empty($content['personal_info']['email']))<div>{{ $content['personal_info']['email'] }}</div>@endif
			@if(!empty($content['personal_info']['phone']))<div>{{ $content['personal_info']['phone'] }}</div>@endif
			@if(!empty($content['personal_info']['location']))<div>{{ $content['personal_info']['location'] }}</div>@endif
			@if(!empty($content['personal_info']['linkedin']))<div>{{ $content['personal_info']['linkedin'] }}</div>@endif
			@if(!empty($content['personal_info']['website']))<div>{{ $content['personal_info']['website'] }}</div>@endif
			@if(!empty($content['personal_info']['github']))<div>{{ $content['personal_info']['github'] }}</div>@endif
		</div>
	</header>

	<!-- Summary -->
	@if(!empty($content['summary']))
	<section>
		<h2>Professional Summary</h2>
		<p class="muted">{{ $content['summary'] }}</p>
	</section>
	@endif

	<!-- Experience -->
	@if(!empty($content['experience']) && is_array($content['experience']))
	<section>
		<h2>Experience</h2>
		@foreach($content['experience'] as $exp)
			<div class="entry">
				<div class="meta">
					<strong>{{ $exp['position'] ?? 'Position Title' }}</strong>
					<span class="date">{{ $exp['start_date'] ?? '' }}@if(!empty($exp['end_date'])) - {{ $exp['end_date'] }}@endif</span>
				</div>
				<div class="muted">{{ $exp['company'] ?? 'Company' }}@if(!empty($exp['location'])) — {{ $exp['location'] }}@endif</div>
				@if(!empty($exp['description']))<p>{{ $exp['description'] }}</p>@endif
				@if(!empty($exp['achievements']) && is_array($exp['achievements']))
				<ul>
					@foreach($exp['achievements'] as $a)
					<li>{{ $a }}</li>
					@endforeach
				</ul>
				@endif
			</div>
		@endforeach
	</section>
	@endif

	<!-- Education -->
	@if(!empty($content['education']) && is_array($content['education']))
	<section>
		<h2>Education</h2>
		<div class="grid-2">
		@foreach($content['education'] as $edu)
			<div class="entry">
				<div class="meta">
					<strong>{{ $edu['degree'] ?? 'Degree' }}</strong>
					@if(!empty($edu['graduation_date']))<span class="date">{{ $edu['graduation_date'] }}</span>@endif
				</div>
				<div class="muted">{{ $edu['institution'] ?? 'Institution' }}@if(!empty($edu['location'])) — {{ $edu['location'] }}@endif</div>
				@if(!empty($edu['gpa']))<div class="muted">GPA: {{ $edu['gpa'] }}</div>@endif
				@if(!empty($edu['honors']))<div class="muted">{{ $edu['honors'] }}</div>@endif
				@if(!empty($edu['relevant_coursework']))<div class="muted">Relevant Coursework: {{ $edu['relevant_coursework'] }}</div>@endif
			</div>
		@endforeach
		</div>
	</section>
	@endif

	<!-- Skills -->
	@if(!empty($content['skills']))
	<section>
		<h2>Core Skills</h2>
		@if(is_array($content['skills']))
			@foreach($content['skills'] as $group)
				@if(is_array($group) && isset($group['category']) && isset($group['skills']))
				<div class="muted" style="margin-bottom:6px;">
					<strong>{{ $group['category'] }}:</strong>
					@if(is_array($group['skills']))
						@foreach($group['skills'] as $skill)
						<span class="pill">{{ $skill }}</span>
						@endforeach
					@else
						<span class="pill">{{ $group['skills'] }}</span>
					@endif
				</div>
				@endif
			@endforeach
		@else
			<div class="muted">{{ $content['skills'] }}</div>
		@endif
	</section>
	@endif

	<!-- Projects -->
	@if(!empty($content['projects']) && is_array($content['projects']))
	<section>
		<h2>Projects</h2>
		@foreach($content['projects'] as $proj)
			<div class="entry">
				<div class="meta">
					<strong>{{ $proj['name'] ?? 'Project Name' }}</strong>
					@if(!empty($proj['date']))<span class="date">{{ $proj['date'] }}</span>@endif
				</div>
				@if(!empty($proj['description']))<p class="muted">{{ $proj['description'] }}</p>@endif
				@if(!empty($proj['technologies']))
					<p class="muted"><strong>Technologies:</strong>
						{{ is_array($proj['technologies']) ? implode(', ', $proj['technologies']) : $proj['technologies'] }}
					</p>
				@endif
				@if(!empty($proj['url']))<p class="muted"><strong>URL:</strong> {{ $proj['url'] }}</p>@endif
			</div>
		@endforeach
	</section>
	@endif

	<!-- Certifications -->
	@if(!empty($content['certifications']) && is_array($content['certifications']))
	<section>
		<h2>Certifications</h2>
		@foreach($content['certifications'] as $cert)
			<div class="entry">
				<div class="meta">
					<strong>{{ $cert['name'] ?? 'Certification' }}</strong>
					@if(!empty($cert['date']))<span class="date">{{ $cert['date'] }}</span>@endif
				</div>
				@if(!empty($cert['issuer']))<div class="muted">{{ $cert['issuer'] }}</div>@endif
				@if(!empty($cert['credential_id']))<div class="muted">Credential ID: {{ $cert['credential_id'] }}</div>@endif
			</div>
		@endforeach
	</section>
	@endif

	<!-- Languages -->
	@if(!empty($content['languages']) && is_array($content['languages']))
	<section>
		<h2>Languages</h2>
		<div class="muted">
			@foreach($content['languages'] as $lang)
				@if(is_array($lang))
				<span class="pill">{{ $lang['language'] ?? 'Language' }} ({{ $lang['proficiency'] ?? 'Level' }})</span>
				@else
				<span class="pill">{{ $lang }}</span>
				@endif
			@endforeach
		</div>
	</section>
	@endif
</body>
</html>

