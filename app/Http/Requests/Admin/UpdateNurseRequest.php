<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateNurseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // $this->user points to the User model from Route model binding -> {user}
        $routeParam = $this->route('user');
        $userId = $routeParam instanceof \App\Models\User ? $routeParam->id : $routeParam;

        return [
            // User Table fields
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'phone' => ['nullable', 'string', 'max:20'],
            'profile_photo' => ['nullable', 'image', 'max:2048'],

            // NurseProfile Table fields
            'is_available' => ['required', 'boolean'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            
            // Location
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'pincode' => ['required', 'string', 'max:20'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],

            // Availability
            'available_from' => ['nullable', 'date_format:H:i'],
            'available_to' => ['nullable', 'date_format:H:i', 'after:available_from'],
            'available_days' => ['nullable', 'array'],
            'available_days.*' => ['integer', Rule::in([0, 1, 2, 3, 4, 5, 6])],

            // Care Types Relation
            'care_types' => ['nullable', 'array'],
            'care_types.*' => ['integer', Rule::exists('care_types', 'id')],
        ];
    }
}
