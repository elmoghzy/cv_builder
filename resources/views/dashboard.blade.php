@extends('layouts.main')

@section('header')

<div class="container text-center pb-8 border-b border-gray-200 mt-4 mb-10">
    <h1 class="text-5xl font-extrabold text-sky-600 drop-shadow-lg mb-2">Welcome, {{ Auth::user()->name }}!</h1>
    <p class="mt-3 text-lg text-gray-700 font-medium mb-16">Create professional ATS-compliant CVs for the Egyptian job market</p>
</div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
    <!-- Quick Actions -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Create New CV -->
        <div class="bg-white border border-gray-200 rounded-lg shadow hover:shadow-lg transition-shadow duration-200 p-6">
            <div class="flex items-center space-x-4">
                <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Create New CV</h3>
                    <p class="text-sm text-gray-500">Build your professional resume</p>
                </div>
            </div>
            <div class="mt-4 space-y-2">
                <a href="{{ route('cv.builder') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200 w-full justify-center">
                    Start Building
                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                <a href="{{ route('test.cv.create') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200 w-full justify-center">
                    🚀 Quick Test CV
                </a>
                <a href="{{ route('test.cv.builder') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200 w-full justify-center">
                    🔧 Test CV Builder
                </a>
            </div>
        </div>
        <!-- My CVs -->
        <div class="bg-white border border-gray-200 rounded-lg shadow hover:shadow-lg transition-shadow duration-200 p-6">
            <div class="flex items-center space-x-4">
                <div class="bg-green-100 text-green-600 p-3 rounded-full">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">My CVs</h3>
                    <p class="text-sm text-gray-500">{{ Auth::user()->cvs()->count() }} created</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('cv.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition-colors duration-200">
                    View All
                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
        <!-- Account Settings -->
        <div class="bg-white border border-gray-200 rounded-lg shadow hover:shadow-lg transition-shadow duration-200 p-6">
            <div class="flex items-center space-x-4">
                <div class="bg-purple-100 text-purple-600 p-3 rounded-full">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v7" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Account Settings</h3>
                    <p class="text-sm text-gray-500">Manage your profile</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 transition-colors duration-200">
                    Edit Profile
                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent CVs -->
    @if(Auth::user()->cvs()->exists())
    <div class="bg-white border border-gray-200 rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Recent CVs</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach(Auth::user()->cvs()->latest()->take(6)->get() as $cv)
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 hover:bg-gray-100 transition-colors duration-150">
                <div class="flex justify-between items-start">
                    <div class="space-y-1">
                        <h3 class="text-sm font-medium text-gray-900 truncate">{{ $cv->personal_info['full_name'] ?? 'Untitled' }}</h3>
                        <p class="text-xs text-gray-500 truncate">{{ $cv->template->name ?? 'No Template' }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('cv.edit', $cv) }}" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>
                        <a href="{{ route('cv.preview', $cv) }}" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                    </div>
                </div>
                <p class="mt-2 text-xs text-gray-400">{{ $cv->created_at->diffForHumans() }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-white border border-gray-200 rounded-lg shadow p-2  text-center max-w-md mx-auto">
        <svg class="mx-auto h-7 w-7 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="text-lg font-medium text-gray-900">No CVs Yet</h3>
        <p class="mt-1 text-sm text-gray-500">Get started by creating your first CV.</p>
        <a href="{{ route('cv.builder') }}" class="mt-4 inline-flex items-center px-5 py-2 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 transition-colors duration-200">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Create Your First CV
        </a>
    </div>
@endif
</div>
@endsection
