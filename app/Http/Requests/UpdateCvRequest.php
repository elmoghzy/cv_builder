<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Cv;

class UpdateCvRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $cv = $this->route('cv');
        return auth()->check() && 
               auth()->user()->can('update', $cv) && 
               !$cv->is_paid; // Prevent editing paid CVs
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
            'content' => 'required|array',
            
            // Personal Information
            'content.personal_info' => 'required|array',
            'content.personal_info.full_name' => 'required|string|max:100',
            'content.personal_info.email' => 'required|email|max:100',
            'content.personal_info.phone' => 'nullable|string|max:20',
            'content.personal_info.address' => 'nullable|string|max:200',
            'content.personal_info.linkedin' => 'nullable|url|max:200',
            'content.personal_info.website' => 'nullable|url|max:200',
            'content.personal_info.github' => 'nullable|url|max:200',
            
            // Professional Summary/Objective
            'content.professional_summary' => 'nullable|string|max:500',
            'content.objective' => 'nullable|string|max:400',
            
            // Work Experience
            'content.work_experience' => 'nullable|array',
            'content.work_experience.*.job_title' => 'required_with:content.work_experience|string|max:100',
            'content.work_experience.*.company' => 'required_with:content.work_experience|string|max:100',
            'content.work_experience.*.location' => 'nullable|string|max:100',
            'content.work_experience.*.start_date' => 'required_with:content.work_experience|date',
            'content.work_experience.*.end_date' => 'nullable|date|after:content.work_experience.*.start_date',
            'content.work_experience.*.current' => 'nullable|boolean',
            'content.work_experience.*.description' => 'nullable|string|max:1000',
            'content.work_experience.*.achievements' => 'nullable|string|max:1000',
            
            // Education
            'content.education' => 'nullable|array',
            'content.education.*.degree' => 'required_with:content.education|string|max:100',
            'content.education.*.institution' => 'required_with:content.education|string|max:100',
            'content.education.*.location' => 'nullable|string|max:100',
            'content.education.*.graduation_date' => 'nullable|date',
            'content.education.*.gpa' => 'nullable|numeric|min:0|max:4',
            'content.education.*.honors' => 'nullable|string|max:200',
            
            // Skills
            'content.skills' => 'nullable|array',
            'content.technical_skills' => 'nullable|array',
            
            // Projects
            'content.projects' => 'nullable|array',
            'content.projects.*.project_name' => 'required_with:content.projects|string|max:100',
            'content.projects.*.description' => 'nullable|string|max:500',
            'content.projects.*.technologies' => 'nullable|string|max:200',
            'content.projects.*.url' => 'nullable|url|max:200',
            
            // Certifications
            'content.certifications' => 'nullable|array',
            'content.certifications.*.name' => 'required_with:content.certifications|string|max:100',
            'content.certifications.*.issuer' => 'nullable|string|max:100',
            'content.certifications.*.date' => 'nullable|date',
            'content.certifications.*.credential_id' => 'nullable|string|max:100',
        ];
    }

    /**
     * Get custom messages for validation errors
     */
    public function messages(): array
    {
        return [
            'template_id.required' => 'Please select a CV template.',
            'template_id.exists' => 'The selected template is not valid.',
            'title.required' => 'CV title is required.',
            'content.personal_info.full_name.required' => 'Full name is required.',
            'content.personal_info.email.required' => 'Email address is required.',
            'content.personal_info.email.email' => 'Please enter a valid email address.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $cv = $this->route('cv');
            if ($cv && $cv->is_paid) {
                $validator->errors()->add('cv', 'Cannot edit a paid CV. Please create a new one.');
            }
        });
    }
}
