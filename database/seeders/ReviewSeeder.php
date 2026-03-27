<?php
// database/seeders/ReviewSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        // 20 تقييم - مرتبط مع users + orders + restaurants + drivers
        $orders = DB::table('orders')->where('status', 'delivered')->get();

        foreach ($orders->take(20) as $order) {
            DB::table('reviews')->insert([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'restaurant_id' => $order->restaurant_id,
                'restaurant_rating' => rand(35, 50) / 10,
                'driver_id' => $order->driver_id,
                'driver_rating' => rand(35, 50) / 10,
                'comment' => 'خدمة ممتازة وطعام طازج، أنصح به!',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
