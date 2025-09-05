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
            
            // Personal Information - simplified
            'content.personal_info.full_name' => 'required|string|max:100',
            'content.personal_info.email' => 'required|email|max:100',
            'content.personal_info.phone' => 'nullable|string|max:20',
            'content.personal_info.address' => 'nullable|string|max:200',
            
            // Professional Summary - optional
            'content.professional_summary' => 'nullable|string|max:1000',
            
            // Work Experience - optional
            'content.work_experience' => 'nullable|array',
            'content.work_experience.*.job_title' => 'nullable|string|max:100',
            'content.work_experience.*.company' => 'nullable|string|max:100',
            'content.work_experience.*.description' => 'nullable|string|max:500',
            
            // Education - optional
            'content.education' => 'nullable|array',
            'content.education.*.degree' => 'nullable|string|max:100',
            'content.education.*.institution' => 'nullable|string|max:100',
            
            // Skills - optional
            'content.skills' => 'nullable|array',
            'content.skills.*' => 'nullable|string|max:50',
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
 