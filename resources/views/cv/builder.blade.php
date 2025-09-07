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
                    <textarea name="content[professional_summary]" 
                              id="professional_summary" 
                              rows="4" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                              placeholder="Experienced software engineer with 5+ years in web development...">{{ old('content.professional_summary') }}</textarea>
                    <p class="mt-2 text-sm text-gray-500">Maximum 300 characters. Focus on your key skills and experience.</p>
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
                            <label class="block text-sm font-medium text-gray-700">Job Description & Achievements</label>
                            <textarea name="content[work_experience][0][description]" 
                                      rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                      placeholder="• Developed web applications using Laravel and React
• Improved system performance by 40%
• Led a team of 3 developers"></textarea>
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
        <div class="flex justify-end space-x-3">
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
</div>

<script>
let experienceCount = 1;
let educationCount = 1;

function updateTemplate(templateId) {
    // You can add AJAX call here to update form fields based on template
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
            <label class="block text-sm font-medium text-gray-700">Job Description & Achievements</label>
            <textarea name="content[work_experience][${index}][description]" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="• Developed web applications using Laravel and React
• Improved system performance by 40%
• Led a team of 3 developers"></textarea>
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
    const endDateInput = checkbox.closest('.work-experience-item').querySelector('input[type="month"]');
    if (endDateInput) {
        endDateInput.disabled = checkbox.checked;
        if (checkbox.checked) {
            endDateInput.value = '';
        }
    }
}

// Initialize remove buttons
document.addEventListener('DOMContentLoaded', function() {
    updateRemoveButtons('experience');
    updateRemoveButtons('education');
});
</script>
@endsection
