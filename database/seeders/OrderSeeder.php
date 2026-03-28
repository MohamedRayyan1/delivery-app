<?php
// database/seeders/OrderSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customerIds = DB::table('users')->where('role', 'customer')->pluck('id');
        $restaurantIds = DB::table('restaurants')->pluck('id');
        $driverIds = DB::table('drivers')->pluck('id');

        // الحالات الجديدة حسب الـ Migration
        $statuses = ['pending', 'preparing', 'picked_up', 'delivered'];

        for ($i = 1; $i <= 30; $i++) {
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
                'picked_up_at' => now()->subHours(rand(2, 48)),
                'delivered_at' => now()->subHours(rand(1, 72)),
                'paid_at' => now()->subHours(rand(1, 72)),
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now(),
            ]);
        }
    }
}
