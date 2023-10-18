<?php

namespace App\Http\Requests\Api\FrontEnd\Auth;

use App\Helpers\ErrorHelper;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        ErrorHelper::JsonFormat($validator);
    }
    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|unique:users,email|email',
            'password' => 'required'
        ];
    }
}
