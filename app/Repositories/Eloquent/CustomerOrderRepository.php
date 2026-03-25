<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;

class CustomerOrderRepository
{
    public function getUserCart(int $userId)
    {
        return Cart::with(['items.Item', 'restaurant'])
            ->where('user_id', $userId)
            ->first();
    }

    public function findCouponWithLock(string $code)
    {
        return Coupon::where('code', $code)->lockForUpdate()->first();
    }

    public function createOrder(array $data)
    {
        return Order::create($data);
    }

    public function insertOrderItems(array $items)
    {
        return OrderItem::insert($items);
    }

    public function decrementCouponUsage(Coupon $coupon)
    {
        if ($coupon->usage_limit !== null) {
            $coupon->decrement('usage_limit');
        }
    }

    public function clearUserCart(int $userId)
    {
        $cart = Cart::where('user_id', $userId)->first();
        if ($cart) {
            CartItem::where('cart_id', $cart->id)->delete();
            $cart->update(['restaurant_id' => null]);
        }
    }

    public function getUserOrders(int $userId, int $perPage = 15)
    {
        return Order::with(['restaurant:id,name,logo', 'address'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->cursorPaginate($perPage);
    }

    public function getUserOrderById(int $userId, int $orderId)
    {
        $Orders=Order::with(['restaurant:id,name,logo', 'items.Item:id,name,image', 'address'])
            ->where('user_id', $userId)
            ->where('id', $orderId)
            ->firstOrFail();
        return $Orders;
    }
}
