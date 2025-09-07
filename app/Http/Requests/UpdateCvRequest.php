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
        
        // التحقق من وجود السيرة الذاتية
        if (!$cv) {
            return false;
        }

        return auth()->check() && 
               auth()->user()->can('update', $cv) && 
               !$cv->is_paid; // منع تعديل السيرة الذاتية المدفوعة
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
            'certifications.*.credential_id' => 'nullable|string|max:100',
        ];
    }

    /**
     * Get custom messages for validation errors
     */
    public function messages(): array
    {
        return [
            // Template validation
            'template_id.required' => 'يرجى اختيار قالب السيرة الذاتية.',
            'template_id.exists' => 'القالب المختار غير صحيح.',
            
            // Title validation
            'title.required' => 'عنوان السيرة الذاتية مطلوب.',
            'title.max' => 'يجب ألا يتجاوز عنوان السيرة الذاتية 100 حرف.',
            
            // Personal info validation
            'personal_info.full_name.required_with' => 'الاسم الكامل مطلوب.',
            'personal_info.full_name.max' => 'يجب ألا يتجاوز الاسم الكامل 100 حرف.',
            'personal_info.email.required_with' => 'البريد الإلكتروني مطلوب.',
            'personal_info.email.email' => 'يرجى إدخال بريد إلكتروني صحيح.',
            'personal_info.email.max' => 'يجب ألا يتجاوز البريد الإلكتروني 100 حرف.',
            'personal_info.phone.max' => 'يجب ألا يتجاوز رقم الهاتف 20 رقم.',
            'personal_info.address.max' => 'يجب ألا يتجاوز العنوان 200 حرف.',
            'personal_info.linkedin.url' => 'يرجى إدخال رابط LinkedIn صحيح.',
            'personal_info.website.url' => 'يرجى إدخال رابط الموقع صحيح.',
            'personal_info.github.url' => 'يرجى إدخال رابط GitHub صحيح.',
            
            // Work experience validation
            'work_experience.*.job_title.required_with' => 'المسمى الوظيفي مطلوب.',
            'work_experience.*.company.required_with' => 'اسم الشركة مطلوب.',
            'work_experience.*.start_date.required_with' => 'تاريخ بداية العمل مطلوب.',
            'work_experience.*.start_date.before_or_equal' => 'تاريخ بداية العمل يجب أن يكون في الماضي أو اليوم.',
            'work_experience.*.end_date.after_or_equal' => 'تاريخ انتهاء العمل يجب أن يكون بعد تاريخ البداية.',
            
            // Education validation
            'education.*.degree.required_with' => 'درجة التعليم مطلوبة.',
            'education.*.institution.required_with' => 'اسم المؤسسة التعليمية مطلوب.',
            'education.*.gpa.numeric' => 'المعدل يجب أن يكون رقم.',
            'education.*.gpa.min' => 'المعدل يجب أن يكون 0 على الأقل.',
            'education.*.gpa.max' => 'المعدل يجب ألا يتجاوز 4.',
            
            // Projects validation
            'projects.*.project_name.required_with' => 'اسم المشروع مطلوب.',
            'projects.*.url.url' => 'يرجى إدخال رابط صحيح للمشروع.',
            
            // Certifications validation
            'certifications.*.name.required_with' => 'اسم الشهادة مطلوب.',
            'certifications.*.date.before_or_equal' => 'تاريخ الحصول على الشهادة يجب أن يكون في الماضي أو اليوم.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $cv = $this->route('cv');
            
            // التحقق من وجود السيرة الذاتية
            if (!$cv) {
                $validator->errors()->add('cv', 'السيرة الذاتية غير موجودة.');
                return;
            }

            // التحقق من حالة الدفع
            if ($cv->is_paid) {
                $validator->errors()->add('cv', 'لا يمكن تعديل سيرة ذاتية مدفوعة. يرجى إنشاء سيرة ذاتية جديدة.');
            }

            // التحقق من صحة تواريخ الخبرات العملية
            if ($this->has('work_experience') && is_array($this->work_experience)) {
                foreach ($this->work_experience as $index => $experience) {
                    if (isset($experience['current']) && $experience['current'] && isset($experience['end_date'])) {
                        $validator->errors()->add(
                            "work_experience.{$index}.end_date", 
                            'لا يمكن تحديد تاريخ انتهاء للوظيفة الحالية.'
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
        // تنظيف البيانات وتحويلها إلى التنسيق المطلوب
        if ($this->has('work_experience')) {
            $workExperience = collect($this->work_experience)->filter(function ($experience) {
                return !empty($experience['job_title']) || !empty($experience['company']);
            })->toArray();
            
            $this->merge(['work_experience' => $workExperience]);
        }

        if ($this->has('education')) {
            $education = collect($this->education)->filter(function ($edu) {
                return !empty($edu['degree']) || !empty($edu['institution']);
            })->toArray();
            
            $this->merge(['education' => $education]);
        }

        if ($this->has('projects')) {
            $projects = collect($this->projects)->filter(function ($project) {
                return !empty($project['project_name']);
            })->toArray();
            
            $this->merge(['projects' => $projects]);
        }

        if ($this->has('certifications')) {
            $certifications = collect($this->certifications)->filter(function ($cert) {
                return !empty($cert['name']);
            })->toArray();
            
            $this->merge(['certifications' => $certifications]);
        }
    }
}