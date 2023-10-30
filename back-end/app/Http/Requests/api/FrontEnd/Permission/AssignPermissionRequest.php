<?php

namespace App\Http\Requests\api\FrontEnd\Permission;

use App\Helpers\ErrorHelper;
use Illuminate\Foundation\Http\FormRequest;

class AssignPermissionRequest extends FormRequest
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
            'permissions' =>'required|array',
            'permissions.*'=>'exists:permissions,id'
        ];
    }
}
