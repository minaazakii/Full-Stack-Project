<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderService
{
    public function createOrder($user_id)
    {
        $order = Order::create(['user_id'=> $user_id]);
        return $order;
    }
}
