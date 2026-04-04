<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdSeeder extends Seeder
{
    public function run(): void
    {
        $restaurantIds = DB::table('restaurants')->pluck('id');

        for ($i = 1; $i <= 20; $i++) {
            DB::table('ads')->insert([
                'restaurant_id' => $restaurantIds->random(),
                // صور إعلانات طعام شهية وعربية
                'image' => "https://picsum.photos/id/" . (400 + $i) . "/800/600",
                'title' => "عرض خاص {$i} - رمضان 2026",
                'content' => 'خصم 20% على كل الطلبات فوق 10,000 ليرة سورية',
                'status' => 'active',
                'cost' => rand(25000, 120000),
                'start_date' => now()->subDays(rand(1, 15)),
                'end_date' => now()->addDays(rand(20, 60)),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
