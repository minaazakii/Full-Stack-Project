<?php

namespace App\Http\Controllers\Api\frontend\Order;

use App\Services\OrderService;
use App\Http\Controllers\Controller;
use App\Http\Requests\api\frontend\Order\OrderItem\StoreOrderItemRequest;


class OrderItemController extends Controller
{
    public function store(StoreOrderItemRequest $request)
    {
        $order = (new OrderService)->createOrder($request);

        foreach($request->products as $rec)
        {
            $order->products()->attach($rec['product']['id'],['quantity'=>$rec['quantity']]);
        }
        return response()->json(['message'=>'Order Created Successfully',201]);

    }
}
