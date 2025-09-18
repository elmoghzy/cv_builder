@extends('layouts.main')

@section('header')
<h1 class="text-3xl font-bold leading-tight tracking-tight text-gray-900">Create Your CV</h1>
<p class="mt-2 text-sm text-gray-600">Build your professional ATS-compliant CV step by step</p>
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    
    @if($errors->any())
        <x-alert type="error" title="There were errors with your submission:">
            <ul class="list-disc space-y-1 pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    @if(session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif
    @if(session('error'))
        <x-alert type="error" :message="session('error')" />
    @endif

    <form method="POST" action="{{ route('cv.store') }}" id="cv-form" class="space-y-8">
        @csrf
        
        <!-- Template Selection -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Step 1: Choose Template</h2>
                <p class="mt-1 text-sm text-gray-600">Select an ATS-compliant template for your CV</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($templates as $template)
                    <div class="relative">
                        <input type="radio" 
                               name="template_id" 
                               value="{{ $template->id }}" 
                               id="template_{{ $template->id }}"
                               class="peer sr-only"
                               {{ $template->id == $selectedTemplate->id ? 'checked' : '' }}
                               onchange="updateTemplate({{ $template->id }})">
                        <label for="template_{{ $template->id }}" 
                               class="block cursor-pointer rounded-lg border border-gray-300 p-4 hover:border-indigo-500 peer-checked:border-indigo-600 peer-checked:ring-2 peer-checked:ring-indigo-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">{{ $template->name }}</h3>
                                    @if($template->is_premium)
                                        <span class="mt-1 inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10">Premium</span>
                                    @endif
                                </div>
                                <svg class="h-5 w-5 text-indigo-600 peer-checked:block hidden" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">{{ $template->description }}</p>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- CV Title -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Step 2: CV Details</h2>
            </div>
            <div class="p-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">CV Title</label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title', 'My Professional CV') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="e.g., John Doe - Software Engineer">
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Step 3: Personal Information</h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                        <input type="text" 
                               name="content[personal_info][full_name]" 
                               id="full_name" 
                               value="{{ old('content.personal_info.full_name') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               required>
                        @error('content.personal_info.full_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address *</label>
                        <input type="email" 
                               name="content[personal_info][email]" 
                               id="email" 
                               value="{{ old('content.personal_info.email') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               required>
                        @error('content.personal_info.email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                        <input type="tel" 
                               name="content[personal_info][phone]" 
                               id="phone" 
                               value="{{ old('content.personal_info.phone') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="+20 123 456 7890">
                    </div>
                    
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <input type="text" 
                               name="content[personal_info][address]" 
                               id="address" 
                               value="{{ old('content.personal_info.address') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="Cairo, Egypt">
                    </div>
                    
                    <div>
                        <label for="linkedin" class="block text-sm font-medium text-gray-700">LinkedIn Profile</label>
                        <input type="url" 
                               name="content[personal_info][linkedin]" 
                               id="linkedin" 
                               value="{{ old('content.personal_info.linkedin') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="https://linkedin.com/in/yourprofile">
                    </div>
                    
                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700">Website/Portfolio</label>
                        <input type="url" 
                               name="content[personal_info][website]" 
                               id="website" 
                               value="{{ old('content.personal_info.website') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="https://yourwebsite.com">
                    </div>
                </div>
            </div>
        </div>

        <!-- Professional Summary -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Step 4: Professional Summary</h2>
                <p class="mt-1 text-sm text-gray-600">Write a brief summary of your professional background (2-3 sentences)</p>
            </div>
            <div class="p-6">
                <div>
                    <label for="professional_summary" class="block text-sm font-medium text-gray-700">Professional Summary</label>
                    <div class="relative mt-1">
                        <textarea name="content[professional_summary]" 
                                  id="professional_summary" 
                                  rows="4" 
                                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm pr-12"
                                  placeholder="Experienced software engineer with 5+ years in web development...">{{ old('content.professional_summary') }}</textarea>
                        <button type="button" onclick="enhanceSection(this)" data-target-id="professional_summary" class="absolute top-2.5 right-2.5 p-1.5 text-gray-400 bg-gray-50 rounded-full hover:bg-indigo-100 hover:text-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500" title="Enhance with AI">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.315 2.123a.75.75 0 011.37 0l1.25 3.75a.75.75 0 001.418-.472l-1.25-3.75a2.25 2.25 0 00-4.276 0l-1.25 3.75a.75.75 0 101.418.472l1.25-3.75zM6.5 9.5a.75.75 0 01.75-.75h5a.75.75 0 010 1.5h-5a.75.75 0 01-.75-.75zM5.315 13.373a.75.75 0 011.37 0l1.25 3.75a.75.75 0 001.418-.472l-1.25-3.75a2.25 2.25 0 00-4.276 0l-1.25 3.75a.75.75 0 101.418.472l1.25-3.75z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Write a brief summary, or let our AI enhance it for you!</p>
                </div>
            </div>
        </div>

        <!-- Work Experience -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Step 5: Work Experience</h2>
                <p class="mt-1 text-sm text-gray-600">Add your work experience (start with most recent)</p>
            </div>
            <div class="p-6">
                <div id="work-experience-container">
                    <div class="work-experience-item border rounded-lg p-4 mb-4">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-md font-medium text-gray-900">Experience #1</h4>
                            <button type="button" class="remove-experience text-red-600 hover:text-red-800 text-sm" onclick="removeExperience(this)" style="display: none;">Remove</button>
                        </div>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Job Title *</label>
                                <input type="text" 
                                       name="content[work_experience][0][job_title]" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                       placeholder="Software Engineer">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Company *</label>
                                <input type="text" 
                                       name="content[work_experience][0][company]" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                       placeholder="ABC Company">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Location</label>
                                <input type="text" 
                                       name="content[work_experience][0][location]" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                       placeholder="Cairo, Egypt">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Start Date *</label>
                                <input type="month" 
                                       name="content[work_experience][0][start_date]" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">End Date</label>
                                <input type="month" 
                                       name="content[work_experience][0][end_date]" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="content[work_experience][0][current]" 
                                       value="1"
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                       onchange="toggleEndDate(this)">
                                <label class="ml-2 block text-sm text-gray-900">I currently work here</label>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label for="work_experience_0_description" class="block text-sm font-medium text-gray-700">Job Description & Achievements</label>
                            <div class="relative mt-1">
                                <textarea name="content[work_experience][0][description]" 
                                        id="work_experience_0_description"
                                        rows="3" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm pr-12"
                                        placeholder="• Developed web applications using Laravel and React
• Improved system performance by 40%
• Led a team of 3 developers"></textarea>
                                <button type="button" onclick="enhanceSection(this)" data-target-id="work_experience_0_description" class="absolute top-2.5 right-2.5 p-1.5 text-gray-400 bg-gray-50 rounded-full hover:bg-indigo-100 hover:text-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500" title="Enhance with AI">
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9.315 2.123a.75.75 0 011.37 0l1.25 3.75a.75.75 0 001.418-.472l-1.25-3.75a2.25 2.25 0 00-4.276 0l-1.25 3.75a.75.75 0 101.418.472l1.25-3.75zM6.5 9.5a.75.75 0 01.75-.75h5a.75.75 0 010 1.5h-5a.75.75 0 01-.75-.75zM5.315 13.373a.75.75 0 011.37 0l1.25 3.75a.75.75 0 001.418-.472l-1.25-3.75a2.25 2.25 0 00-4.276 0l-1.25 3.75a.75.75 0 101.418.472l1.25-3.75z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" 
                        onclick="addExperience()" 
                        class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Add Another Experience
                </button>
            </div>
        </div>

        <!-- Education -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Step 6: Education</h2>
            </div>
            <div class="p-6">
                <div id="education-container">
                    <div class="education-item border rounded-lg p-4 mb-4">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-md font-medium text-gray-900">Education #1</h4>
                            <button type="button" class="remove-education text-red-600 hover:text-red-800 text-sm" onclick="removeEducation(this)" style="display: none;">Remove</button>
                        </div>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Degree *</label>
                                <input type="text" 
                                       name="content[education][0][degree]" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                       placeholder="Bachelor of Computer Science">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Institution *</label>
                                <input type="text" 
                                       name="content[education][0][institution]" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                       placeholder="Cairo University">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Location</label>
                                <input type="text" 
                                       name="content[education][0][location]" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                       placeholder="Cairo, Egypt">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Graduation Date</label>
                                <input type="month" 
                                       name="content[education][0][graduation_date]" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">GPA (Optional)</label>
                                <input type="number" 
                                       name="content[education][0][gpa]" 
                                       step="0.01"
                                       min="0"
                                       max="4"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                       placeholder="3.85">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Honors/Awards</label>
                                <input type="text" 
                                       name="content[education][0][honors]" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                       placeholder="Magna Cum Laude">
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" 
                        onclick="addEducation()" 
                        class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Add Another Education
                </button>
            </div>
        </div>

        <!-- Skills -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">Step 7: Skills</h2>
                <p class="mt-1 text-sm text-gray-600">Add your technical and soft skills</p>
            </div>
            <div class="p-6 space-y-6">
                <div>
                    <label for="technical_skills" class="block text-sm font-medium text-gray-700">Technical Skills</label>
                    <input type="text" 
                           name="content[technical_skills]" 
                           id="technical_skills" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="PHP, Laravel, JavaScript, React, MySQL, Git (separate with commas)">
                    <p class="mt-2 text-sm text-gray-500">Separate skills with commas</p>
                </div>
                
                <div>
                    <label for="soft_skills" class="block text-sm font-medium text-gray-700">Soft Skills</label>
                    <input type="text" 
                           name="content[soft_skills]" 
                           id="soft_skills" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="Leadership, Communication, Problem Solving, Team Work">
                </div>
                
                <div>
                    <label for="languages" class="block text-sm font-medium text-gray-700">Languages</label>
                    <input type="text" 
                           name="content[languages]" 
                           id="languages" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="Arabic (Native), English (Fluent), French (Intermediate)">
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex justify-between items-center bg-white shadow-sm rounded-lg p-4 sticky bottom-0">
            <div class="flex items-center gap-3">
                <button type="button" id="ai-autofill" class="inline-flex items-center gap-2 px-3 py-2 text-sm rounded-md bg-emerald-600 text-white hover:bg-emerald-700">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M10 2a1 1 0 01.894.553l1.618 3.236 3.572.519a1 1 0 01.554 1.706l-2.585 2.52.61 3.561a1 1 0 01-1.451 1.054L10 13.347l-3.208 1.687a1 1 0 01-1.451-1.054l.61-3.561L3.366 8.014a1 1 0 01.554-1.706l3.572-.519L9.11 2.553A1 1 0 0110 2z"/></svg>
                    Auto-fill with AI
                </button>
                <span id="ai-autofill-status" class="text-sm text-slate-500"></span>
            </div>
            <button type="button" 
                    onclick="analyzeCv()"
                    id="ai-analysis-btn"
                    class="inline-flex items-center justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                <svg id="ai-analysis-btn-icon" class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 3.5a1.5 1.5 0 011.5 1.5v2.155a2.5 2.5 0 011.056 1.056h2.155a1.5 1.5 0 010 3h-2.155a2.5 2.5 0 01-1.056 1.056v2.155a1.5 1.5 0 01-3 0v-2.155a2.5 2.5 0 01-1.056-1.056H4.5a1.5 1.5 0 010-3h2.155a2.5 2.5 0 011.056-1.056V5A1.5 1.5 0 0110 3.5z" />
                </svg>
                <svg id="ai-analysis-btn-loader" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span id="ai-analysis-btn-text">AI ATS Check & Review</span>
            </button>
            <a href="{{ route('cv.index') }}" 
               class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cancel
            </a>
            <button type="submit" 
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600">
                Create CV & Preview
            </button>
        </div>
    </form>

    <!-- AI Analysis Modal -->
    <div id="ai-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3 border-b">
                <h3 class="text-2xl font-bold text-gray-900">AI CV Analysis Report</h3>
                <button id="ai-modal-close" class="p-1 rounded-full hover:bg-gray-200">
                    <svg class="w-6 h-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div id="ai-modal-content" class="mt-4 max-h-[70vh] overflow-y-auto prose max-w-none pr-4">
                <!-- AI report will be injected here -->
            </div>
        </div>
    </div>
</div>

<script>
let experienceCount = 1;
let educationCount = 1;

// --- AI Feature Functions ---

async function enhanceSection(button) {
    const targetId = button.dataset.targetId;
    const textarea = document.getElementById(targetId);
    const originalText = textarea.value;

    if (!originalText.trim()) {
        alert('Please enter some text before enhancing.');
        return;
    }

    // --- Visual feedback ---
    button.disabled = true;
    const originalIcon = button.innerHTML;
    button.innerHTML = `<svg class="animate-spin h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;

    try {
        const response = await fetch('{{ route("api.ai.enhance-section") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ text: originalText })
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Network response was not ok.');
        }

        const data = await response.json();
        
        if (data.enhanced_text) {
            textarea.value = data.enhanced_text;
        }

    } catch (error) {
        console.error('AI Enhancement Error:', error);
        alert('An error occurred while enhancing the text: ' + error.message);
    } finally {
        // --- Restore button ---
        button.disabled = false;
        button.innerHTML = originalIcon;
    }
}

async function analyzeCv() {
    const form = document.getElementById('cv-form');
    const formData = new FormData(form);
    const cvData = {};

    for (let [key, value] of formData.entries()) {
        const keys = key.match(/[a-zA-Z0-9_]+/g) || [];
        let current = cvData;
        for(let i = 0; i < keys.length; i++) {
            let key = keys[i];
            if (i === keys.length - 1) {
                current[key] = value;
            } else {
                if (!current[key]) {
                    current[key] = /^[0-9]+$/.test(keys[i+1]) ? [] : {};
                }
                current = current[key];
            }
        }
    }

    const modal = document.getElementById('ai-modal');
    const modalContent = document.getElementById('ai-modal-content');
    const analysisBtn = document.getElementById('ai-analysis-btn');
    const btnIcon = document.getElementById('ai-analysis-btn-icon');
    const btnLoader = document.getElementById('ai-analysis-btn-loader');
    const btnText = document.getElementById('ai-analysis-btn-text');

    // Show loading state
    analysisBtn.disabled = true;
    btnIcon.classList.add('hidden');
    btnLoader.classList.remove('hidden');
    btnText.textContent = 'Analyzing...';

    modal.classList.remove('hidden');
    modalContent.innerHTML = '<div class="flex justify-center items-center p-8"><svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><p class="ml-4 text-lg">Analyzing your CV... This may take a moment.</p></div>';

    try {
        const response = await fetch('{{ route("api.ai.analyze-cv") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ cv_data: cvData.content || {} })
        });

        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Network response was not ok.');
        }

        const data = await response.json();
        // A simple markdown-to-HTML conversion
        let html = data.analysis.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>')
                               .replace(/\*([^*]+)\*/g, '<em>$1</em>')
                               .replace(/\n/g, '<br>');
        modalContent.innerHTML = html;

    } catch (error) {
        console.error('AI Analysis Error:', error);
        modalContent.innerHTML = `<div class="p-4 bg-red-50 text-red-700 rounded-lg"><h4 class="font-bold">Analysis Failed</h4><p>${error.message}</p></div>`;
    } finally {
        // Hide loading state
        analysisBtn.disabled = false;
        btnIcon.classList.remove('hidden');
        btnLoader.classList.add('hidden');
        btnText.textContent = 'AI ATS Check & Review';
    }
}

// --- Dynamic Element Functions ---

function updateTemplate(templateId) {
    console.log('Template changed to:', templateId);
}

function addExperience() {
    const container = document.getElementById('work-experience-container');
    const newItem = createExperienceItem(experienceCount);
    container.appendChild(newItem);
    experienceCount++;
    updateRemoveButtons('experience');
}

function removeExperience(button) {
    button.closest('.work-experience-item').remove();
    updateRemoveButtons('experience');
}

function createExperienceItem(index) {
    const div = document.createElement('div');
    div.className = 'work-experience-item border rounded-lg p-4 mb-4';
    const descriptionId = `work_experience_${index}_description`;

    div.innerHTML = `
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-md font-medium text-gray-900">Experience #${index + 1}</h4>
            <button type="button" class="remove-experience text-red-600 hover:text-red-800 text-sm" onclick="removeExperience(this)">Remove</button>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700">Job Title *</label>
                <input type="text" name="content[work_experience][${index}][job_title]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Software Engineer">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Company *</label>
                <input type="text" name="content[work_experience][${index}][company]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="ABC Company">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Location</label>
                <input type="text" name="content[work_experience][${index}][location]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Cairo, Egypt">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Start Date *</label>
                <input type="month" name="content[work_experience][${index}][start_date]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="month" name="content[work_experience][${index}][end_date]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="flex items-center">
                <input type="checkbox" name="content[work_experience][${index}][current]" value="1" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" onchange="toggleEndDate(this)">
                <label class="ml-2 block text-sm text-gray-900">I currently work here</label>
            </div>
        </div>
        <div class="mt-4">
            <label for="${descriptionId}" class="block text-sm font-medium text-gray-700">Job Description & Achievements</label>
            <div class="relative mt-1">
                <textarea name="content[work_experience][${index}][description]" id="${descriptionId}" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm pr-12" placeholder="• Developed web applications using Laravel and React..."></textarea>
                <button type="button" onclick="enhanceSection(this)" data-target-id="${descriptionId}" class="absolute top-2.5 right-2.5 p-1.5 text-gray-400 bg-gray-50 rounded-full hover:bg-indigo-100 hover:text-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500" title="Enhance with AI">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.315 2.123a.75.75 0 011.37 0l1.25 3.75a.75.75 0 001.418-.472l-1.25-3.75a2.25 2.25 0 00-4.276 0l-1.25 3.75a.75.75 0 101.418.472l1.25-3.75zM6.5 9.5a.75.75 0 01.75-.75h5a.75.75 0 010 1.5h-5a.75.75 0 01-.75-.75zM5.315 13.373a.75.75 0 011.37 0l1.25 3.75a.75.75 0 001.418-.472l-1.25-3.75a2.25 2.25 0 00-4.276 0l-1.25 3.75a.75.75 0 101.418.472l1.25-3.75z" clip-rule="evenodd" /></svg>
                </button>
            </div>
        </div>
    `;
    return div;
}

function addEducation() {
    const container = document.getElementById('education-container');
    const newItem = createEducationItem(educationCount);
    container.appendChild(newItem);
    educationCount++;
    updateRemoveButtons('education');
}

function removeEducation(button) {
    button.closest('.education-item').remove();
    updateRemoveButtons('education');
}

function createEducationItem(index) {
    const div = document.createElement('div');
    div.className = 'education-item border rounded-lg p-4 mb-4';
    div.innerHTML = `
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-md font-medium text-gray-900">Education #${index + 1}</h4>
            <button type="button" class="remove-education text-red-600 hover:text-red-800 text-sm" onclick="removeEducation(this)">Remove</button>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium text-gray-700">Degree *</label>
                <input type="text" name="content[education][${index}][degree]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Bachelor of Computer Science">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Institution *</label>
                <input type="text" name="content[education][${index}][institution]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Cairo University">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Location</label>
                <input type="text" name="content[education][${index}][location]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Cairo, Egypt">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Graduation Date</label>
                <input type="month" name="content[education][${index}][graduation_date]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">GPA (Optional)</label>
                <input type="number" name="content[education][${index}][gpa]" step="0.01" min="0" max="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="3.85">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Honors/Awards</label>
                <input type="text" name="content[education][${index}][honors]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Magna Cum Laude">
            </div>
        </div>
    `;
    return div;
}

function updateRemoveButtons(type) {
    const items = document.querySelectorAll(`.${type}-item`);
    items.forEach((item, index) => {
        const removeBtn = item.querySelector(`.remove-${type}`);
        if (removeBtn) {
            removeBtn.style.display = items.length > 1 ? 'inline' : 'none';
        }
    });
}

function toggleEndDate(checkbox) {
    const endDateInput = checkbox.closest('.work-experience-item').querySelector('input[type="month"][name*="end_date"]');
    if (endDateInput) {
        endDateInput.disabled = checkbox.checked;
        if (checkbox.checked) {
            endDateInput.value = '';
        }
    }
}

// --- Event Listeners ---
window.addEventListener('DOMContentLoaded', function() {
    updateRemoveButtons('experience');
    updateRemoveButtons('education');

    const modal = document.getElementById('ai-modal');
    const closeModalBtn = document.getElementById('ai-modal-close');
    
    closeModalBtn.addEventListener('click', () => modal.classList.add('hidden'));
    // Close modal if clicking outside of the content
    modal.addEventListener('click', (e) => {
        if (e.target.id === 'ai-modal') {
            modal.classList.add('hidden');
        }
    });
});
</script>
<style>
/* Simple floating chat widget styles (ATS-safe page unaffected) */
.cv-chatbot { position: fixed; bottom: 20px; right: 20px; z-index: 60; }
.cv-chatbot-panel { width: 340px; max-height: 70vh; background: #fff; box-shadow: 0 10px 25px rgba(0,0,0,.15); border-radius: 12px; overflow: hidden; display: none; }
.cv-chatbot-header { display: flex; align-items: center; justify-content: space-between; padding: 10px 12px; background: #4f46e5; color: #fff; }
.cv-chatbot-body { padding: 12px; height: 48vh; overflow-y: auto; }
.cv-chatbot-input { display: flex; gap: 8px; padding: 10px; border-top: 1px solid #eee; }
.cv-chatbot-bubble { margin: 8px 0; padding: 10px 12px; border-radius: 10px; line-height: 1.4; }
.cv-chatbot-bubble.user { background: #eef2ff; color: #3730a3; align-self: flex-end; }
.cv-chatbot-bubble.bot { background: #f8fafc; color: #0f172a; border: 1px solid #e5e7eb; }
</style>
<div class="cv-chatbot">
    <button id="cv-chatbot-toggle" class="rounded-full bg-indigo-600 text-white shadow-md px-4 py-2 hover:bg-indigo-700">AI Assistant</button>
    <div id="cv-chatbot-panel" class="cv-chatbot-panel">
        <div class="cv-chatbot-header">
            <div class="font-semibold">CV Assistant</div>
            <button id="cv-chatbot-close" class="text-white/80 hover:text-white">×</button>
        </div>
        <div id="cv-chatbot-body" class="cv-chatbot-body flex flex-col"></div>
        <div class="cv-chatbot-input">
            <select id="builder-insert-target" class="border rounded-md px-2 py-2 text-sm">
                <option value="summary">Apply → Summary</option>
                <option value="exp0">Apply → Experience #1</option>
            </select>
            <select id="builder-insert-mode" class="border rounded-md px-2 py-2 text-sm">
                <option value="replace">Replace</option>
                <option value="append">Append</option>
            </select>
            <button id="builder-apply" class="bg-emerald-600 text-white rounded-md px-3 py-2 text-sm">Apply</button>
            <input id="cv-chatbot-message" type="text" placeholder="Ask to write a summary, bullets..." class="flex-1 border rounded-md px-3 py-2 text-sm" />
            <button id="cv-chatbot-send" class="bg-indigo-600 text-white rounded-md px-3 py-2 text-sm">Send</button>
        </div>
    </div>
</div>
<script>
const botToggle = document.getElementById('cv-chatbot-toggle');
const botPanel = document.getElementById('cv-chatbot-panel');
const botClose = document.getElementById('cv-chatbot-close');
const botBody = document.getElementById('cv-chatbot-body');
const botInput = document.getElementById('cv-chatbot-message');
const botSend = document.getElementById('cv-chatbot-send');
const builderApply = document.getElementById('builder-apply');
const builderTarget = document.getElementById('builder-insert-target');
const builderMode = document.getElementById('builder-insert-mode');
let lastBotReply = '';

function getCurrentCvData() {
    const form = document.getElementById('cv-form');
    const formData = new FormData(form);
    const cvData = {};
    for (let [key, value] of formData.entries()) {
        const keys = key.match(/[a-zA-Z0-9_]+/g) || [];
        let current = cvData;
        for (let i = 0; i < keys.length; i++) {
            const k = keys[i];
            if (i === keys.length - 1) {
                current[k] = value;
            } else {
                if (!current[k]) current[k] = /^[0-9]+$/.test(keys[i+1]) ? [] : {};
                current = current[k];
            }
        }
    }
    return cvData.content || {};
}

function appendBubble(text, who = 'bot') {
    const div = document.createElement('div');
    div.className = `cv-chatbot-bubble ${who}`;
    div.innerHTML = text.replace(/\n/g,'<br>');
    botBody.appendChild(div);
    botBody.scrollTop = botBody.scrollHeight;
}

botToggle.addEventListener('click', () => {
    botPanel.style.display = 'block';
    setTimeout(() => botInput.focus(), 50);
});
botClose.addEventListener('click', () => botPanel.style.display = 'none');
botSend.addEventListener('click', sendBotMessage);
botInput.addEventListener('keydown', (e) => { if (e.key === 'Enter') sendBotMessage(); });

async function sendBotMessage() {
    const text = botInput.value.trim();
    if (!text) return;
    appendBubble(text, 'user');
    botInput.value = '';
    const cvData = getCurrentCvData();
    try {
        const res = await fetch('{{ route("api.ai.chat") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: JSON.stringify({ message: text, cv_data: cvData, language: '{{ app()->getLocale() === 'ar' ? 'ar' : (config('ai.language','en')) }}' })
        });
        const data = await res.json();
        lastBotReply = data.reply || '';
        appendBubble(lastBotReply);
    } catch (e) {
        appendBubble('Error contacting assistant.');
    }
}

// Auto-fill with AI: calls generate-cv-content, fills summary, first experience, skills
document.getElementById('ai-autofill').addEventListener('click', async () => {
    const status = document.getElementById('ai-autofill-status');
    status.textContent = 'Generating...';
    try {
        // Infer persona/role from form inputs when available
        const role = document.getElementById('title')?.value || 'Software Engineer';
        const res = await fetch('{{ route("api.ai.generate-cv-content") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: JSON.stringify({ target_role: role, persona: 'professional', language: '{{ app()->getLocale() === 'ar' ? 'ar' : (config('ai.language','en')) }}' })
        });
        const data = await res.json();
        const content = data.content || {};
        // Apply to fields when present
        if (content.professional_summary) {
            const ta = document.getElementById('professional_summary');
            if (ta) ta.value = content.professional_summary;
        }
        if (Array.isArray(content.work_experience) && content.work_experience[0]) {
            const w0 = content.work_experience[0];
            const desc = document.getElementById('work_experience_0_description');
            if (desc && w0.description) desc.value = w0.description;
            const jt = document.querySelector('input[name="content[work_experience][0][job_title]"]');
            if (jt && w0.job_title) jt.value = w0.job_title;
            const co = document.querySelector('input[name="content[work_experience][0][company]"]');
            if (co && w0.company) co.value = w0.company;
            const lo = document.querySelector('input[name="content[work_experience][0][location]"]');
            if (lo && w0.location) lo.value = w0.location;
            const sd = document.querySelector('input[name="content[work_experience][0][start_date]"]');
            if (sd && w0.start_date) sd.value = w0.start_date;
            const ed = document.querySelector('input[name="content[work_experience][0][end_date]"]');
            if (ed && w0.end_date) ed.value = w0.end_date;
        }
        if (content.technical_skills) {
            const ts = document.getElementById('technical_skills');
            if (ts) ts.value = Array.isArray(content.technical_skills) ? content.technical_skills.join(', ') : content.technical_skills;
        }
        if (content.soft_skills) {
            const ss = document.getElementById('soft_skills');
            if (ss) ss.value = Array.isArray(content.soft_skills) ? content.soft_skills.join(', ') : content.soft_skills;
        }
        if (content.languages) {
            const lg = document.getElementById('languages');
            if (lg) lg.value = Array.isArray(content.languages) ? content.languages.join(', ') : content.languages;
        }
        status.textContent = 'Applied ✓';
    } catch (e) {
        status.textContent = 'Failed to generate';
    }
});

builderApply.addEventListener('click', () => {
    if (!lastBotReply) { appendBubble('No AI reply to apply yet.'); return; }
    if (builderTarget.value === 'summary') {
        const ta = document.getElementById('professional_summary');
        if (ta) {
            if (builderMode.value === 'append' && ta.value.trim()) ta.value = ta.value + "\n" + lastBotReply;
            else ta.value = lastBotReply;
            appendBubble('Applied to Summary ✓');
            return;
        }
    }
    if (builderTarget.value === 'exp0') {
        const ta = document.getElementById('work_experience_0_description');
        if (ta) {
            if (builderMode.value === 'append' && ta.value.trim()) ta.value = ta.value + "\n" + lastBotReply;
            else ta.value = lastBotReply;
            appendBubble('Applied to Experience #1 ✓');
            return;
        }
    }
    appendBubble('Target field not found.');
});
</script>
@endsection
