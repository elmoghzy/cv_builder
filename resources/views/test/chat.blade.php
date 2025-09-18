@extends('layouts.main')

@section('header')
<h1 class="text-2xl font-bold text-gray-900">AI Chat Playground</h1>
<p class="text-sm text-gray-600">Test the web-authenticated endpoint at <code>/ai/chat</code>.</p>
@endsection

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-4">
    <div id="log" class="h-80 overflow-y-auto border rounded p-3 text-sm space-y-2"></div>
    <div class="mt-3 flex gap-2">
        <input id="msg" type="text" class="flex-1 border rounded px-3 py-2" placeholder="Type a message...">
        <button id="send" class="px-4 py-2 rounded bg-indigo-600 text-white">Send</button>
    </div>
</div>

<script>
const log = document.getElementById('log');
const msg = document.getElementById('msg');
const send = document.getElementById('send');

function add(role, text){
  const div = document.createElement('div');
  div.className = role === 'user' ? 'text-right' : 'text-left';
  div.innerHTML = `<span class="inline-block px-3 py-2 rounded ${role==='user'?'bg-indigo-50 text-indigo-700':'bg-gray-50 text-gray-800'}">${(text||'').replace(/\n/g,'<br>')}</span>`;
  log.appendChild(div); log.scrollTop = log.scrollHeight;
}

async function sendMsg(){
  const text = msg.value.trim();
  if(!text) return;
  add('user', text); msg.value = '';
  try {
    const res = await fetch('{{ route('api.ai.chat') }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
      body: JSON.stringify({ message: text, cv_data: {}, language: '{{ app()->getLocale()==='ar' ? 'ar' : (config('ai.language','en')) }}' })
    });
    const data = await res.json();
    add('bot', data.reply || '...');
  } catch(e){ add('bot','Request failed'); }
}

send.addEventListener('click', sendMsg);
msg.addEventListener('keydown', e => { if(e.key==='Enter') sendMsg(); });
</script>
@endsection


