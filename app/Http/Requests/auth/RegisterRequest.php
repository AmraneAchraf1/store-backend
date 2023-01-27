<?php

namespace App\Http\Requests\auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * [{"key":"first_name","value":"","description":"","type":"text","enabled":true},{"key":"last_name","value":"","description":"","type":"text","enabled":true},{"key":"email","value":"","description":"","type":"text","enabled":true},{"key":"phone","value":"","description":"","type":"text","enabled":true},{"key":"password","value":"","description":"","type":"text","enabled":true}]
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "first_name"=> "required|string|min:2",
            "last_name"=> "required|string|min:2",
            "email"=> "required|unique:admins|unique:users|string|max:255",
            "phone"=> "sometimes|unique:users|string|max:20",
            "password"=> "required|string|max:20|min:8",
        ];
    }
}
