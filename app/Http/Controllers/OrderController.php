<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function view(Order $order)
    {
        $order->load('items.product', 'vendor', 'user', 'payment');

        return view('orders.show', compact('order'));
    }
}
