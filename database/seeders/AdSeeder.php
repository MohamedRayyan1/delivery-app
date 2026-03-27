<?php
// database/seeders/AdSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdSeeder extends Seeder
{
    public function run(): void
    {
        // 20 إعلان - مرتبط مع restaurants
        $restaurantIds = DB::table('restaurants')->pluck('id');

        for ($i = 1; $i <= 20; $i++) {
            DB::table('ads')->insert([
                'restaurant_id' => $restaurantIds->random(),

                // ✅ الحل: صورة وهمية (غير null)
                'image' => "ads/ad-{$i}.jpg",   // أو "https://placehold.co/600x400?text=Ad+{$i}"

                'title' => "عرض خاص {$i} - رمضان 2026",
                'content' => 'خصم 20% على كل الطلبات فوق 10,000 ليرة',
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
