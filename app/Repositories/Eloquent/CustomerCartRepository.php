<?php

namespace App\Repositories\Eloquent;

use App\Models\Cart;
use App\Models\CartItem;

class CustomerCartRepository
{
    public function getCartWithDetails(int $userId)
    {
        return Cart::with(['items.Item', 'restaurant'])
            ->where('user_id', $userId)
            ->first();
    }

    public function firstOrCreateCart(int $userId)
    {
        return Cart::firstOrCreate(['user_id' => $userId]);
    }

    public function updateCartRestaurant(int $cartId, ?int $restaurantId)
    {
        return Cart::where('id', $cartId)->update(['restaurant_id' => $restaurantId]);
    }

    public function findItemInCart(int $cartId, int $itemId)
    {
        return CartItem::where('cart_id', $cartId)->where('item_id', $itemId)->first();
    }

    public function addItemToCart(array $data)
    {
        return CartItem::create($data);
    }

    public function updateItemQuantity(int $cartItemId, int $quantity, ?string $notes)
    {
        return CartItem::where('id', $cartItemId)->update([
            'quantity' => $quantity,
            'notes' => $notes
        ]);
    }

    public function removeItem(int $cartItemId)
    {
        return CartItem::where('id', $cartItemId)->delete();
    }

    public function clearCart(int $cartId)
    {
        return CartItem::where('cart_id', $cartId)->delete();
    }
}
