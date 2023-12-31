<?php

namespace App\Services;

use App\Models\Order;

class OrderService
{
    public function createOrder($request)
    {
        $order = Order::create(
            [
                'user_id' => auth('sanctum')->id(),
                'name' => $request->name,
                'description' => $request->description,
                'amount' => $request->discount ?? 0,
                'discountType' => $request->discountType ?? 'fixed'
            ]
        );
        return $order;
    }

    public function updateOrder($request, $order)
    {
        $order->update(
            [
                'name' => $request->name,
                'description' => $request->description,
                'amount' => $request->amount ?? 0,
                'discountType' => $request->discountType ?? 'fixed'
            ]
        );
        return $order;
    }
}
