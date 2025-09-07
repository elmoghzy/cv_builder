<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Validation\Validator;

class UpdateCvRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $cv = $this->route('cv');

        if (!$cv) {
            return false;
        }

        $user = $this->user();
        if (!$user) {
            return false;
        }

        // Use Gate::forUser(...) to avoid analyzer complaints about dynamic 'can' method
        if (Gate::forUser($user)->allows('update', $cv)) {
            // do not allow editing paid CVs
            return empty($cv->is_paid) || $cv->is_paid === false;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'template_id' => 'required|exists:templates,id',
            'title' => 'required|string|max:100',

            // Personal Information
            'personal_info' => 'sometimes|array',
            'personal_info.full_name' => 'required_with:personal_info|string|max:100',
            'personal_info.email' => 'required_with:personal_info|email|max:100',
            'personal_info.phone' => 'nullable|string|max:20',
            'personal_info.address' => 'nullable|string|max:200',
            'personal_info.linkedin' => 'nullable|url|max:200',
            'personal_info.website' => 'nullable|url|max:200',
            'personal_info.github' => 'nullable|url|max:200',

            // Summary and Objective
            'professional_summary' => 'nullable|string|max:500',
            'objective' => 'nullable|string|max:400',

            // Work Experience
            'work_experience' => 'nullable|array',
            'work_experience.*.job_title' => 'required_with:work_experience.*|string|max:100',
            'work_experience.*.company' => 'required_with:work_experience.*|string|max:100',
            'work_experience.*.location' => 'nullable|string|max:100',
            'work_experience.*.start_date' => 'required_with:work_experience.*|date|before_or_equal:today',
            'work_experience.*.end_date' => 'nullable|date|after_or_equal:work_experience.*.start_date',
            'work_experience.*.current' => 'nullable|boolean',
            'work_experience.*.description' => 'nullable|string|max:1000',
            'work_experience.*.achievements' => 'nullable|string|max:1000',

            // Education
            'education' => 'nullable|array',
            'education.*.degree' => 'required_with:education.*|string|max:100',
            'education.*.institution' => 'required_with:education.*|string|max:100',
            'education.*.location' => 'nullable|string|max:100',
            'education.*.graduation_date' => 'nullable|date|before_or_equal:today',
            'education.*.gpa' => 'nullable|numeric|min:0|max:4',
            'education.*.honors' => 'nullable|string|max:200',

            // Skills
            'skills' => 'nullable|array',
            'skills.*' => 'string|max:50',
            'technical_skills' => 'nullable|array',
            'technical_skills.*' => 'string|max:50',

            // Projects
            'projects' => 'nullable|array',
            'projects.*.project_name' => 'required_with:projects.*|string|max:100',
            'projects.*.description' => 'nullable|string|max:500',
            'projects.*.technologies' => 'nullable|string|max:200',
            'projects.*.url' => 'nullable|url|max:200',

            // Certifications
            'certifications' => 'nullable|array',
            'certifications.*.name' => 'required_with:certifications.*|string|max:100',
            'certifications.*.issuer' => 'nullable|string|max:100',
            'certifications.*.date' => 'nullable|date|before_or_equal:today',
            'certifications.*.url' => 'nullable|url|max:200',
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array<string,string>
     */
    public function messages(): array
    {
        return [
            // Template validation
            'template_id.required' => 'Please select a CV template.',
            'template_id.exists' => 'The selected template is not valid.',

            // Title validation
            'title.required' => 'The CV title is required.',
            'title.max' => 'The CV title must not exceed 100 characters.',

            // Personal info validation
            'personal_info.full_name.required_with' => 'Full name is required.',
            'personal_info.full_name.max' => 'Full name must not exceed 100 characters.',
            'personal_info.email.required_with' => 'Email is required.',
            'personal_info.email.email' => 'Please enter a valid email address.',
            'personal_info.email.max' => 'Email must not exceed 100 characters.',
            'personal_info.phone.max' => 'Phone number must not exceed 20 characters.',
            'personal_info.address.max' => 'Address must not exceed 200 characters.',
            'personal_info.linkedin.url' => 'Please enter a valid LinkedIn URL.',
            'personal_info.website.url' => 'Please enter a valid website URL.',
            'personal_info.github.url' => 'Please enter a valid GitHub URL.',

            // Work experience validation
            'work_experience.*.job_title.required_with' => 'Job title is required.',
            'work_experience.*.company.required_with' => 'Company name is required.',
            'work_experience.*.start_date.required_with' => 'Start date is required.',
            'work_experience.*.start_date.before_or_equal' => 'Start date must be in the past or today.',
            'work_experience.*.end_date.after_or_equal' => 'End date must be after the start date.',

            // Education validation
            'education.*.degree.required_with' => 'Degree is required.',
            'education.*.institution.required_with' => 'Institution name is required.',
            'education.*.gpa.numeric' => 'GPA must be a number.',
            'education.*.gpa.min' => 'GPA must be at least 0.',
            'education.*.gpa.max' => 'GPA must not exceed 4.',

            // Projects validation
            'projects.*.project_name.required_with' => 'Project name is required.',
            'projects.*.url.url' => 'Please enter a valid project URL.',

            // Certifications validation
            'certifications.*.name.required_with' => 'Certification name is required.',
            'certifications.*.date.before_or_equal' => 'Certification date must be in the past or today.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            $cv = $this->route('cv');

            // ensure CV exists
            if (!$cv) {
                $validator->errors()->add('cv', 'CV not found.');
                return;
            }

            // check paid status
            if (!empty($cv->is_paid) && $cv->is_paid) {
                $validator->errors()->add('cv', 'Paid CVs cannot be modified. Please create a new CV.');
            }

            // validate work experience dates: current + end_date cannot coexist
            if ($this->has('work_experience') && is_array($this->work_experience)) {
                foreach ($this->work_experience as $index => $experience) {
                    if (!empty($experience['current']) && !empty($experience['end_date'])) {
                        $validator->errors()->add(
                            "work_experience.{$index}.end_date",
                            'An end date cannot be set for a current job.'
                        );
                    }
                }
            }
        });
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Clean and normalize array inputs
        if ($this->has('work_experience') && is_array($this->work_experience)) {
            $workExperience = collect($this->work_experience)->filter(function ($experience) {
                return !empty($experience['job_title']) || !empty($experience['company']);
            })->values()->toArray();

            $this->merge(['work_experience' => $workExperience]);
        }

        if ($this->has('education') && is_array($this->education)) {
            $education = collect($this->education)->filter(function ($edu) {
                return !empty($edu['degree']) || !empty($edu['institution']);
            })->values()->toArray();

            $this->merge(['education' => $education]);
        }

        if ($this->has('projects') && is_array($this->projects)) {
            $projects = collect($this->projects)->filter(function ($project) {
                return !empty($project['project_name']);
            })->values()->toArray();

            $this->merge(['projects' => $projects]);
        }

        if ($this->has('certifications') && is_array($this->certifications)) {
            $certifications = collect($this->certifications)->filter(function ($cert) {
                return !empty($cert['name']);
            })->values()->toArray();

            $this->merge(['certifications' => $certifications]);
        }
    }
}