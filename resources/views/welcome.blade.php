<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CVCraft - ATS-Compliant CV Builder for Egypt</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#1E40AF',
                        accent: '#F59E0B',
                        success: '#10B981'
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s ease-in-out infinite',
                        'bounce-slow': 'bounce 2s infinite',
                        'fade-in': 'fadeIn 1s ease-out forwards',
                        'slide-up': 'slideUp 0.8s ease-out forwards'
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s ease-out;
        }
        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 text-white min-h-screen overflow-x-hidden">
    
    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass-effect">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="text-xl font-bold">CVCraft</span>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    {{-- <a href="#features" class="hover:text-blue-400 transition-colors">Features</a>
                    <a href="#templates" class="hover:text-blue-400 transition-colors">Templates</a>
                    <a href="#pricing" class="hover:text-blue-400 transition-colors">Pricing</a>
                    <a href="#contact" class="hover:text-blue-400 transition-colors">Contact</a> --}}
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button onclick="toggleMobileMenu()" class="text-white hover:text-blue-400 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="hidden md:block text-sm hover:text-blue-400 transition-colors">Login</a>
                    <a href="{{ route('register') }}" class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-2 rounded-full text-sm font-semibold hover:shadow-lg hover:scale-105 transition-all duration-300">
                        Get Started
                    </a>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden bg-black/20 backdrop-blur-lg border-t border-white/10">
                <div class="px-4 py-4 space-y-4">
                    {{-- <a href="#features" class="block hover:text-blue-400 transition-colors">Features</a>
                    <a href="#templates" class="block hover:text-blue-400 transition-colors">Templates</a>
                    <a href="#pricing" class="block hover:text-blue-400 transition-colors">Pricing</a>
                    <a href="#contact" class="block hover:text-blue-400 transition-colors">Contact</a> --}}
                    <div class="pt-4 border-t border-white/10">
                        <a href="{{ route('login') }}" class="block w-full text-left mb-2 hover:text-blue-400 transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 px-4 py-2 rounded-full text-sm font-semibold">
                            Get Started
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-purple-600/20"></div>
        
        <!-- Floating Elements -->
        <div class="absolute top-20 left-10 w-20 h-20 bg-gradient-to-r from-pink-500 to-yellow-500 rounded-full opacity-20 animate-float"></div>
        <div class="absolute top-40 right-20 w-16 h-16 bg-gradient-to-r from-green-500 to-blue-500 rounded-full opacity-20 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-20 left-1/4 w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full opacity-20 animate-float" style="animation-delay: 4s;"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="animate-fade-in">
                    <h1 class="text-5xl lg:text-7xl font-bold leading-tight mb-6">
                        Create <span class="bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">ATS-Ready</span> CVs That Get You <span class="bg-gradient-to-r from-yellow-400 to-pink-400 bg-clip-text text-transparent">Hired</span>
                    </h1>
                    <p class="text-xl text-gray-300 mb-8 leading-relaxed">
                        Build professional CVs optimized for Applicant Tracking Systems in Egypt. Get past the robots and land your dream job with our ATS-compliant templates.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 mb-8">
                        <a href="{{ route('cv.builder') }}" class="group bg-gradient-to-r from-blue-500 to-purple-600 px-8 py-4 rounded-full text-lg font-semibold hover:shadow-2xl hover:scale-105 transition-all duration-300 flex items-center justify-center">
                            Build My CV Now
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                        {{-- <button class="border border-white/30 px-8 py-4 rounded-full text-lg font-semibold hover:bg-white/10 transition-all duration-300 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1.5a2.5 2.5 0 010 5H9m4.5-5a2.5 2.5 0 010 5M12 3v1m6.364 1.636l-.707.707M21 12h-1M18.364 17.364l-.707-.707M12 20.5v1m-6.364-1.636l.707-.707M3 12h1m2.636-5.364l.707.707"></path>
                            </svg>
                            Watch Demo
                        </button> --}}
                    </div>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-8">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-blue-400 mb-1">95%</div>
                            <div class="text-sm text-gray-400">ATS Pass Rate</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-purple-400 mb-1">10K+</div>
                            <div class="text-sm text-gray-400">CVs Created</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-pink-400 mb-1">24/7</div>
                            <div class="text-sm text-gray-400">Support</div>
                        </div>
                    </div>
                </div>
                
                <!-- Hero Image/Animation -->
                <div class="relative animate-slide-up">
                    <div class="relative z-10">
                        <div class="glass-effect rounded-3xl p-8 transform rotate-3 hover:rotate-0 transition-transform duration-500">
                            <div class="bg-white rounded-2xl p-6 text-gray-800">
                                <div class="flex items-center mb-4">
                                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg">A</div>
                                    <div class="ml-4">
                                        <h3 class="font-semibold">Ahmed Mohammed</h3>
                                        <p class="text-sm text-gray-600">Software Engineer</p>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div class="h-2 bg-gray-200 rounded-full">
                                        <div class="h-2 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full w-4/5 animate-pulse-slow"></div>
                                    </div>
                                    <div class="h-2 bg-gray-200 rounded-full">
                                        <div class="h-2 bg-gradient-to-r from-green-500 to-blue-500 rounded-full w-3/5 animate-pulse-slow" style="animation-delay: 1s;"></div>
                                    </div>
                                    <div class="h-2 bg-gray-200 rounded-full">
                                        <div class="h-2 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full w-5/6 animate-pulse-slow" style="animation-delay: 2s;"></div>
                                    </div>
                                </div>
                                <div class="mt-6 flex justify-between items-center">
                                    <span class="text-sm text-green-600 font-semibold">✓ ATS Optimized</span>
                                    <div class="flex space-x-1">
                                        <div class="w-2 h-2 bg-green-500 rounded-full animate-bounce"></div>
                                        <div class="w-2 h-2 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0.1s;"></div>
                                        <div class="w-2 h-2 bg-purple-500 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Background Elements -->
                    <div class="absolute -top-4 -right-4 w-24 h-24 bg-gradient-to-r from-yellow-400 to-pink-500 rounded-full opacity-20 animate-pulse-slow"></div>
                    <div class="absolute -bottom-8 -left-8 w-32 h-32 bg-gradient-to-r from-green-400 to-blue-500 rounded-full opacity-20 animate-pulse-slow" style="animation-delay: 1s;"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Templates Section -->
    <section id="templates" class="py-20 animate-on-scroll">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-bold mb-6">
                    Professional <span class="bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">Templates</span>
                </h2>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    Choose from our carefully crafted ATS-compliant templates designed to pass through automated screening systems.
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Modern Template -->
                <div class="group glass-effect rounded-2xl p-6 hover:scale-105 transition-all duration-300">
                    <div class="overflow-hidden rounded-lg mb-4">
                        <img src="{{ asset('images/templates/modern.png') }}" alt="Modern CV Template" class="w-full h-auto transform group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Modern</h3>
                    <p class="text-gray-300 text-sm">A fresh and clean design for the modern professional. ATS-friendly and stylish.</p>
                </div>
                
                <!-- Creative Template -->
                <div class="group glass-effect rounded-2xl p-6 hover:scale-105 transition-all duration-300">
                    <div class="overflow-hidden rounded-lg mb-4">
                        <img src="{{ asset('images/templates/creative.png') }}" alt="Creative CV Template" class="w-full h-auto transform group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Creative</h3>
                    <p class="text-gray-300 text-sm">A bold, two-column layout for those who want to stand out. Perfect for designers and marketers.</p>
                </div>
                
                <!-- Professional Template -->
                <div class="group glass-effect rounded-2xl p-6 hover:scale-105 transition-all duration-300">
                    <div class="overflow-hidden rounded-lg mb-4">
                        <img src="{{ asset('images/templates/professional.png') }}" alt="Professional CV Template" class="w-full h-auto transform group-hover:scale-110 transition-transform duration-500">
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Professional</h3>
                    <p class="text-gray-300 text-sm">A classic, elegant, and traditional design. Ideal for corporate and academic roles.</p>
                </div>
            </div>
        </div>
    </section>
    <section id="features" class="py-20 animate-on-scroll">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-bold mb-6">
                    Why Choose <span class="bg-gradient-to-r from-blue-400 to-purple-400 bg-clip-text text-transparent">CVCraft</span>?
                </h2>
                <p class="text-xl text-gray-300 max-w-3xl mx-auto">
                    Our platform is specifically designed for the Egyptian job market with ATS-compliant templates that get results.
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="group glass-effect rounded-2xl p-8 hover:scale-105 transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:rotate-12 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">ATS Optimized</h3>
                    <p class="text-gray-300">
                        Our templates are tested with major ATS systems like Taleo and Workable to ensure your CV gets through the first screening.
                    </p>
                </div>
                
                <!-- Feature 2 -->
                <div class="group glass-effect rounded-2xl p-8 hover:scale-105 transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:rotate-12 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Quick & Easy</h3>
                    <p class="text-gray-300">
                        Create your professional CV in minutes with our intuitive step-by-step builder. No design skills required.
                    </p>
                </div>
                
                <!-- Feature 3 -->
                <div class="group glass-effect rounded-2xl p-8 hover:scale-105 transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mb-6 group-hover:rotate-12 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Secure Payment</h3>
                    <p class="text-gray-300">
                        One-time payment of just EGP 100 per CV. Secure Visa payments through PayMob with full data protection.
                    </p>
                </div>
                
                <!-- Feature 4 -->
                <div class="group glass-effect rounded-2xl p-8 hover:scale-105 transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-red-600 rounded-2xl flex items-center justify-center mb-6 group-hover:rotate-12 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17v4a2 2 0 002 2h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Professional Templates</h3>
                    <p class="text-gray-300">
                        Choose from multiple professionally designed templates that follow industry standards and best practices.
                    </p>
                </div>
                
                <!-- Feature 5 -->
                <div class="group glass-effect rounded-2xl p-8 hover:scale-105 transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:rotate-12 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Egypt Focused</h3>
                    <p class="text-gray-300">
                        Designed specifically for the Egyptian job market with local payment methods and HR preferences in mind.
                    </p>
                </div>
                
                <!-- Feature 6 -->
                <div class="group glass-effect rounded-2xl p-8 hover:scale-105 transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-r from-teal-500 to-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:rotate-12 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Instant Download</h3>
                    <p class="text-gray-300">
                        Get your professionally formatted PDF CV instantly after payment. No waiting, no delays.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-20 animate-on-scroll">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl lg:text-5xl font-bold mb-6">
                Simple <span class="bg-gradient-to-r from-green-400 to-blue-400 bg-clip-text text-transparent">Pricing</span>
            </h2>
            <p class="text-xl text-gray-300 mb-12">
                No subscriptions, no hidden fees. Pay once per CV and get professional results.
            </p>
            
            <div class="glass-effect rounded-3xl p-8 max-w-md mx-auto hover:scale-105 transition-all duration-300">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-r from-green-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-white">CV</span>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Per CV Download</h3>
                    <div class="mb-6">
                        <span class="text-5xl font-bold">EGP 100</span>
                        <span class="text-gray-400">/CV</span>
                    </div>
                    <ul class="text-left space-y-3 mb-8">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            ATS-Optimized Templates
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Professional PDF Download
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Real-time Preview
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Secure Visa Payment
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Instant Download
                        </li>
                    </ul>
                    <a href="{{ route('cv.builder') }}">
                    <button  class="w-full bg-gradient-to-r from-green-500 to-blue-600 py-4 rounded-full text-lg font-semibold hover:shadow-2xl hover:scale-105 transition-all duration-300">
                        Create My CV Now
                    </button></a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 animate-on-scroll">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="glass-effect rounded-3xl p-12">
                <h2 class="text-4xl lg:text-5xl font-bold mb-6">
                    Ready to Land Your <span class="bg-gradient-to-r from-yellow-400 to-pink-400 bg-clip-text text-transparent">Dream Job</span>?
                </h2>
                <p class="text-xl text-gray-300 mb-8">
                    Join thousands of successful job seekers in Egypt who got hired with our ATS-optimized CVs.
                </p>
                <a href="{{ route('cv.builder') }}">
                    <button class="bg-gradient-to-r from-yellow-500 to-pink-600 px-12 py-4 rounded-full text-xl font-semibold hover:shadow-2xl hover:scale-105 transition-all duration-300 animate-bounce-slow">
                        Start Building Now →
                    </button>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-white/10 py-12 animate-on-scroll">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <span class="text-lg font-bold">CVCraft</span>
                    </div>
                    <p class="text-gray-400">
                        Creating ATS-compliant CVs for Egyptian job seekers {{ now()->year }}.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Product</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#features" class="hover:text-white transition-colors">Features</a></li>
                        <li><a href="#templates" class="hover:text-white transition-colors">Templates</a></li>
                        <li><a href="#pricing" class="hover:text-white transition-colors">Pricing</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Support</h4>
                    <ul class="space-y-2 text-gray-400">
                        {{-- <li><a href="#" class="hover:text-white transition-colors">Help Center</a></li> --}}
                        <li><a href="#" class="hover:text-white transition-colors">Contact Us</a></li>
                       
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Connect</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-white/20 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-white/20 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-white/20 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-white/10 mt-12 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-sm">
                        © {{ now()->year }} CVCraft. All rights reserved. Made with ❤️ for Egyptian job seekers.
                    </p>
                    
                </div>
            </div>
        </div>
    </footer>

    <!-- Scroll Animation Script -->
    <script>
        // Intersection Observer for scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        // Observe all animate-on-scroll elements
        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Mobile menu toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        // Add click handlers to CTA buttons
        document.querySelectorAll('button').forEach(button => {
            if (button.textContent.includes('Build My CV') || button.textContent.includes('Get Started') || button.textContent.includes('Create My CV')) {
                button.addEventListener('click', function() {
                    // This would redirect to the CV builder page
                    console.log('Redirecting to CV builder...');
                    // window.location.href = '/cv/create';
                });
            }
        });
    </script>

</body>
</html>