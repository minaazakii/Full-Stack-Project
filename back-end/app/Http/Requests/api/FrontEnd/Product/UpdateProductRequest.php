<?php

namespace App\Http\Requests\api\FrontEnd\Product;

use App\Helpers\ErrorHelper;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'category_id' => 'required|exists:categories,id',
            'description' => 'required',
            'status' => 'required|in:1,2,3',
            'price' => 'required|numeric',
            'quantity' => 'nullable',
            'ratings'=>'nullable'
        ];
    }
}
