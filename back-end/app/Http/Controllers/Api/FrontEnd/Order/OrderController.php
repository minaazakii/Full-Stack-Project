<?php

namespace App\Http\Controllers\Api\FrontEnd\Order;

use App\Models\Order;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\FrontEnd\Order\OrderResource;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('products')->where('user_id', auth('sanctum')->id())->get();
        return response()->json(['orders' => OrderResource::collection($orders)]);
    }

    public function destroy($id)
    {
        Order::findOrFail($id)->delete();
        return response()->json(['message' => 'Order Deleted Successfully']);
    }
}
