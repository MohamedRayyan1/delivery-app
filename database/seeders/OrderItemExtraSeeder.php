<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemExtraSeeder extends Seeder
{
    public function run(): void
    {
        $orderItems = DB::table('order_items')->get();

        foreach ($orderItems as $orderItem) {
            $possibleExtras = DB::table('item_extras')
                ->where('menu_item_id', $orderItem->item_id)
                ->get();

            if ($possibleExtras->isEmpty()) {
                continue;
            }

            $numExtras = rand(0, 2); // واقعي: 0-2 إضافات لكل عنصر
            if ($numExtras === 0) {
                continue;
            }

            $selected = $possibleExtras->random($numExtras);

            foreach ($selected as $extra) {
                DB::table('order_item_extras')->insert([
                    'order_item_id' => $orderItem->id,
                    'extra_id'      => $extra->id,
                    'extra_name'    => $extra->name,
                    'extra_price'   => $extra->price,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }
    }
}
