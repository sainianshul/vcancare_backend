<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCareRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'notes' => ['nullable', 'string', 'max:500'],
            'secondary_phone' => ['nullable', 'string', 'max:20'],
            // These core fields can be present, but might be ignored by service based on status
            'care_type_id' => ['sometimes', 'exists:care_types,id'],
            'care_for' => ['sometimes', 'integer', 'in:1,2'],
            'patient_name' => ['nullable', 'string', 'required_if:care_for,2', 'max:100'],
            'patient_age' => ['nullable', 'string', 'required_if:care_for,2', 'max:10'],
            'contact_phone' => ['sometimes', 'string', 'max:20'],
            'latitude' => ['sometimes', 'numeric'],
            'longitude' => ['sometimes', 'numeric'],
            'address' => ['sometimes', 'string', 'max:500'],
            'city' => ['sometimes', 'string', 'max:100'],
            'state' => ['sometimes', 'string', 'max:100'],
            'country' => ['sometimes', 'string', 'max:100'],
            'pincode' => ['sometimes', 'string', 'max:20'],
            'start_date' => ['sometimes', 'date', 'after_or_equal:today'],
            'end_date' => ['sometimes', 'date', 'after_or_equal:start_date'],
            'start_time' => ['sometimes', 'date_format:H:i'],
            'end_time' => ['sometimes', 'date_format:H:i'],
        ];
    }
}
