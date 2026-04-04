<?php

namespace App\Repositories\Eloquent;

use App\Models\Cart;
use App\Models\CartItem;

class CustomerCartRepository
{
    public function getCartWithDetails(int $userId)
    {
        // Eager Loading جبار يجلب الوجبة والإضافات والمطعم بـ 3 استعلامات فقط
        return Cart::with(['items.Item', 'items.extras', 'restaurant'])
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

    public function findIdenticalItemInCart(int $cartId, int $itemId, array $extrasIds = [])
    {
        $items = CartItem::with('extras')
            ->where('cart_id', $cartId)
            ->where('item_id', $itemId)
            ->get();

        sort($extrasIds);

        foreach ($items as $item) {
            $itemExtras = $item->extras->pluck('id')->toArray();
            sort($itemExtras);

            if ($itemExtras === $extrasIds) {
                return $item; // تطابق تام في الوجبة والإضافات
            }
        }

        return null; // وجبة بتركيبة إضافات جديدة
    }

    public function addItemToCart(array $data)
    {
        return CartItem::create($data);
    }

    public function syncItemExtras(int $cartItemId, array $extrasIds)
    {
        $cartItem = CartItem::find($cartItemId);
        if ($cartItem) {
            $cartItem->extras()->sync($extrasIds);
        }
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

    public function getCartItemById(int $cartItemId)
    {
        return CartItem::find($cartItemId);
    }
}
