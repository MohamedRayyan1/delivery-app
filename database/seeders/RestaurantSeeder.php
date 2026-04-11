<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        $managerIds = DB::table('users')
            ->where('role', 'vendor')
            ->pluck('id')
            ->shuffle();

        $count = $managerIds->count();

        for ($i = 0; $i < $count; $i++) {
            $managerId = $managerIds[$i];

            DB::table('restaurants')->insert([
                'manager_user_id' => $managerId,
                'name' => "مطعم " . ($i + 1) . " السوري",
                'governorate' => 'دمشق',
                'city' => 'دمشق',
                'status' => 'active',
                // صور مطاعم عربية أنيقة وواقعية
                'logo' => "https://picsum.photos/id/" . (240 + $i) . "/400/400",   // شعارات مطاعم
                'cover_image' => "https://picsum.photos/id/" . (280 + $i) . "/1200/600", // صور خارجية/داخلية لمطعم
                'description' => 'أشهى الأكلات السورية التقليدية مع خدمة توصيل سريعة',
                'rating' => rand(38, 49) / 10,
                'delivery_cost' => rand(800, 2500),
                'min_order_price' => rand(1500, 6000),
                'delivery_time' => rand(20, 45) . ' دقيقة',
                'is_featured' => (bool) rand(0, 1),
'lat' => (float) (33.5138 + (rand(-25, 25) / 1000)),
'lng' => (float) (36.2765 + (rand(-25, 25) / 1000)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
