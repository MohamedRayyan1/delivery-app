<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use Illuminate\Support\Facades\DB;

class CustomerOrderRepository
{
    public function getUserCart(int $userId)
    {
        return Cart::with(['items.Item', 'items.extras', 'restaurant'])
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

    public function createOrderItem(array $data)
    {
        return OrderItem::create($data);
    }

    public function insertOrderItemExtras(array $extras)
    {
        return DB::table('order_item_extras')->insert($extras);
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
        return Order::with(['restaurant:id,name,logo', 'items.Item:id,name,image', 'items.extras', 'address'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->cursorPaginate($perPage);
    }

    public function getUserOrderById(int $userId, int $orderId)
    {
        $Orders = Order::with(['restaurant:id,name,logo', 'items.Item:id,name,image', 'items.extras', 'address'])
            ->where('user_id', $userId)
            ->where('id', $orderId)
            ->firstOrFail();
        return $Orders;
    }

    public function getOrderTracking(int $orderId)
    {
        return Order::where('id', $orderId)
            ->select([
                'id',
                'status',
                'driver_id',
                'user_id',
                'restaurant_id',
                'delivery_confirmation_code',
                'grand_total',
                'created_at'
            ])
            ->with([
                'driver.user:id,name,phone',
                'restaurant:id,name,logo',
                'items.Item:id,name'
            ])
            ->first();
    }

    public function incrementCouponUsage(int $couponId)
    {
        $coupon = Coupon::find($couponId);
        if ($coupon && $coupon->usage_limit !== null) {
            $coupon->increment('usage_limit');
        }
    }

    public function updateOrderStatus(int $orderId, string $status)
    {
        return Order::where('id', $orderId)->update(['status' => $status]);
    }
}
