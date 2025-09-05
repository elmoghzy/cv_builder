@extends('layouts.main')

@section('header')
<h1 class="text-3xl font-bold leading-tight tracking-tight text-gray-900">Debug CV Creation</h1>
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">System Status</h2>
        
        <div class="space-y-3">
            <div class="flex items-center">
                <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                    ✅ User Status
                </span>
                <span class="ml-3">
                    @auth
                        Logged in as: {{ auth()->user()->name }} ({{ auth()->user()->email }})
                    @else
                        Not logged in
                    @endauth
                </span>
            </div>

            <div class="flex items-center">
                <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20">
                    📊 Database
                </span>
                <span class="ml-3">
                    Templates: {{ \App\Models\Template::count() }} | 
                    CVs: {{ \App\Models\Cv::count() }} | 
                    Users: {{ \App\Models\User::count() }}
                </span>
            </div>

            <div class="flex items-center">
                <span class="inline-flex items-center rounded-md bg-purple-50 px-2 py-1 text-xs font-medium text-purple-700 ring-1 ring-inset ring-purple-600/20">
                    🔐 Permissions
                </span>
                <span class="ml-3">
                    @auth
                        Can create CV: {{ auth()->user()->can('create', \App\Models\Cv::class) ? 'Yes' : 'No' }}
                    @else
                        Please login first
                    @endauth
                </span>
            </div>
        </div>
    </div>

    @auth
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Simple CV Creation Test</h2>
        
        <form method="POST" action="{{ route('cv.store') }}" class="space-y-4">
            @csrf
            
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-md p-4">
                    <h3 class="text-sm font-medium text-red-800">Validation Errors:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700">Template</label>
                <select name="template_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @foreach(\App\Models\Template::all() as $template)
                        <option value="{{ $template->id }}">{{ $template->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">CV Title</label>
                <input type="text" name="title" value="Test CV {{ now()->format('Y-m-d H:i') }}" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>

            <!-- Minimal required content -->
            <input type="hidden" name="content[personal_info][full_name]" value="{{ auth()->user()->name }}">
            <input type="hidden" name="content[personal_info][email]" value="{{ auth()->user()->email }}">
            <input type="hidden" name="content[personal_info][phone]" value="01234567890">
            <input type="hidden" name="content[professional_summary]" value="Test professional summary">

            <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700">
                Create Test CV
            </button>
        </form>
    </div>
    @else
    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
        <p class="text-yellow-800">Please <a href="{{ route('login') }}" class="underline">login</a> first to create a CV.</p>
    </div>
    @endauth
</div>
@endsection
