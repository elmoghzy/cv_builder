@extends('layouts.app')

@section('content')
@php($price = number_format((config('paymob.cv_price_cents', 5000) / 100), 2))
<div class="min-h-screen bg-gradient-to-b from-slate-50 to-slate-100">
    <!-- Sticky Toolbar -->
    <div class="sticky top-0 z-10 bg-white/80 backdrop-blur border-b border-slate-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div class="min-w-0">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('filament.user.resources.cvs.index') }}" class="text-slate-600 hover:text-slate-900" title="Back">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                        </a>
                        <h1 class="text-lg font-semibold text-slate-900 truncate">CV Preview</h1>
                        <span class="truncate text-slate-600">— {{ $cv->title }}</span>
                    </div>
                    <div class="mt-1 flex items-center gap-2 text-xs">
                        <span class="text-slate-600">Template:</span>
                        <span class="px-2 py-0.5 bg-blue-100 text-blue-800 rounded">{{ $cv->template->name }}</span>
                        {{-- Template selector form --}}
                        @php($availableTemplates = \\App\\Models\\Template::active()->orderBy('sort_order')->get())
                        <form id="templateForm" action="{{ route('cv.changeTemplate', $cv) }}" method="POST" class="ms-3 d-inline-flex align-items-center">
                            @csrf
                            <select name="template_id" id="templateSelect" class="rounded-md border-gray-300 text-sm">
                                @foreach($availableTemplates as $tpl)
                                    <option value="{{ $tpl->id }}" @if($cv->template_id == $tpl->id) selected @endif>{{ $tpl->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="ml-2 px-2 py-1 rounded bg-blue-600 text-white text-xs">تغيير القالب</button>
                        </form>
                        <span class="text-slate-600 ml-3">Status:</span>
                        <span class="px-2 py-0.5 rounded {{ $cv->is_paid ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                            {{ $cv->is_paid ? 'Paid' : 'Unpaid' }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-1 text-sm text-slate-600">
                        <span>Zoom</span>
                        <button id="zoomOut" class="px-2 py-1 border rounded hover:bg-slate-50" title="Zoom out">-</button>
                        <select id="zoom" class="border-gray-300 rounded-md text-sm">
                            <option value="0.75">75%</option>
                            <option value="0.9">90%</option>
                            <option value="1" selected>100%</option>
                            <option value="1.25">125%</option>
                            <option value="1.5">150%</option>
                        </select>
                        <button id="zoomIn" class="px-2 py-1 border rounded hover:bg-slate-50" title="Zoom in">+</button>
                    </div>
                    <a href="{{ route('cv.edit', $cv) }}" class="hidden sm:inline-flex bg-blue-600 text-white px-3 py-2 rounded-md hover:bg-blue-700 text-sm">Edit</a>
                    @if(!$cv->is_paid)
                        <form action="{{ route('payment.initiate', $cv) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-emerald-600 text-white px-3 py-2 rounded-md hover:bg-emerald-700 text-sm">Pay & Download (EGP {{ $price }})</button>
                        </form>
                    @else
                        <a href="{{ route('cv.download', $cv) }}" class="bg-emerald-600 text-white px-3 py-2 rounded-md hover:bg-emerald-700 text-sm">Download PDF</a>
                    @endif
                    <button id="printBtn" class="px-3 py-2 rounded-md hover:bg-slate-100 text-sm">Print</button>
                </div>
            </div>
        </div>
    </div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <!-- Canvas -->
    <div class="bg-white/60 rounded-lg border border-slate-200 p-6 overflow-auto shadow-sm">
            <div class="flex justify-center">
                <div id="pageWrapper" class="origin-top scale-100">
                    <iframe id="cvFrame" title="cv preview" class="bg-white shadow-md rounded border border-gray-200"
                            style="width: 794px; height: 1123px;" srcdoc="{!! str_replace('"', '&quot;', $html) !!}"></iframe>
                </div>
            </div>
        </div>

        @if(!$cv->is_paid)
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <h3 class="text-lg font-medium text-yellow-800 mb-2">Payment Required for Download</h3>
            <p class="text-sm text-yellow-700">Complete payment to get an ATS-compliant PDF with professional formatting. Price: EGP {{ $price }}.</p>
        </div>
        @endif
    </div>
</div>

<script>
    const select = document.getElementById('zoom');
    const wrapper = document.getElementById('pageWrapper');
    const frame = document.getElementById('cvFrame');
    const zoomIn = document.getElementById('zoomIn');
    const zoomOut = document.getElementById('zoomOut');
    select.addEventListener('change', () => {
        const scale = parseFloat(select.value || '1');
        wrapper.style.transform = `scale(${scale})`;
    });
    zoomIn.addEventListener('click', () => {
        const next = Math.min(1.5, (parseFloat(select.value || '1') + 0.1));
        select.value = String(next);
        wrapper.style.transform = `scale(${next})`;
    });
    zoomOut.addEventListener('click', () => {
        const next = Math.max(0.5, (parseFloat(select.value || '1') - 0.1));
        select.value = String(next);
        wrapper.style.transform = `scale(${next})`;
    });
    document.getElementById('printBtn').addEventListener('click', () => {
        try { frame.contentWindow && frame.contentWindow.print(); } catch (e) { window.print(); }
    });

    // تم إزالة تغيير القالب بالـ AJAX. الآن التغيير يتم فقط عند الضغط على زر "تغيير القالب" وسيتم إعادة تحميل الصفحة بالكامل.
</script>
@endsection
