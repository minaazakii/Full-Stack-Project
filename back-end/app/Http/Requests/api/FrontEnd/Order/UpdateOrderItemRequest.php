<?php

namespace App\Http\Requests\api\FrontEnd\Order;

use App\Helpers\ErrorHelper;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderItemRequest extends FormRequest
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
            'name'=>'required',
            'description'=>'required',
            'discountType'=>'nullable',
            'amount'=>'nullable',
            'products' => 'required|array',
            'products.*.pivot'=>'required'
        ];
    }
}
