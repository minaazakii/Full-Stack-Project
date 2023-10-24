<?php

namespace App\Http\Requests\api\frontend\Order\OrderItem;

use App\Helpers\ErrorHelper;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
            'products.*.product.name'=>'required'

        ];
    }
}
