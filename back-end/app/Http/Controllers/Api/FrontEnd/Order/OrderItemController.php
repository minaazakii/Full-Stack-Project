<?php

namespace App\Http\Controllers\Api\frontend\Order;

use App\Models\Order;
use App\Services\OrderService;
use App\Http\Controllers\Controller;
use App\Http\Requests\api\FrontEnd\Order\UpdateOrderItemRequest;
use App\Http\Requests\api\frontend\Order\OrderItem\StoreOrderItemRequest;


class OrderItemController extends Controller
{
    public function store(StoreOrderItemRequest $request)
    {
        $order = (new OrderService)->createOrder($request);

        foreach ($request->products as $rec) {
            $order->products()->attach($rec['product']['id'], ['quantity' => $rec['quantity']]);
        }
        return response()->json(['message' => 'Order Created Successfully'], 201);
    }

    public function update($id, UpdateOrderItemRequest $request)
    {
        $order = Order::findOrFail($id);
        $updatedOrder = (new OrderService)->updateOrder($request, $order);
        $addedProducts = [];
        foreach ($request->products as $product) {

            $addedProducts[$product['id']] = ['quantity' => $product['pivot']['quantity']];
        }
        $order->products()->sync($addedProducts);
        return response()->json(['message' => 'Order Updated Successfully'], 201);
    }
}
