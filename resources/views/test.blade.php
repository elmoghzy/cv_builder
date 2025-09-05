@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-lg rounded-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6 text-center">CV Builder Egypt - Test Page</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-blue-50 p-6 rounded-lg">
                    <h2 class="text-xl font-semibold text-blue-900 mb-4">✅ Database Status</h2>
                    <ul class="space-y-2 text-sm">
                        <li>✅ Users table: {{ \App\Models\User::count() }} users</li>
                        <li>✅ Templates table: {{ \App\Models\Template::count() }} templates</li>
                        <li>✅ CVs table: {{ \App\Models\Cv::count() }} CVs</li>
                        <li>✅ Payments table: {{ \App\Models\Payment::count() }} payments</li>
                    </ul>
                </div>
                
                <div class="bg-green-50 p-6 rounded-lg">
                    <h2 class="text-xl font-semibold text-green-900 mb-4">🚀 Next Steps</h2>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/register" class="text-blue-600 hover:underline">Register new account</a></li>
                        <li><a href="/login" class="text-blue-600 hover:underline">Login</a></li>
                        <li><a href="/cv/builder" class="text-blue-600 hover:underline">CV Builder (requires login)</a></li>
                        <li><a href="/admin" class="text-blue-600 hover:underline">Admin Panel</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="mt-8 text-center">
                <h3 class="text-lg font-semibold mb-4">🔧 Application Status</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div class="bg-gray-100 p-3 rounded">
                        <strong>Laravel:</strong> {{ app()->version() }}
                    </div>
                    <div class="bg-gray-100 p-3 rounded">
                        <strong>PHP:</strong> {{ PHP_VERSION }}
                    </div>
                    <div class="bg-gray-100 p-3 rounded">
                        <strong>Environment:</strong> {{ app()->environment() }}
                    </div>
                    <div class="bg-gray-100 p-3 rounded">
                        <strong>Debug:</strong> {{ config('app.debug') ? 'ON' : 'OFF' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
