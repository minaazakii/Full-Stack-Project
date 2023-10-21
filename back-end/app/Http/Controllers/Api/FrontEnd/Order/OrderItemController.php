<?php

namespace App\Http\Controllers\Api\frontend\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\frontend\Order\OrderItem\StoreOrderItemRequest;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function store(StoreOrderItemRequest $request)
    {
        $order = (new OrderService)->createOrder($request->user_id);

        $order->products()->create(['product_id'=>$request->product_ids,'quantity'=>$request->quantity]);

    }
}
