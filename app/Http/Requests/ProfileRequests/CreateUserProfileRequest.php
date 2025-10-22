<?php

namespace App\Http\Requests\ProfileRequests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'profile_name' => 'required|string|max:255|unique:user_profiles,profile_name',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive,locked'
        ];
    }

    public function messages()
    {
        return [
            'profile_name.required' => 'Profile name is required.',
            'profile_name.unique' => 'This profile name is already taken.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be active, inactive, or locked.'
        ];
    }
}
