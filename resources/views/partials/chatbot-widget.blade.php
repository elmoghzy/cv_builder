<style>
.fi-chatbot { position: fixed; bottom: 18px; right: 18px; z-index: 70; }
.fi-chatbot-panel { width: 360px; max-height: 70vh; background: #fff; box-shadow: 0 10px 25px rgba(0,0,0,.15); border-radius: 12px; overflow: hidden; display: none; border: 1px solid #e5e7eb; }
.fi-chatbot-header { display:flex; align-items:center; justify-content:space-between; padding:10px 12px; background:#4f46e5; color:#fff; }
.fi-chatbot-body { padding: 12px; height: 48vh; overflow-y: auto; display:flex; flex-direction:column; gap:8px; }
.fi-bubble { padding: 10px 12px; border-radius: 10px; line-height: 1.45; max-width: 85%; }
.fi-bubble.user { align-self: flex-end; background: #eef2ff; color: #3730a3; }
.fi-bubble.bot { align-self: flex-start; background: #f8fafc; border: 1px solid #e5e7eb; color: #0f172a; }
.fi-chatbot-input { display:flex; gap:8px; padding:10px; border-top:1px solid #eee; }
</style>
<div class="fi-chatbot">
    <button id="fi-chatbot-toggle" class="rounded-full bg-indigo-600 text-white shadow-md px-4 py-2 hover:bg-indigo-700 hidden">Assistant</button>
    <!-- Floating circular logo launcher (left side) -->
    <button id="fi-chatbot-launcher" title="Ask the assistant" style="position:fixed; left:18px; bottom:90px; width:44px; height:44px; border-radius:999px; border:0; cursor:pointer; z-index:71; background:linear-gradient(135deg,#8b5cf6,#38bdf8); box-shadow:0 8px 20px rgba(16,24,40,.18); display:flex; align-items:center; justify-content:center;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="28" height="28">
            <defs>
                <linearGradient id="fi-g" x1="0" y1="0" x2="1" y2="1">
                    <stop offset="0%" stop-color="#8b5cf6"/>
                    <stop offset="100%" stop-color="#38bdf8"/>
                </linearGradient>
            </defs>
            <circle cx="32" cy="32" r="28" fill="url(#fi-g)"/>
            <path d="M20 22h16a6 6 0 016 6v14a4 4 0 01-4 4H22a4 4 0 01-4-4V24a2 2 0 012-2zm4 4v4h12v-2a2 2 0 00-2-2H24zm0 10h16v6a2 2 0 01-2 2H24a2 2 0 01-2-2v-6z" fill="#fff" opacity=".95"/>
        </svg>
    </button>
    <div id="fi-chatbot-panel" class="fi-chatbot-panel">
        <div class="fi-chatbot-header">
            <div class="font-semibold">CV Assistant</div>
            <button id="fi-chatbot-close" class="text-white/80 hover:text-white">×</button>
        </div>
        <div id="fi-chatbot-body" class="fi-chatbot-body"></div>
        <div class="fi-chatbot-input">
            <input id="fi-chatbot-message" type="text" placeholder="Ask to write a summary, bullets..." class="flex-1 border rounded-md px-3 py-2 text-sm" />
            <button id="fi-chatbot-send" class="bg-indigo-600  rounded-md px-3 py-2 text-sm">Send</button>
        </div>
    </div>
</div>
<script>
(function(){
    const t = document.getElementById('fi-chatbot-toggle');
    const l = document.getElementById('fi-chatbot-launcher');
    const p = document.getElementById('fi-chatbot-panel');
    const c = document.getElementById('fi-chatbot-close');
    const b = document.getElementById('fi-chatbot-body');
    const i = document.getElementById('fi-chatbot-message');
    const s = document.getElementById('fi-chatbot-send');
    function add(text, who='bot'){ const d=document.createElement('div'); d.className='fi-bubble '+who; d.innerHTML=(text||'').replace(/\n/g,'<br>'); b.appendChild(d); b.scrollTop=b.scrollHeight; }
    async function send(){ const text=i.value.trim(); if(!text) return; add(text,'user'); i.value=''; try{ const res=await fetch('{{ route('api.ai.chat') }}',{ method:'POST', headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json' }, body: JSON.stringify({ message:text, cv_data:{}, language:'{{ app()->getLocale()==='ar' ? 'ar' : (config('ai.language','en')) }}' })}); const data=await res.json(); add(data.reply||'...'); } catch(e){ add('Error contacting assistant.'); } }
    function openPanel(){ p.style.display='block'; setTimeout(()=>i.focus(),50); }
    t.addEventListener('click', openPanel);
    if (l) l.addEventListener('click', openPanel);
    c.addEventListener('click', ()=> p.style.display='none');
    s.addEventListener('click', send);
    i.addEventListener('keydown', e=>{ if(e.key==='Enter') send(); });
})();
</script>
@once
<div id="cvchatbot" x-data="chatbot()" class="fixed bottom-6 right-6 z-[9999]">
    <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-80" x-show="open" x-transition>
        <div class="p-3 border-b flex items-center justify-between">
            <div class="font-semibold text-gray-800" x-text="selectedLanguage==='en' ? 'Crafty – CV Assistant' : 'كرافتـي - مساعد السيرة'"></div>
            <div class="flex items-center gap-2">
                <div class="bg-gray-100 rounded-md overflow-hidden text-[11px]">
                    <button type="button" @click="setLang('en')" :class="selectedLanguage==='en' ? 'bg-blue-600 text-white' : 'text-gray-700'" class="px-2 py-1">EN</button>
                    <button type="button" @click="setLang('ar')" :class="selectedLanguage==='ar' ? 'bg-blue-600 text-white' : 'text-gray-700'" class="px-2 py-1">AR</button>
                </div>
                <button class="text-gray-500 hover:text-red-500" @click="open=false">✕</button>
            </div>
        </div>
        <div class="p-3 h-64 overflow-y-auto space-y-3" id="chat-body">
            <template x-for="item in messages" :key="item.id">
                <div :class="item.role==='user' ? 'text-right' : 'text-left'">
                    <div :class="item.role==='user' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-800'" class="inline-block px-3 py-2 rounded-lg max-w-[90%] whitespace-pre-line" x-text="item.text"></div>
                </div>
            </template>
        </div>
        <div class="p-3 border-t">
            <form @submit.prevent="send">
                <input x-model="input" type="text" class="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" :placeholder="selectedLanguage==='en' ? 'Ask Crafty to improve your summary or experience...' : 'اسأل كرافتـي عن صياغة الملخص أو الخبرة...'" />
            </form>
            <div class="mt-2 text-[11px] text-gray-500" x-text="selectedLanguage==='en' ? 'Powered by Gemini for professional responses.' : 'يعتمد على Gemini لإجابات احترافية.'"></div>
        </div>
    </div>
    <button @click="open=!open" class="rounded-full bg-blue-600 text-white w-12 h-12 shadow-lg hover:bg-blue-700 flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4-.8L3 20l.8-3.2A8.944 8.944 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
    </button>
</div>

<script>
function chatbot(){
    return {
        open: false,
        input: '',
        selectedLanguage: 'en',
        messages: [],
        init(){
            const textEn = "Hi! I'm Crafty. Tell me your target role and experience, and I'll help you craft a strong summary and bullet points.";
            const textAr = 'أهلًا! أنا كرافتـي. أخبرني بالوظيفة المستهدفة وخبرتك وسأساعدك في صياغة ملخص وعبارات خبرة قوية.';
            this.messages.push({ id: Date.now(), role: 'assistant', text: this.selectedLanguage==='en' ? textEn : textAr });
        },
        setLang(lang){
            this.selectedLanguage = lang;
            // Optional: Add a short system message to reflect language change
            const notice = lang==='en' ? 'Language set to English.' : 'تم اختيار العربية.';
            this.messages.push({ id: Date.now()+3, role: 'assistant', text: notice });
            this.$nextTick(()=>{
                const el = document.getElementById('chat-body');
                if (el) el.scrollTop = el.scrollHeight;
            });
        },
        async send(){
            const text = this.input.trim();
            if(!text) return;
            this.messages.push({ id: Date.now(), role: 'user', text });
            this.input = '';
            try {
                const headers = { 'Content-Type': 'application/json' };
                const meta = document.querySelector('meta[name="csrf-token"]');
                if (meta && meta.getAttribute('content')) headers['X-CSRF-TOKEN'] = meta.getAttribute('content');
                const res = await fetch('/api/chatbot', {
                    method: 'POST',
                    headers,
                    body: JSON.stringify({ message: text, language: this.selectedLanguage })
                });
                const data = await res.json();
                const reply = data.reply || data.error || 'تعذر جلب الرد الآن.';
                this.messages.push({ id: Date.now()+1, role: 'assistant', text: reply });
                this.$nextTick(()=>{
                    const el = document.getElementById('chat-body');
                    el.scrollTop = el.scrollHeight;
                });
            } catch (e) {
                this.messages.push({ id: Date.now()+2, role: 'assistant', text: 'حدث خطأ أثناء الاتصال بالخادم.' });
            }
        }
    }
}
</script>

@endonce
