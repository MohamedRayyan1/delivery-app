<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        // 1. جلب معرفات المديرين و "خلطهم" عشوائياً
        $managerIds = DB::table('users')
            ->where('role', 'restaurant_manager')
            ->pluck('id')
            ->shuffle(); // خلط الترتيب لضمان العشوائية

        // 2. التأكد من أن عدد المديرين يكفي لعدد المطاعم (اختياري لكنه أضمن)
        $count = min(20, $managerIds->count());

        for ($i = 1; $i <= $count; $i++) {
            // 3. سحب أول ID من المصفوفة وحذفه منها لضمان عدم التكرار
            $managerId = $managerIds->shift();

            DB::table('restaurants')->insert([
                'manager_user_id' => $managerId,
                'name' => "مطعم {$i} السوري",
                'governorate' => 'دمشق',
                'city' => 'دمشق',
                'status' => 'active',
                'logo' => null,
                'cover_image' => null,
                'description' => 'أشهى الأكلات السورية التقليدية مع خدمة توصيل سريعة',
                'rating' => rand(38, 49) / 10,
                'delivery_cost' => rand(800, 2500),
                'min_order_price' => rand(1500, 6000),
                'delivery_time' => rand(20, 45) . ' دقيقة',
                'is_featured' => (bool) rand(0, 1),
                'lat' => 33.5138 + (rand(-40, 40) / 1000),
                'lng' => 36.2765 + (rand(-40, 40) / 1000),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
