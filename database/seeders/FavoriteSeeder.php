<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $customerIds = DB::table('users')->where('role', 'customer')->pluck('id')->take(15);
        $restaurantIds = DB::table('restaurants')->pluck('id');
        $itemIds = DB::table('menu_items')->pluck('id');

        // نتحقق أولاً من وجود بيانات لتجنب الأخطاء
        if ($restaurantIds->isEmpty() && $itemIds->isEmpty()) {
            return;
        }

        foreach ($customerIds as $userId) {

            // مفضلة مطاعم
            if ($restaurantIds->isNotEmpty()) {
                // نأخذ القيمة الأقل بين (العدد العشوائي المطلوب) وبين (العدد المتوفر فعلياً)
                $countToExtract = min(rand(3, 6), $restaurantIds->count());
                $favRestaurants = $restaurantIds->random($countToExtract);

                foreach ($favRestaurants as $restId) {
                    DB::table('favorite_restaurants')->updateOrInsert(
                        ['user_id' => $userId, 'restaurant_id' => $restId],
                        ['created_at' => now()]
                    );
                }
            }

            // مفضلة أصناف
            if ($itemIds->isNotEmpty()) {
                // نأخذ القيمة الأقل بين (العدد العشوائي المطلوب) وبين (العدد المتوفر فعلياً)
                $itemsToExtract = min(rand(4, 8), $itemIds->count());
                $favItems = $itemIds->random($itemsToExtract);

                foreach ($favItems as $itemId) {
                    DB::table('favorite_items')->updateOrInsert(
                        ['user_id' => $userId, 'item_id' => $itemId],
                        ['created_at' => now()]
                    );
                }
            }
        }
    }
}
