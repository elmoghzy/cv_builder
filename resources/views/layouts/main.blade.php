<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CV Builder Egypt') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Filament Styles -->
    @filamentStyles
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @filamentScripts
    
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #f8fafc; 
            min-height: 100vh; 
            margin: 0; 
        }

        /* Filament-inspired Navbar */
        .nav-professional { 
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-bottom: 1px solid #475569; 
        }
        .nav-professional .container { max-width: 1120px; margin: 0 auto; padding: 0 1rem; }
        .nav-professional .nav-inner { display: flex; align-items: center; justify-content: space-between; height: 64px; }
        .nav-professional .brand { 
            font-size: 1.375rem; 
            font-weight: 700; 
            color: #f1f5f9; 
            text-decoration: none;
            background: linear-gradient(135deg, #f59e0b, #f97316);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        @media (min-width: 768px) {
            .nav-professional .nav-links {
                display: flex;
                gap: 0.25rem;
                align-items: center;
            }
        }
        .nav-professional .nav-link { 
            color: #cbd5e1; 
            font-size: 0.875rem; 
            font-weight: 500; 
            text-decoration: none; 
            padding: 0.625rem 1rem; 
            border-radius: 0.5rem; 
            transition: all 0.2s ease;
            position: relative;
        }
        .nav-professional .nav-link:hover, .nav-professional .nav-link.active { 
            background-color: rgba(248, 250, 252, 0.1); 
            color: #f1f5f9;
            transform: translateY(-1px);
        }
        .nav-professional .nav-user { 
            color: #e2e8f0; 
            font-size: 0.875rem; 
            padding: 0.5rem 1rem;
            background: rgba(248, 250, 252, 0.05);
            border-radius: 0.5rem;
            border: 1px solid rgba(248, 250, 252, 0.1);
        }
        .nav-professional .logout-btn { 
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #ffffff; 
            padding: 0.625rem 1rem; 
            border-radius: 0.5rem; 
            font-size: 0.875rem; 
            font-weight: 600; 
            border: none; 
            cursor: pointer; 
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2);
        }
        .nav-professional .logout-btn:hover { 
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
        }

        /* Mobile Menu and Toggle */
        .menu-toggle { cursor: pointer; color: #e2e8f0; transition: color 0.2s; }
        .menu-toggle:hover { color: #f1f5f9; }
        .mobile-menu { 
            background: linear-gradient(135deg, #0f172a, #1e293b);
            position: absolute; 
            top: 64px; 
            right: 0; 
            left: 0; 
            border-top: 1px solid #475569;
            box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1);
        }
        .mobile-menu .nav-link { 
            display: block; 
            padding: 1rem 1.5rem; 
            color: #cbd5e1; 
            text-decoration: none; 
            border-bottom: 1px solid #334155;
            transition: all 0.2s;
        }
        .mobile-menu .nav-link:hover { 
            background-color: rgba(248, 250, 252, 0.05); 
            color: #f1f5f9;
        }
        .mobile-menu .nav-link:last-child { border-bottom: none; }

        /* Responsive: hide full nav on small, show toggle; reverse on desktop */
        @media (max-width: 767px) {
            .nav-professional .nav-links { display: none; }
            .menu-toggle { display: block; }
        }
        @media (min-width: 768px) {
            .menu-toggle { display: none; }
            .mobile-menu { display: none; }
        }

        /* Filament-style content container */
        .main-content {
            background-color: #f8fafc;
            min-height: calc(100vh - 64px);
        }
    </style>
</head>
<body class="font-sans antialiased h-full">
    <nav class="nav-professional">
        <div class="container">
            <div class="nav-inner">
                <!-- Brand -->
                <a href="{{ url('/user') }}" class="brand">CV Builder Egypt</a>

                <!-- Desktop Links -->
                <div class="nav-links">
                    <a href="{{ url('/user') }}" class="nav-link">Dashboard</a>
                    <a href="{{ route('cv.index') }}" class="nav-link {{ Route::is('cv.index') ? 'active' : '' }}">My CVs</a>
                    <a href="{{ route('cv.builder') }}" class="nav-link {{ Route::is('cv.builder') ? 'active' : '' }}">Create CV</a>
                    <a href="{{ route('how.it.works') }}" class="nav-link {{ Route::is('how.it.works') ? 'active' : '' }}">How it Works</a>
                </div>

                <!-- User & Toggle -->
                <div class="flex items-center space-x-4">
                    @auth
                        <span class="nav-user">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="logout-btn">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="nav-link">Login</a>
                        <a href="{{ route('register') }}" class="nav-link">Register</a>
                    @endauth
                    <!-- Mobile menu toggle -->
                    <div class="menu-toggle" onclick="toggleMobileMenu()">
                        <svg id="menu-icon" class="h-6 w-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg id="close-icon" class="h-6 w-6 text-gray-600 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden mobile-menu">
            <a href="{{ url('/user') }}" class="nav-link">Dashboard</a>
            <a href="{{ route('cv.index') }}" class="nav-link">My CVs</a>
            <a href="{{ route('cv.builder') }}" class="nav-link">Create CV</a>
            <a href="{{ route('how.it.works') }}" class="nav-link">How it Works</a>
            @guest
                <a href="{{ route('login') }}" class="nav-link">Login</a>
                <a href="{{ route('register') }}" class="nav-link">Register</a>
            @endguest
        </div>
    </nav>
    <div class="main-content">
        <header class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
            @yield('header')
        </header>
        <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-12">
            @if(session('success'))
                <x-alert type="success" title="Success" :message="session('success')" />
            @endif
            @if(session('error'))
                <x-alert type="error" title="Error" :message="session('error')" />
            @endif
            @yield('content')
        </main>
        <footer class="mt-auto">
            @include('layouts.footer')
        </footer>
    </div>
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const menuIcon = document.getElementById('menu-icon');
            const closeIcon = document.getElementById('close-icon');
            menu.classList.toggle('hidden');
            menuIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        }
    </script>
    <style>
        .site-chatbot { position: fixed; bottom: 20px; right: 20px; z-index: 70; }
        .site-chatbot-panel { width: 380px; max-height: 72vh; background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; box-shadow: 0 25px 50px -12px rgba(0,0,0,.25); overflow: hidden; display: none; }
        .site-chatbot-header { background: linear-gradient(135deg,#4f46e5,#4338ca); color: #fff; padding: 10px 12px; display: flex; align-items: center; justify-content: space-between; }
        .site-chatbot-body { padding: 12px; height: 50vh; overflow-y: auto; display: flex; flex-direction: column; gap: 8px; }
        .site-chatbot-input { display: flex; gap: 8px; padding: 10px; border-top: 1px solid #eee; }
        .bubble { padding: 10px 12px; border-radius: 12px; line-height: 1.45; max-width: 85%; }
        .bubble.user { align-self: flex-end; background: #eef2ff; color: #3730a3; }
        .bubble.bot { align-self: flex-start; background: #f8fafc; border: 1px solid #e5e7eb; color: #0f172a; }
        .suggestions { display: flex; gap: 8px; flex-wrap: wrap; padding: 8px 12px; border-top: 1px solid #eef2ff; }
        .sugg { background: #eef2ff; color: #3730a3; border: 1px solid #e0e7ff; padding: 6px 10px; border-radius: 999px; font-size: 12px; cursor: pointer; }
    </style>
    <div class="site-chatbot">
        <button id="site-chatbot-toggle" class="rounded-full bg-indigo-600 text-white shadow-md px-4 py-2 hover:bg-indigo-700">Need help?</button>
        <div id="site-chatbot-panel" class="site-chatbot-panel">
            <div class="site-chatbot-header">
                <div class="font-semibold">Assistant</div>
                <button id="site-chatbot-close" class="text-white/80 hover:text-white">×</button>
            </div>
            <div id="site-chatbot-body" class="site-chatbot-body"></div>
            <div class="suggestions">
                <span class="sugg">Write a professional summary</span>
                <span class="sugg">Improve my last role bullets</span>
                <span class="sugg">List key skills for frontend</span>
            </div>
            <div class="site-chatbot-input">
                <input id="site-chatbot-input" type="text" placeholder="Ask anything about your CV..." class="flex-1 border rounded-md px-3 py-2 text-sm" />
                <button id="site-chatbot-send" class="bg-indigo-600 text-white rounded-md px-3 py-2 text-sm">Send</button>
            </div>
        </div>
    </div>
    <script>
        const scToggle = document.getElementById('site-chatbot-toggle');
        const scPanel = document.getElementById('site-chatbot-panel');
        const scClose = document.getElementById('site-chatbot-close');
        const scBody = document.getElementById('site-chatbot-body');
        const scInput = document.getElementById('site-chatbot-input');
        const scSend = document.getElementById('site-chatbot-send');
        const scSugg = document.querySelectorAll('.sugg');
        function addBubble(text, who='bot'){ const d=document.createElement('div'); d.className='bubble '+who; d.innerHTML=(text||'').replace(/\n/g,'<br>'); scBody.appendChild(d); scBody.scrollTop=scBody.scrollHeight; }
        async function askAssistant(question){
            addBubble(question,'user');
            try {
                const res = await fetch('{{ route('api.ai.chat') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                    body: JSON.stringify({ message: question, cv_data: {}, language: '{{ app()->getLocale()==='ar' ? 'ar' : (config('ai.language','en')) }}' })
                });
                const data = await res.json();
                addBubble(data.reply || '...','bot');
            } catch(e){ addBubble('Connection error.','bot'); }
        }
        scToggle.addEventListener('click', ()=>{ scPanel.style.display='block'; setTimeout(()=>scInput.focus(),50); });
        scClose.addEventListener('click', ()=> scPanel.style.display='none');
        scSend.addEventListener('click', ()=>{ const t=scInput.value.trim(); if(!t) return; scInput.value=''; askAssistant(t); });
        scInput.addEventListener('keydown', (e)=>{ if(e.key==='Enter'){ const t=scInput.value.trim(); if(!t) return; scInput.value=''; askAssistant(t); }});
        scSugg.forEach(el=> el.addEventListener('click', ()=> askAssistant(el.textContent)) );
    </script>
</body>
</html>
