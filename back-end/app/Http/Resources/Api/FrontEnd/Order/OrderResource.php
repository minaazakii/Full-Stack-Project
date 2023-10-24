<?php

namespace App\Http\Resources\Api\FrontEnd\Order;

use App\Http\Resources\Api\FrontEnd\Product\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id'=>$this->id,
            'name'=>$this->name,
            'description'=>$this->description,
            'status'=>$this->status,
            'amount'=>$this->amount,
            'discountType'=>$this->discountType,
            'products'=>ProductResource::collection($this->products),
            'created_at'=>$this->created_at->format('l F j, Y')
        ];
    }
}
