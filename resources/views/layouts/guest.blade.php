<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Cv Builder') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=cairo:400,600,700|figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in {
                animation: fadeIn 0.5s ease-out forwards;
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased" style="font-family: 'Cairo', 'Figtree', sans-serif;">
        <div class="min-h-screen lg:grid lg:grid-cols-2">
            <!-- Form Section -->
            <div class="flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
                <div class="w-full max-w-md space-y-8">
                    {{ $slot }}
                </div>
            </div>
            
            <!-- Branding Section -->
            <div class="hidden lg:flex items-center justify-center bg-gradient-to-tr from-blue-600 to-purple-700 p-12 text-white text-center relative overflow-hidden">
                <div class="z-10 animate-fade-in">
                    <a href="/" class="inline-block mb-6">
                         <svg class="w-24 h-24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 14.5C15.866 14.5 19 15.8134 19 17.5C19 19.1866 15.866 20.5 12 20.5C8.13401 20.5 5 19.1866 5 17.5C5 15.8134 8.13401 14.5 12 14.5Z" fill="white" fill-opacity="0.8"/>
                            <path d="M12 12C14.2091 12 16 10.2091 16 8C16 5.79086 14.2091 4 12 4C9.79086 4 8 5.79086 8 8C8 10.2091 9.79086 12 12 12Z" fill="white"/>
                        </svg>
                    </a>
                    <h1 class="text-4xl font-bold tracking-tight">CV Builder Pro</h1>
                    <p class="mt-4 text-lg opacity-80 max-w-sm mx-auto">
                        أنشئ سيرتك الذاتية الاحترافية في دقائق معدودة. تصميمات عصرية وسهولة في الاستخدام.
                    </p>
                </div>
                <!-- Background shapes -->
                <div class="absolute top-0 left-0 w-32 h-32 bg-white/10 rounded-full -translate-x-1/3 -translate-y-1/3"></div>
                <div class="absolute bottom-0 right-0 w-48 h-48 bg-white/10 rounded-full translate-x-1/3 translate-y-1/3"></div>
            </div>
        </div>
    </body>
</html>
