<?php

namespace App\Http\Requests\Api\Nurse\Onboarding;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BasicProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * Validation rules.
     */

    public function rules()
    {
        return [

            'profile_photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($this->user()->id),
            ],

            'bio' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'years_of_experience' => [
                'required',
                'integer',
                'min:0',
                'max:60',
            ],

            'license_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique(
                    'nurse_profiles',
                    'license_number'
                )->ignore(
                        $this->user()
                            ->nurseProfile
                            ->id
                    ),
            ],

            'license_expiry_date' => [
                'required',
                'date',
            ],

            'latitude' => [
                'required',
                'numeric',
            ],

            'longitude' => [
                'required',
                'numeric',
            ],

            'address' => [
                'required',
                'string',
                'max:1000',
            ],

            'city' => [
                'required',
                'string',
                'max:255',
            ],

            'state' => [
                'required',
                'string',
                'max:255',
            ],

            'country' => [
                'required',
                'string',
                'max:255',
            ],

            'pincode' => [
                'required',
                'string',
                'max:20',
            ],
        ];
    }
    /**
     * Body parameters.
     */
    public function bodyParameters()
    {
        return [

            'profile_photo' => ['description' => 'Nurse profile photo.', 'example' => null],

            'email' => ['description' => 'User email address.', 'example' => 'nurse@example.com'],

            'bio' => ['description' => 'Short professional bio.', 'example' => 'Experienced ICU nurse with 5 years of experience.'],

            'years_of_experience' => ['description' => 'Total years of nursing experience.', 'example' => 5],

            'license_number' => ['description' => 'Nursing license number.', 'example' => 'RN-458796'],

            'license_expiry_date' => ['description' => 'License expiry date.', 'example' => '2028-12-31'],

            'latitude' => ['description' => 'Current latitude.', 'example' => 28.7041],

            'longitude' => ['description' => 'Current longitude.', 'example' => 77.1025],

            'address' => ['description' => 'Complete address.', 'example' => 'Sector 21, Chandigarh'],

            'city' => ['description' => 'City name.', 'example' => 'Chandigarh'],

            'state' => ['description' => 'State name.', 'example' => 'Punjab'],

            'country' => ['description' => 'Country name.', 'example' => 'India'],

            'pincode' => ['description' => 'Area postal code.', 'example' => '160021'],
        ];
    }
}