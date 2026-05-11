<?php

namespace App\Http\Requests\Api\Nurse\Onboarding;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AvailabilityRequest extends FormRequest
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

            'available_from' => [
                'description' => 'Available start time.',
                'example' => '09:00',
            ],

            'available_to' => [
                'description' => 'Available end time.',
                'example' => '18:00',
            ],

            'available_days' => [
                'description' => 'Available working days.',
                'example' => [
                    'monday',
                    'tuesday',
                    'wednesday',
                    'thursday',
                    'friday',
                ],
            ],

            'is_available' => [
                'description' => 'Availability status.',
                'example' => true,
            ],
        ];
    }

    public function rules(): array
    {
        return [

            'available_from' => [
                'required',
                'date_format:H:i',
            ],

            'available_to' => [
                'required',
                'date_format:H:i',
                'after:available_from',
            ],

            'available_days' => [
                'required',
                'array',
                'min:1',
            ],

            'available_days.*' => [
                'required',
                'string',
                Rule::in([
                    'monday',
                    'tuesday',
                    'wednesday',
                    'thursday',
                    'friday',
                    'saturday',
                    'sunday',
                ]),
            ]
        ];
    }
}