<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $template->name }} - Template Preview</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="bg-blue-600 text-white px-6 py-4">
                    <h1 class="text-2xl font-bold">{{ $template->name }}</h1>
                    <p class="text-blue-100 mt-1">{{ $template->description }}</p>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Template Information</h3>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="font-medium text-gray-600">Status:</dt>
                                    <dd>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $template->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $template->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="font-medium text-gray-600">Type:</dt>
                                    <dd>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $template->is_premium ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $template->is_premium ? 'Premium' : 'Free' }}
                                        </span>
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="font-medium text-gray-600">Usage Count:</dt>
                                    <dd class="text-gray-900">{{ $template->cvs->count() }} CVs</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="font-medium text-gray-600">Created:</dt>
                                    <dd class="text-gray-900">{{ $template->created_at->format('M d, Y') }}</dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Template Structure</h3>
                            @if($template->content)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ json_encode($template->content, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            @else
                                <p class="text-gray-500 italic">No structure defined</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <h4 class="text-lg font-semibold">Template Preview</h4>
                                <p class="text-gray-600">This template can be used to create professional CVs</p>
                            </div>
                            <div class="space-x-3">
                                <a href="{{ url()->previous() }}" 
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    Back
                                </a>
                                @if($template->is_active)
                                    <a href="{{ url('/cv/builder') }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                        Use This Template
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>