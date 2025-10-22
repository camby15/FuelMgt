<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyUserRequest extends FormRequest
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
            'company_name'  => 'required|string|max:255',
            'company_email' =>'required|email|unique:company_profiles,company_email',
            'company_phone' => 'required|string|min:8|max:15',
            'primary_email' => 'required|email',
            'pin_code'      => 'required|string|min:4|max:8',
        ];
    }
}
