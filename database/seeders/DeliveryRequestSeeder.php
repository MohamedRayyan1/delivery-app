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
        // $statuses = ['pending'];

        foreach ($orders as $order) {
            DB::table('delivery_requests')->insert([
                'order_id' => $order->id,
                'driver_id' => null,
                'offered_delivery_fee' => $order->delivery_fee,
                'required_vehicle_type' => 'motorcycle',
                'status' => 'pending',
                'invoice_image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
