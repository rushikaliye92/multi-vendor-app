<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Vendor;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'vendor', 'payment'])
            ->when($request->vendor_id, fn($q) => $q->where('vendor_id', $request->vendor_id))
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->when($request->status, fn($q) => $q->whereHas('payment', fn($p) => $p->where('status', $request->status)))
            ->latest();

        $orders = $query->paginate(10);
        $vendors = Vendor::all();
        $customers = User::where('role', 'customer')->get();

        return view('admin.orders.index', compact('orders', 'vendors', 'customers'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'vendor', 'user', 'payment']);
        return view('admin.orders.show', compact('order'));
    }
}
