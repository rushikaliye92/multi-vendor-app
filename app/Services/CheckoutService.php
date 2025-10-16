<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Events\OrderPlaced;
use App\Events\PaymentSucceeded;
use Illuminate\Support\Facades\DB;

class CheckoutService
{
    public function checkout($userId)
    {
        $cart = Cart::with('items.product.vendor')->where('user_id', $userId)->firstOrFail();

        $vendorGroups = $cart->items->groupBy(fn($item) => $item->product->vendor->id);

        DB::transaction(function () use ($vendorGroups, $userId, $cart) {
            foreach ($vendorGroups as $vendorId => $items) {
                $total = 0;
                $order = Order::create([
                    'user_id' => $userId,
                    'vendor_id' => $vendorId,
                    'total' => 0
                ]);

                foreach ($items as $item) {
                    if ($item->quantity > $item->product->stock) {
                        throw new \Exception("Product {$item->product->name} out of stock.");
                    }

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product->id,
                        'quantity' => $item->quantity,
                        'price' => $item->product->price,
                    ]);

                    $item->product->decrement('stock', $item->quantity);
                    $total += $item->quantity * $item->product->price;
                }

                $order->update(['total' => $total]);

                $payment = Payment::create([
                    'order_id' => $order->id,
                    'status' => 'paid'
                ]);

                event(new OrderPlaced($order));
                event(new PaymentSucceeded($payment));
            }

            $cart->items()->delete();
        });
    }
}
