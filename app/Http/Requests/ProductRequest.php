<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        //'image' => 'file|size:512'
        return [
            "name" => "required|string|max: 255|min: 3",
            "type" => "required|string|max: 255",
            "options" => "sometimes",
            "description" => "required|string|max: 255|min: 2",
            "price" => "required",
            "image" => "sometimes",
        ];
    }
}
