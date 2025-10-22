<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyPinRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'pin' => 'required|string|max:255',
        ];
    }

    /**
     * Customize error messages for validation rules.
     */
    public function messages()
    {
        return [
            'pin.required' => 'The PIN field is required.',
            'pin.string' => 'The PIN must be a string.',
            'pin.max' => 'The PIN may not be greater than 255 characters.',
        ];
    }
}
