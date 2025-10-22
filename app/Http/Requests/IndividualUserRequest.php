<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndividualUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fullname'       => 'required|string|max:255',
            'personal_email' => 'required|email|unique:users,personal_email',
            'phone_number'   => 'required|string|min:8|max:15',
            'pin_code'       => 'required|string|min:4|max:8',
        ];
    }
}
