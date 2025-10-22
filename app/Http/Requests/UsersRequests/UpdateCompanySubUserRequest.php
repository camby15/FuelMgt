<?php

namespace App\Http\Requests\UsersRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateCompanySubUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // We'll handle authorization in the controller
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fullname' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('company_sub_users')->ignore($this->route('company_sub_user')),
            ],
            'phone_number'  => ['sometimes', 'required', 'string', 'max:20'],
            'password'      => ['nullable', Password::defaults()],
            'pin_code'      => ['sometimes', 'required', 'string', 'min:4', 'max:6'],
            'role'          => ['sometimes', 'required', 'string', 'in:admin,manager,user'],
            'status'        => ['sometimes', 'required', 'string', 'in:active,inactive,locked'],
            'company_id'    => ['sometimes', 'required', 'exists:company_profiles,id'],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'fullname.required'     => 'The full name field is required.',
            'email.required'        => 'The email field is required.',
            'email.email'           => 'Please enter a valid email address.',
            'email.unique'          => 'This email is already registered.',
            'phone_number.required' => 'The phone number field is required.',
            'pin_code.required'     => 'The PIN code field is required.',
            'pin_code.min'          => 'The PIN code must be at least 4 characters.',
            'pin_code.max'          => 'The PIN code cannot be longer than 6 characters.',
            'role.required'         => 'The role field is required.',
            'role.in'               => 'Please select a valid role.',
            'status.required'       => 'The status field is required.',
            'status.in'             => 'Please select a valid status.',
            'company_id.required'   => 'The company field is required.',
            'company_id.exists'     => 'The selected company is invalid.',
            'profile_image.image'   => 'The file must be an image.',
            'profile_image.mimes'   => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'profile_image.max'     => 'The image may not be greater than 2MB.',
        ];
    }
}
