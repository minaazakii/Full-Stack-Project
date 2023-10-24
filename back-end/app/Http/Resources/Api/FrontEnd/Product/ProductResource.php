<?php

namespace App\Http\Resources\Api\FrontEnd\Product;

use App\Http\Resources\Api\FrontEnd\Category\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'category'=>new CategoryResource($this->category),
            'status'=>$this->status,
            'description'=>$this->description,
            'ratings'=>$this->ratings,
            'price'=>$this->price,
            'quantity'=>$this->quantity,
            'created_at'=>$this->created_at->format('l F j, Y'),
            'pivot'=>$this->pivot?? null
        ];
    }
}
