<?php
// database/seeders/OrderItemSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        // ~90 عنصر طلب - مرتبط مع orders + menu_items
        $orders = DB::table('orders')->get();
        $menuItemIds = DB::table('menu_items')->pluck('id');

        foreach ($orders as $order) {
            $numItems = rand(2, 4);
            $selectedItems = $menuItemIds->random($numItems);

            foreach ($selectedItems as $itemId) {
                $unitPrice = rand(1200, 6500);
                $quantity = rand(1, 3);
                $totalPrice = $unitPrice * $quantity;

                DB::table('order_items')->insert([
                    'order_id' => $order->id,
                    'item_id' => $itemId,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
