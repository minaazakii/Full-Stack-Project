<?php

namespace App\Http\Requests\api\FrontEnd\Category;

use App\Helpers\ErrorHelper;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        ErrorHelper::JsonFormat($validator);
    }
    public function rules(): array
    {
        return [
            'name'=>'required'
        ];
    }
}
