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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #ffffff; min-height: 100vh; margin: 0; }

        /* Professional Navbar */
        .nav-professional { background-color: #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); border-bottom: 1px solid #e5e7eb; }
        .nav-professional .container { max-width: 1120px; margin: 0 auto; padding: 0 1rem; }
        .nav-professional .nav-inner { display: flex; align-items: center; justify-content: space-between; height: 60px; }
        .nav-professional .brand { font-size: 1.25rem; font-weight: 700; color: #1f2937; text-decoration: none; }
        @media (min-width: 768px) {
            .nav-professional .nav-links {
                display: flex;
                gap: 1rem;
                align-items: center;
            }
        }
        .nav-professional .nav-link { color: #4b5563; font-size: 0.875rem; font-weight: 600; text-decoration: none; padding: 0.5rem; border-radius: 0.375rem; transition: background-color 0.2s; }
        .nav-professional .nav-link:hover, .nav-professional .nav-link.active { background-color: #f3f4f6; color: #1f2937; }
        .nav-professional .nav-user { color: #6b7280; font-size: 0.875rem; padding: 0.5rem; }
        .nav-professional .logout-btn { background-color: #e5e7eb; color: #374151; padding: 0.5rem 0.75rem; border-radius: 0.375rem; font-size: 0.875rem; font-weight: 600; border: none; cursor: pointer; transition: background-color 0.2s; }
        .nav-professional .logout-btn:hover { background-color: #d1d5db; }

        /* Mobile Menu and Toggle */
        .menu-toggle { cursor: pointer; }
        .mobile-menu { background-color: #ffffff; position: absolute; top: 60px; right: 0; left: 0; border-bottom: 1px solid #e5e7eb; }
        .mobile-menu .nav-link { display: block; padding: 0.75rem 1rem; color: #4b5563; text-decoration: none; border-bottom: 1px solid #e5e7eb; }
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
    </style>
</head>
<body class="font-sans antialiased h-full">
    <nav class="nav-professional">
        <div class="container">
            <div class="nav-inner">
                <!-- Brand -->
                <a href="{{ route('dashboard') }}" class="brand">CV Builder Egypt</a>

                <!-- Desktop Links -->
                <div class="nav-links">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="{{ route('cv.index') }}" class="nav-link {{ Route::is('cv.index') ? 'active' : '' }}">My CVs</a>
                    <a href="{{ route('cv.builder') }}" class="nav-link {{ Route::is('cv.builder') ? 'active' : '' }}">Create CV</a>
                    <a href="{{ route('how.it.works') }}" class="nav-link {{ Route::is('how.it.works') ? 'active' : '' }}">How it Works</a>
                </div>

                <!-- User & Toggle -->
                <div class="flex items-center space-x-4">
                    <span class="nav-user">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
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
            <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
            <a href="{{ route('cv.index') }}" class="nav-link">My CVs</a>
            <a href="{{ route('cv.builder') }}" class="nav-link">Create CV</a>
        </div>
    </nav>
    <div class="py-4">
        <header class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @yield('header')
        </header>
        <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if(session('success'))<div class="mb-8 floating-card success-alert rounded-2xl p-6 animate-slide-down"></div>@endif
            @if(session('error'))<div class="mb-8 floating-card error-alert rounded-2xl p-6 animate-slide-down"></div>@endif
            @yield('content')
            <footer class="mt-8 text-center text-gray-500 text-sm">
               
            @include('layouts.footer')
        </footer>
        </main>
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
</body>
</html>
