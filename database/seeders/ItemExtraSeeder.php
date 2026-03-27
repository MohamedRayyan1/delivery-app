<?php
// database/seeders/ItemExtraSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemExtraSeeder extends Seeder
{
    public function run(): void
    {
        // ~120 إضافة (2-3 لكل عنصر) - مرتبط مع menu_items
        $menuItemIds = DB::table('menu_items')->pluck('id')->take(50); // نأخذ 50 فقط للتوازن

        foreach ($menuItemIds as $menuItemId) {
            $numExtras = rand(2, 3);
            for ($m = 1; $m <= $numExtras; $m++) {
                DB::table('item_extras')->insert([
                    'menu_item_id' => $menuItemId,
                    'name' => match (rand(1, 4)) {
                        1 => 'جبنة إضافية',
                        2 => 'بصل مشوي',
                        3 => 'صودا',
                        default => "إضافة {$m}",
                    },
                    'category' => rand(0, 1) ? 'إضافات' : 'مشروبات',
                    'price' => rand(150, 750),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
