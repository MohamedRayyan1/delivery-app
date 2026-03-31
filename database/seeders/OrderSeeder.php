<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customerIds = DB::table('users')->where('role', 'customer')->pluck('id');
        $restaurantIds = DB::table('restaurants')->pluck('id');
        $driverIds = DB::table('drivers')->pluck('id');

        $statuses = ['pending', 'preparing', 'picked_up', 'delivered'];

        for ($i = 1; $i <= 50; $i++) {

            $userId = $customerIds->random();

            $addressId = DB::table('user_addresses')
                ->where('user_id', $userId)
                ->inRandomOrder()
                ->value('id') ?? 1;

            $restaurantId = $restaurantIds->random();
            $driverId = $driverIds->random();

            $subtotal = rand(4500, 18500);
            $deliveryFee = rand(800, 2800);
            $discount = rand(0, (int)($subtotal * 0.25));
            $grandTotal = $subtotal + $deliveryFee - $discount;

            /**
             * 🔥 أهم تعديل:
             * توزيع الطلبات على أيام مختلفة
             */
            $daysAgo = rand(0, 2); // 0 = اليوم، 1 = أمس، 2 = قبل يومين

            $createdAt = Carbon::now()
                ->subDays($daysAgo)
                ->setTime(rand(0, 23), rand(0, 59), rand(0, 59));

            $pickedUpAt = (clone $createdAt)->addMinutes(rand(10, 60));
            $deliveredAt = (clone $pickedUpAt)->addMinutes(rand(10, 60));
            $paidAt = (clone $createdAt)->addMinutes(rand(5, 30));

            DB::table('orders')->insert([
                'user_id' => $userId,
                'restaurant_id' => $restaurantId,
                'driver_id' => $driverId,
                'address_id' => $addressId,
                'coupon_id' => null,
                'delivery_confirmation_code' => rand(1000, 9999),
                'status' => $statuses[array_rand($statuses)],
                'payment_method' => rand(0, 1) ? 'cash' : 'wallet',
                'payment_status' => 'paid',
                'transaction_ref' => 'TX-' . rand(100000, 999999),
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'discount_amount' => $discount,
                'grand_total' => $grandTotal,
                'applied_restaurant_commission' => 12.50,
                'applied_driver_share' => 100.00,
                'picked_up_at' => $pickedUpAt,
                'delivered_at' => $deliveredAt,
                'paid_at' => $paidAt,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
