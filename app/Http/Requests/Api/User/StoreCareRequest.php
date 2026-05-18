<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreCareRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'care_type_id' => ['required', 'integer', 'exists:care_types,id'],
            'care_for' => ['required', 'integer', 'in:1,2'], // 1: Self, 2: Other
            'patient_name' => ['required_if:care_for,2', 'nullable', 'string', 'max:255'],
            'patient_age' => ['required_if:care_for,2', 'nullable', 'string', 'max:50'],
            'contact_phone' => ['required', 'string', 'max:15'],
            'secondary_phone' => ['nullable', 'string', 'max:15'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'pincode' => ['required', 'string', 'max:20'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
