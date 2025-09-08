<footer class="bg-white border-t border-gray-200 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Social Icons -->
        <div class="flex justify-center space-x-6 mb-6">
            <a href="#" class="text-gray-400 hover:text-amber-500 transition-colors duration-200" aria-label="Facebook">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879v-6.99H7.898v-2.89h2.54V9.797c0-2.506 1.493-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.772-1.63 1.562v1.875h2.773l-.443 2.89h-2.33v6.99C18.343 21.128 22 16.991 22 12z" />
                </svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-amber-500 transition-colors duration-200" aria-label="Instagram">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M7.75 2h8.5A5.75 5.75 0 0122 7.75v8.5A5.75 5.75 0 0116.25 22h-8.5A5.75 5.75 0 012 16.25v-8.5A5.75 5.75 0 017.75 2zm0 1.5A4.25 4.25 0 003.5 7.75v8.5A4.25 4.25 0 007.75 20.5h8.5a4.25 4.25 0 004.25-4.25v-8.5A4.25 4.25 0 0016.25 3.5h-8.5zM12 7a5 5 0 110 10 5 5 0 010-10zm0 1.5a3.5 3.5 0 100 7 3.5 3.5 0 000-7zm4.75-.875a1.125 1.125 0 11-2.25 0 1.125 1.125 0 012.25 0z" />
                </svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-amber-500 transition-colors duration-200" aria-label="Twitter">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z" />
                </svg>
            </a>
            <a href="#" class="text-gray-400 hover:text-amber-500 transition-colors duration-200" aria-label="LinkedIn">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                </svg>
            </a>
        </div>
        
        <!-- Footer Links -->
        <div class="flex justify-center space-x-8 mb-6 text-sm">
            <a href="{{ url('/user') }}" class="text-gray-600 hover:text-gray-900 font-medium transition-colors duration-200">Home</a>
            <a href="{{ route('how.it.works') }}" class="text-gray-600 hover:text-gray-900 font-medium transition-colors duration-200">How it Works</a>
            <a href="#" class="text-gray-600 hover:text-gray-900 font-medium transition-colors duration-200">Privacy Policy</a>
            <a href="#" class="text-gray-600 hover:text-gray-900 font-medium transition-colors duration-200">Terms of Service</a>
            <a href="#" class="text-gray-600 hover:text-gray-900 font-medium transition-colors duration-200">Support</a>
        </div>
        
        <!-- Copyright -->
        <div class="text-center">
            <p class="text-sm text-gray-500">
                © {{ date('Y') }} <span class="font-semibold text-gray-600">CV Builder Egypt</span>. All rights reserved.
            </p>
            <p class="text-xs text-gray-400 mt-1">
                Professional ATS-compliant CVs for the Egyptian job market
            </p>
        </div>
    </div>
</footer>
