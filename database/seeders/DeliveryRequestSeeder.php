<?php
// database/seeders/DeliveryRequestSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeliveryRequestSeeder extends Seeder
{
    public function run(): void
    {
        // 30 طلب توصيل - مرتبط مع orders + drivers
        $orders = DB::table('orders')->whereNotNull('driver_id')->get();
        $statuses = ['pending', 'accepted', 'picked_up', 'delivered'];

        foreach ($orders as $order) {
            DB::table('delivery_requests')->insert([
                'order_id' => $order->id,
                'driver_id' => $order->driver_id,
                'offered_delivery_fee' => $order->delivery_fee,
                'required_vehicle_type' => null,
                'status' => $statuses[array_rand($statuses)],
                'invoice_image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
