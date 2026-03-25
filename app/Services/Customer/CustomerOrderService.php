<?php

namespace App\Services\Customer;

use App\Repositories\Eloquent\CustomerOrderRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class CustomerOrderService
{
    protected $repository;

    public function __construct(CustomerOrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function checkout(int $userId, array $data)
    {
        return DB::transaction(function () use ($userId, $data) {

            $cart = $this->repository->getUserCart($userId);

            if (!$cart || $cart->items->isEmpty()) {
                throw new Exception('Cart is empty. Cannot proceed to checkout.');
            }

            $subtotal = 0;
            foreach ($cart->items as $cartItem) {
                $price = $cartItem->Item->discount_price ?? $cartItem->Item->price;
                $subtotal += ($price * $cartItem->quantity);
            }

            $deliveryFee = $cart->restaurant->delivery_cost ?? 0;
            $discountAmount = 0;
            $couponId = null;

            if (!empty($data['coupon_code'])) {
                $coupon = $this->repository->findCouponWithLock($data['coupon_code']);

                if (!$coupon || !$coupon->isValid()) {
                    throw new Exception('Invalid or expired coupon.');
                }

                if ($coupon->usage_limit !== null && $coupon->usage_limit <= 0) {
                    throw new Exception('Coupon usage limit has been reached.');
                }

                if ($coupon->min_order_price !== null && $subtotal < $coupon->min_order_price) {
                    throw new Exception('Order subtotal does not meet the minimum requirement for this coupon.');
                }

                if ($coupon->discount_type === 'percent') {
                    $discountAmount = ($subtotal * $coupon->value) / 100;
                } else {
                    $discountAmount = $coupon->value;
                }

                if ($discountAmount > $subtotal) {
                    $discountAmount = $subtotal;
                }

                $couponId = $coupon->id;
                $this->repository->decrementCouponUsage($coupon);
            }

            $grandTotal = ($subtotal + $deliveryFee) - $discountAmount;

            $orderData = [
                'user_id' => $userId,
                'restaurant_id' => $cart->restaurant_id,
                'address_id' => $data['address_id'],
                'coupon_id' => $couponId,
                'status' => 'pending',
                'payment_method' => $data['payment_method'],
                'payment_status' => 'unpaid',
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'discount_amount' => $discountAmount,
                'grand_total' => $grandTotal,
                'applied_restaurant_commission' => 10.00,
                'applied_driver_share' => 100.00,
            ];

            $order = $this->repository->createOrder($orderData);

            $orderItems = [];
            $now = now();
            foreach ($cart->items as $cartItem) {
                $unitPrice = $cartItem->Item->discount_price ?? $cartItem->Item->price;
                $orderItems[] = [
                    'order_id' => $order->id,
                    'item_id' => $cartItem->item_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $unitPrice * $cartItem->quantity,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            $this->repository->insertOrderItems($orderItems);
            $this->repository->clearUserCart($userId);

            return $order->load(['items.Item', 'restaurant']);
        });
    }

    public function getUserOrders(int $userId)
    {
        return $this->repository->getUserOrders($userId);
    }

    public function getOrderDetails(int $userId, int $orderId)
    {    
        return $this->repository->getUserOrderById($userId, $orderId);
    }
}
