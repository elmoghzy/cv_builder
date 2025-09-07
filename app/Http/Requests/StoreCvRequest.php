<?php

namespace App\Http\Requests;

use App\Models\Cv;
use Illuminate\Foundation\Http\FormRequest;

class StoreCvRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Use policy 'create' ability on the Cv model
        return auth()->check() && auth()->user()->can('create', Cv::class);
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
            
            // Accept either nested under content.* or top-level keys used by tests
            // Top-level
            'personal_info' => 'required|array',
            'personal_info.full_name' => 'required|string|max:100',
            'personal_info.email' => 'required|email|max:100',
            'personal_info.phone' => 'nullable|string|max:20',
            'personal_info.address' => 'nullable|string|max:200',

            'professional_summary' => 'nullable|string|max:1000',

            'work_experience' => 'nullable|array',
            'work_experience.*.job_title' => 'nullable|string|max:100',
            'work_experience.*.company' => 'nullable|string|max:100',
            'work_experience.*.description' => 'nullable|string|max:500',

            'education' => 'nullable|array',
            'education.*.degree' => 'nullable|string|max:100',
            'education.*.institution' => 'nullable|string|max:100',

            'technical_skills' => 'nullable|array',
            'technical_skills.*' => 'nullable|string|max:50',

            // Nested under content.* (optional support)
            'content' => 'sometimes|array',
            'content.personal_info.full_name' => 'sometimes|required|string|max:100',
            'content.personal_info.email' => 'sometimes|required|email|max:100',
            'content.personal_info.phone' => 'sometimes|nullable|string|max:20',
            'content.personal_info.address' => 'sometimes|nullable|string|max:200',
            'content.professional_summary' => 'sometimes|nullable|string|max:1000',
            'content.work_experience' => 'sometimes|nullable|array',
            'content.work_experience.*.job_title' => 'sometimes|nullable|string|max:100',
            'content.work_experience.*.company' => 'sometimes|nullable|string|max:100',
            'content.work_experience.*.description' => 'sometimes|nullable|string|max:500',
            'content.education' => 'sometimes|nullable|array',
            'content.education.*.degree' => 'sometimes|nullable|string|max:100',
            'content.education.*.institution' => 'sometimes|nullable|string|max:100',
            'content.skills' => 'sometimes|nullable|array',
            'content.skills.*' => 'sometimes|nullable|string|max:50',
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
}
 