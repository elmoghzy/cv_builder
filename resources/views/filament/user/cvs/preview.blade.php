@php($price = $price ?? number_format((config('paymob.cv_price_cents', 5000) / 100), 2))
<div class="p-4 bg-slate-50 rounded-lg border border-slate-200">
    <div class="flex items-center justify-between mb-3">
        <div class="text-sm text-slate-600">Use zoom controls in your browser (Ctrl/Cmd +/–) for a closer look.</div>
        <a href="{{ route('cv.preview', $cv) }}" target="_blank" class="text-primary-600 hover:underline text-sm">Open full preview</a>
    </div>
    <div class="flex justify-center">
        <iframe title="cv preview" class="bg-white shadow rounded border border-gray-200" style="width: 794px; height: 1123px;" srcdoc="{!! str_replace('"', '&quot;', $html ?? app(\App\Services\CvService::class)->generateHtml($cv)) !!}"></iframe>
    </div>
    @if(!$cv->is_paid)
        <div class="mt-3 text-xs text-amber-700">Payment required to download. Price: EGP {{ $price }}.</div>
    @endif
</div>
