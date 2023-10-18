<?php

namespace App\Http\Requests\Api\FrontEnd\Category;

use App\Helpers\ErrorHelper;
use Illuminate\Foundation\Http\FormRequest;

class DeleteMultiCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
            'categories'=>'required|array'
        ];
    }
}
