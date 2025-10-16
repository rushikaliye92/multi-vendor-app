<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Notifications\NotifyVendor;
use App\Models\{Cart, CartItem, Order, OrderItem, Payment, Product, Vendor};
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function view()
    {
        $cart = Cart::with('items.product.vendor')
            ->where('user_id', Auth::id())
            ->first();

        $grouped = $cart?->items->groupBy(fn($item) => $item->product->vendor->name) ?? collect();
        return view('cart.view', compact('grouped'));
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        // Check stock
        if ($request->quantity > $product->stock) {
            return back()->with('error', 'Quantity exceeds available stock.');
        }

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $newQty = $cartItem->quantity + $request->quantity;
            if ($newQty > $product->stock) {
                return back()->with('error', 'Total quantity exceeds available stock.');
            }
            $cartItem->update(['quantity' => $newQty]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);
        }

        return redirect()->route('cart.view')->with('success', 'Product added to cart.');
    }

    public function update(Request $request, CartItem $item)
    {
        $quantity = (int) $request->quantity;
        if ($quantity > $item->product->stock) {
            return back()->with('error', "Cannot exceed available stock for {$item->product->name}");
        }
        $item->update(['quantity' => $quantity]);
        return back()->with('success', "Quantity updated");
    }

    public function remove(CartItem $item)
    {
        $item->delete();
        return back()->with('success', "Item removed from cart");
    }

    public function checkout()
    {
        try {
            Log::info('Checkout started for user: ' . Auth::id());

            $cart = Cart::with('items.product.vendor')
                ->where('user_id', Auth::id())
                ->first();

            if (!$cart || $cart->items->isEmpty()) {
                Log::warning('Checkout failed: empty cart for user ' . Auth::id());
                return back()->with('error', 'Your cart is empty.');
            }

            $grouped = $cart->items->groupBy(fn($item) => $item->product->vendor->id);
            $orderIds = [];

            foreach ($grouped as $vendorId => $items) {
                $total = $items->sum(fn($item) => $item->quantity * $item->product->price);

                $order = Order::create([
                    'user_id' => Auth::id(),
                    'vendor_id' => $vendorId,
                    'total' => $total,
                    'status' => 'pending',
                ]);
                $orderIds[] = $order->id;

                Log::info("Order created (ID: {$order->id}) for Vendor ID: {$vendorId}");

                foreach ($items as $item) {
                    $product = $item->product;

                    if ($item->quantity > $product->stock) {
                        Log::error("Insufficient stock for product {$product->name}");
                        return back()->with('error', "Product {$product->name} does not have enough stock.");
                    }

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item->quantity,
                        'price' => $product->price,
                    ]);

                    $product->decrement('stock', $item->quantity);
                }

                Payment::create([
                    'order_id' => $order->id,
                    'status' => 'paid',
                ]);

                $vendor = $items->first()->product->vendor;
                if ($vendor && $vendor->user) {
                    $vendor->user->notify(new NotifyVendor($order));
                    Log::info("Notification sent to Vendor ID: {$vendor->id}");
                } else {
                    Log::warning("No vendor user found for Vendor ID: {$vendorId}");
                }
            }

            $cart->items()->delete();

            Log::info("Checkout completed successfully for user " . Auth::id());

            return redirect()
                ->route('orders.view', ['order' => $orderIds[0]])
                ->with('success', 'Checkout completed successfully!');

        } catch (\Exception $e) {
            Log::error('Checkout failed: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong during checkout.');
        }
    }
}
