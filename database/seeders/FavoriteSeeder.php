<?php
// database/seeders/FavoriteSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        // ~70 مفضلة مطاعم + ~80 مفضلة أصناف - مرتبط مع users + restaurants + menu_items
        $customerIds = DB::table('users')->where('role', 'customer')->pluck('id')->take(15);
        $restaurantIds = DB::table('restaurants')->pluck('id');
        $itemIds = DB::table('menu_items')->pluck('id');

        foreach ($customerIds as $userId) {
            // مفضلة مطاعم
            $favRestaurants = $restaurantIds->random(rand(3, 6));
            foreach ($favRestaurants as $restId) {
                DB::table('favorite_restaurants')->insert([
                    'user_id' => $userId,
                    'restaurant_id' => $restId,
                    'created_at' => now(),
                ]);
            }

            // مفضلة أصناف
            $favItems = $itemIds->random(rand(4, 8));
            foreach ($favItems as $itemId) {
                DB::table('favorite_items')->insert([
                    'user_id' => $userId,
                    'item_id' => $itemId,
                    'created_at' => now(),
                ]);
            }
        }
    }
}
