@php
    // Accept either $cv or Filament's $record variable when included from different contexts
    $cv = $cv ?? ($record ?? null);
    $price = $price ?? number_format((config('paymob.cv_price_cents', 5000) / 100), 2);
@endphp

@if(!$cv)
    <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200 text-sm text-yellow-800">No CV available for preview.</div>
@else
<div class="p-6 bg-gradient-to-b from-white to-slate-50 rounded-xl border border-slate-200 shadow-sm">
    <div class="flex items-start justify-between mb-4">
        <div>
            <h3 class="text-lg font-semibold text-slate-900">Preview</h3>
            <p class="text-sm text-slate-600 mt-0.5">Use browser zoom (Ctrl/Cmd +/–) for a closer look.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('cv.preview', $cv) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 text-sm rounded-md bg-white border border-slate-200 text-slate-700 hover:bg-slate-50">Open full preview</a>
            <a href="{{ url('/user/cvs/' . $cv->id . '/edit') }}" class="inline-flex items-center px-3 py-1.5 text-sm rounded-md bg-primary-600 text-white hover:bg-primary-700">Open in builder</a>
        </div>
    </div>
        <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2 text-sm">
            <span class="text-slate-700">Template:</span>
            @php($availableTemplates = \App\Models\Template::active()->orderBy('sort_order')->get())
            <form action="{{ route('cv.changeTemplate', $cv) }}" method="POST" class="inline-flex items-center gap-2">
                @csrf
                <select name="template_id" class="rounded-md border-gray-200 text-sm p-1.5 focus:border-primary-500 focus:ring-primary-500" onchange="this.form.submit()">
                    @foreach($availableTemplates as $tpl)
                        <option value="{{ $tpl->id }}" @if($cv->template_id == $tpl->id) selected @endif>{{ $tpl->name }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <a href="{{ url('/user/cvs/' . $cv->id . '/edit') }}" class="hidden">Open in builder</a>
    </div>
    <div class="flex justify-center">
        <iframe title="cv preview" class="bg-white shadow-lg rounded-lg border border-gray-200" style="width: 794px; height: 1123px;" srcdoc="{!! str_replace('\"', '&quot;', $html ?? app(\App\Services\CvService::class)->generateHtml($cv)) !!}"></iframe>
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
    <div class="text-xs text-slate-500 mt-2">Status: <span class="px-2 py-0.5 rounded {{ $cv->is_paid ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-800' }}">{{ $cv->is_paid ? 'Paid' : 'Unpaid' }}</span></div>
</div>

{{-- Removed AJAX change: template switching now triggers full page reload via normal POST submit --}}

@endif

<style>
.cv-chatbot { position: fixed; bottom: 22px; right: 22px; z-index: 60; }
.cv-chatbot-panel { width: 360px; max-height: 70vh; background: #fff; box-shadow: 0 10px 25px rgba(0,0,0,.15); border-radius: 12px; overflow: hidden; display: none; }
.cv-chatbot-header { display: flex; align-items: center; justify-content: space-between; padding: 10px 12px; background: #4f46e5; color: #fff; }
.cv-chatbot-body { padding: 12px; height: 48vh; overflow-y: auto; }
.cv-chatbot-input { display: flex; gap: 8px; padding: 10px; border-top: 1px solid #eee; }
.cv-chatbot-bubble { margin: 8px 0; padding: 10px 12px; border-radius: 10px; line-height: 1.4; }
.cv-chatbot-bubble.user { background: #eef2ff; color: #3730a3; align-self: flex-end; }
.cv-chatbot-bubble.bot { background: #f8fafc; color: #0f172a; border: 1px solid #e5e7eb; }
</style>
<div class="cv-chatbot">
    <button id="cv-chatbot-toggle" class="rounded-full bg-indigo-600 text-white shadow-md px-4 py-2 hover:bg-indigo-700">AI Assistant</button>
    <div id="cv-chatbot-panel" class="cv-chatbot-panel">
        <div class="cv-chatbot-header">
            <div class="font-semibold">CV Assistant</div>
            <button id="cv-chatbot-close" class="text-white/80 hover:text-white">×</button>
        </div>
        <div id="cv-chatbot-body" class="cv-chatbot-body flex flex-col"></div>
        <div class="cv-chatbot-input">
            <select id="cv-insert-target" class="border rounded-md px-2 py-2 text-sm">
                <option value="content.professional_summary">Insert → Summary</option>
                <option value="content.work_experience.0.description">Insert → Experience #1</option>
            </select>
            <button id="cv-insert-apply" class="bg-emerald-600 text-white rounded-md px-3 py-2 text-sm">Insert</button>
            <input id="cv-chatbot-message" type="text" placeholder="Ask to refine summary, bullets..." class="flex-1 border rounded-md px-3 py-2 text-sm" />
            <button id="cv-chatbot-send" class="bg-indigo-600 text-white rounded-md px-3 py-2 text-sm">Send</button>
        </div>
    </div>
    <script>
    const botToggle = document.getElementById('cv-chatbot-toggle');
    const botPanel = document.getElementById('cv-chatbot-panel');
    const botClose = document.getElementById('cv-chatbot-close');
    const botBody = document.getElementById('cv-chatbot-body');
    const botInput = document.getElementById('cv-chatbot-message');
    const botSend = document.getElementById('cv-chatbot-send');
    const insertBtn = document.getElementById('cv-insert-apply');
    const insertTarget = document.getElementById('cv-insert-target');
    let lastBotReply = '';
    function appendBubble(text, who = 'bot') {
        const div = document.createElement('div');
        div.className = `cv-chatbot-bubble ${who}`;
        div.innerHTML = (text || '').replace(/\n/g,'<br>');
        botBody.appendChild(div);
        botBody.scrollTop = botBody.scrollHeight;
    }
    function getCvData() {
        try { return @json($cv->content ?? []); } catch(e) { return {}; }
    }
    async function sendBotMessage() {
        const text = botInput.value.trim();
        if (!text) return;
        appendBubble(text, 'user');
        botInput.value = '';
        try {
            const res = await fetch('{{ route('api.ai.chat') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ message: text, cv_data: getCvData(), language: '{{ app()->getLocale() === 'ar' ? 'ar' : (config('ai.language','en')) }}' })
            });
            const data = await res.json();
            lastBotReply = data.reply || '...';
            appendBubble(lastBotReply);
        } catch (e) {
            appendBubble('Error contacting assistant.');
        }
    }
    async function applyInsert() {
        if (!lastBotReply) { appendBubble('No AI reply to insert yet.'); return; }
        try {
            const res = await fetch('{{ route('cv.ai.insert', $cv) }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ path: insertTarget.value, value: lastBotReply, mode: 'replace' })
            });
            if (res.ok) { appendBubble('Inserted into CV ✓'); }
            else { appendBubble('Failed to insert.'); }
        } catch(e) { appendBubble('Failed to insert.'); }
    }
    botToggle.addEventListener('click', () => { botPanel.style.display = 'block'; setTimeout(() => botInput.focus(), 50); });
    botClose.addEventListener('click', () => botPanel.style.display = 'none');
    botSend.addEventListener('click', sendBotMessage);
    botInput.addEventListener('keydown', (e) => { if (e.key === 'Enter') sendBotMessage(); });
    insertBtn.addEventListener('click', applyInsert);
    </script>
</div>
