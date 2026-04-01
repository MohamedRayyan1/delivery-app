<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        // جلب معرفات المستخدمين (زبائن فقط) والمطاعم والأصناف
        $customerIds = DB::table('users')->where('role', 'customer')->pluck('id')->take(15);
        $restaurantIds = DB::table('restaurants')->pluck('id');
        $itemIds = DB::table('menu_items')->pluck('id');

        foreach ($customerIds as $userId) {

            // --- 1. مفضلة المطاعم ---
            $howManyRestaurants = min(rand(1, 4), $restaurantIds->count());

            if ($howManyRestaurants > 0) {
                $favRestaurants = $restaurantIds->random($howManyRestaurants);

                foreach ($favRestaurants as $restId) {
                    DB::table('favorite_restaurants')->insert([
                        'user_id'       => $userId,
                        'restaurant_id' => $restId,
                        'created_at'    => now(),
                        // ملاحظة: إذا كان الجدول لا يحتوي على updated_at، اتركها محذوفة كما فعلتُ هنا
                    ]);
                }
            }

            // --- 2. مفضلة الأصناف ---
            $howManyItems = min(rand(2, 6), $itemIds->count());

            if ($howManyItems > 0) {
                $favItems = $itemIds->random($howManyItems);

                foreach ($favItems as $itemId) {
                    DB::table('favorite_items')->insert([
                        'user_id'    => $userId,
                        'item_id'    => $itemId,
                        'created_at' => now(),
                    ]);
                }
            }
        }
    }
}
