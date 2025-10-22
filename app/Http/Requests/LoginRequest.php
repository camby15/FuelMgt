<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow all authorized users to use this request
    }

    public function rules()
    {
        return [
            'contact' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (
                        !filter_var($value, FILTER_VALIDATE_EMAIL) &&
                        !preg_match('/^\d{10,15}$/', $value)
                    ) {
                        $fail(
                            'The contact must be a valid email address or phone number.'
                        );
                    }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            'contact.required' => 'Please enter your email or phone number.',
        ];
    }
}
