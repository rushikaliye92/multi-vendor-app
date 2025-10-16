<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;

class CartService
{
    public function addProduct($userId, Product $product, $quantity)
    {
        if ($quantity > $product->stock) {
            throw new \Exception("Quantity exceeds stock.");
        }

        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        $item = $cart->items()->firstOrCreate(
            ['product_id' => $product->id],
            ['quantity' => 0]
        );

        $item->quantity += $quantity;

        if ($item->quantity > $product->stock) {
            throw new \Exception("Quantity exceeds stock.");
        }

        $item->save();

        return $cart;
    }

    public function getCart($userId)
    {
        return Cart::with('items.product.vendor')->where('user_id', $userId)->first();
    }

    public function clearCart($userId)
    {
        $cart = Cart::where('user_id', $userId)->first();
        if ($cart) {
            $cart->items()->delete();
        }
    }
}
