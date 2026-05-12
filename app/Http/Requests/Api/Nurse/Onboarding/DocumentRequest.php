<?php

namespace App\Http\Requests\Api\Nurse\Onboarding;

use App\Models\NurseDocument;
use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $nurseProfile = $this->user()->nurseProfile;

        $uploadedTypes = $nurseProfile
            ? $nurseProfile->documents()->pluck('document_type')->toArray()
            : [];

        $rule = fn(string $type) =>
            in_array($type, $uploadedTypes) ? 'nullable' : 'required';

        return [
            'aadhar_document' => [
                $rule(NurseDocument::TYPE_AADHAR),
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:10240',
            ],

            'nursing_certificate_document' => [
                $rule(NurseDocument::TYPE_NURSING_CERTIFICATE),
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:10240',
            ],

            'pan_document' => [
                $rule(NurseDocument::TYPE_PAN),
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:10240',
            ],
            'marksheet_10_document' => [
                $rule(NurseDocument::TYPE_MARKSHEET_10),
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:10240',
            ],
            'marksheet_12_document' => [
                $rule(NurseDocument::TYPE_MARKSHEET_12),
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:10240',
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'aadhar_document.required' => 'Aadhar document is required.',
            'nursing_certificate_document.required' => 'Nursing certificate is required.',
            'aadhar_document.mimes' => 'Aadhar must be jpg, jpeg, png or pdf.',
            'nursing_certificate_document.mimes' => 'Certificate must be jpg, jpeg, png or pdf.',
            'aadhar_document.max' => 'Aadhar file must not exceed 10MB.',
            'nursing_certificate_document.max' => 'Certificate file must not exceed 10MB.',
        ];
    }
}