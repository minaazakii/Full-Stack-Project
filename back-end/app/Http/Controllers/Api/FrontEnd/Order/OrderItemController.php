<?php

namespace App\Http\Controllers\Api\frontend\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\frontend\Order\OrderItem\StoreOrderItemRequest;
use App\Services\OrderService;

class OrderItemController extends Controller
{
    public function store(StoreOrderItemRequest $request)
    {
        $order = (new OrderService)->createOrder($request);

        foreach($request->products as $product)
        {
            $order->products()->create(['product_id'=>$product->id]);
        }
        return response()->json(['message'=>'Order Created Successfully',201]);

    }
}
