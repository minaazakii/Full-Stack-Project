<?php

namespace App\Http\Controllers\Api\FrontEnd\Order;

use App\Models\Order;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth('sanctum')->id())->get();

        return response()->json(['orders' => $orders]);
    }
}
