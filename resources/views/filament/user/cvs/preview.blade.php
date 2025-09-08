@php
    // Accept either $cv or Filament's $record variable when included from different contexts
    $cv = $cv ?? ($record ?? null);
    $price = $price ?? number_format((config('paymob.cv_price_cents', 5000) / 100), 2);
@endphp

@if(!$cv)
    <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200 text-sm text-yellow-800">No CV available for preview.</div>
@else
<div class="p-4 bg-slate-50 rounded-lg border border-slate-200">
    <div class="flex items-center justify-between mb-3">
        <div class="text-sm text-slate-600">Use zoom controls in your browser (Ctrl/Cmd +/–) for a closer look.</div>
        <a href="{{ route('cv.preview', $cv) }}" target="_blank" class="text-primary-600 hover:underline text-sm">Open full preview</a>
    </div>
        <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-2 text-sm">
            <span class="text-slate-700">Template:</span>
            @php($availableTemplates = \App\Models\Template::active()->orderBy('sort_order')->get())
            <form action="{{ route('cv.changeTemplate', $cv) }}" method="POST" class="inline-block">
                @csrf
                <select name="template_id" class="rounded-md border-gray-200 text-sm p-1" onchange="this.form.submit()">
                    @foreach($availableTemplates as $tpl)
                        <option value="{{ $tpl->id }}" @if($cv->template_id == $tpl->id) selected @endif>{{ $tpl->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <a href="{{ url('/user/cvs/' . $cv->id . '/edit') }}" class="text-primary-600 hover:underline text-sm">Open in builder</a>
    </div>
    <div class="flex justify-center">
        <iframe title="cv preview" class="bg-white shadow rounded border border-gray-200" style="width: 794px; height: 1123px;" srcdoc="{!! str_replace('"', '&quot;', $html ?? app(\App\Services\CvService::class)->generateHtml($cv)) !!}"></iframe>
    </div>

    <div class="mt-4 flex items-center justify-between flex-wrap gap-3">
        @if(!$cv->is_paid)
            <div class="text-sm text-slate-700">
                السعر: <span class="font-semibold text-slate-900">EGP {{ $price }}</span> — قم بالدفع لتنزيل السيرة الذاتية.
            </div>
            <form action="{{ route('payment.initiate', $cv) }}" method="POST" class="ms-auto">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-5 py-2.5 text-sm font-semibold shadow hover:shadow-lg hover:scale-[1.02] transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 8.25h19.5M3.75 6A1.5 1.5 0 0 0 2.25 7.5v9a1.5 1.5 0 0 0 1.5 1.5h16.5a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 20.25 6H3.75z"/></svg>
                    ادفع الآن وحمّل
                </button>
            </form>
        @else
            <div class="text-sm text-emerald-700">تم الدفع — التحميل متاح الآن.</div>
            <div class="ms-auto flex items-center gap-3">
                <a href="{{ asset('storage/' . ($cv->pdf_path ?? '')) }}" target="_blank" class="inline-flex items-center gap-2 rounded-full bg-emerald-600 text-white px-4 py-2 text-sm font-medium hover:bg-emerald-700">
                    PDF
                </a>
                @if(!empty($cv->docx_path))
                    <a href="{{ asset('storage/' . $cv->docx_path) }}" class="inline-flex items-center gap-2 rounded-full bg-sky-600 text-white px-4 py-2 text-sm font-medium hover:bg-sky-700">
                        Word
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>

{{-- Removed AJAX change: template switching now triggers full page reload via normal POST submit --}}

@endif
