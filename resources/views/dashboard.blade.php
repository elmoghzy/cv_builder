@extends('layouts.main')

@section('header')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-8">
    <div class="text-center">
        <div class="w-20 h-20 bg-gradient-to-br from-amber-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome back, {{ Auth::user()->name }}!</h1>
        <p class="text-lg text-gray-600">Create professional ATS-compliant CVs for the Egyptian job market</p>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-8">
    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <!-- Create New CV -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:border-amber-300 hover:shadow-md transition-all duration-200 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Create New CV</h3>
                        <p class="text-sm text-gray-500">Build your professional resume</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <a href="{{ route('cv.builder') }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Start Building
                    </a>
                    <div class="grid grid-cols-1 gap-2">
                        <a href="{{ route('test.cv.create') }}" class="w-full inline-flex items-center justify-center px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200 border border-gray-200">
                            🚀 Quick Test CV
                        </a>
                        <a href="{{ route('test.cv.builder') }}" class="w-full inline-flex items-center justify-center px-3 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200 border border-gray-200">
                            🔧 Test CV Builder
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- My CVs -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:border-amber-300 hover:shadow-md transition-all duration-200 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">My CVs</h3>
                        <p class="text-sm text-gray-500">{{ Auth::user()->cvs()->count() }} created</p>
                    </div>
                </div>
                <a href="{{ route('cv.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    View All CVs
                </a>
            </div>
        </div>

        <!-- Account Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:border-amber-300 hover:shadow-md transition-all duration-200 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Account Settings</h3>
                        <p class="text-sm text-gray-500">Manage your profile</p>
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Profile
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
