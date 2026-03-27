<?php
// database/seeders/CartSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        // 20 سلة + ~70 عنصر سلة - مرتبط مع users + restaurants + menu_items
        $customerIds = DB::table('users')->where('role', 'customer')->pluck('id')->take(20);
        $restaurantIds = DB::table('restaurants')->pluck('id');

        foreach ($customerIds as $userId) {
            $restaurantId = $restaurantIds->random();

            // إنشاء السلة
            $cartId = DB::table('carts')->insertGetId([
                'user_id' => $userId,
                'restaurant_id' => $restaurantId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // إضافة عناصر (من مطعم واحد فقط)
            $itemIds = DB::table('menu_items')
                ->whereIn('sub_section_id', function ($query) use ($restaurantId) {
                    $query->select('id')
                          ->from('sub_menu_sections')
                          ->where('restaurant_id', $restaurantId);
                })
                ->pluck('id')
                ->take(rand(3, 5));

            foreach ($itemIds as $itemId) {
                DB::table('cart_items')->insert([
                    'cart_id' => $cartId,
                    'item_id' => $itemId,
                    'quantity' => rand(1, 3),
                    'notes' => rand(0, 1) ? 'بدون ثوم' : null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
