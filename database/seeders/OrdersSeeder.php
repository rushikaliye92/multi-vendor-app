<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;

class OrdersSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::where('role', 'customer')->first();
        $vendors = Vendor::all();

        foreach ($vendors as $vendor) {
            $order = Order::create([
                'user_id' => $customer->id,
                'vendor_id' => $vendor->id,
                'total' => 0
            ]);

            $products = Product::where('vendor_id', $vendor->id)->take(2)->get();
            $total = 0;

            foreach ($products as $product) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'price' => $product->price
                ]);
                $total += $product->price;
            }

            $order->update(['total' => $total]);

            Payment::create([
                'order_id' => $order->id,
                'status' => 'paid'
            ]);
        }
    }
}
