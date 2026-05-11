<?php

namespace App\Http\Requests\Api\Nurse\Onboarding;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Body parameters.
     */
    public function bodyParameters(): array
    {
        return [

            'aadhar_document' => [
                'description' => 'Aadhar document.',
                'example' => null,
            ],

            'pan_document' => [
                'description' => 'PAN document.',
                'example' => null,
            ],

            'marksheet_10_document' => [
                'description' => '10th marksheet.',
                'example' => null,
            ],

            'marksheet_12_document' => [
                'description' => '12th marksheet.',
                'example' => null,
            ],

            'nursing_certificate_document' => [
                'description' => 'Nursing certificate.',
                'example' => null,
            ],

            'other_document' => [
                'description' => 'Other supporting document.',
                'example' => null,
            ],
        ];
    }

    public function rules(): array
    {
        return [

            'aadhar_document' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:10240',
            ],

            'pan_document' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:10240',
            ],

            'marksheet_10_document' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:10240',
            ],

            'marksheet_12_document' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:10240',
            ],

            'nursing_certificate_document' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:10240',
            ],

            'other_document' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:10240',
            ],
        ];
    }
}