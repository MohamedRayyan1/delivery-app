<?php
// database/seeders/MenuItemSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        // ~240 عنصر قائمة (4 لكل قسم فرعي) - مرتبط مع sub_menu_sections
        $subSectionIds = DB::table('sub_menu_sections')->pluck('id');

        foreach ($subSectionIds as $subSectionId) {
            for ($k = 1; $k <= 4; $k++) {
                DB::table('menu_items')->insert([
                    'sub_section_id' => $subSectionId,
                    'name' => match (rand(1, 5)) {
                        1 => 'كباب دمشقي',
                        2 => 'شاورما',
                        3 => 'برجر',
                        4 => 'سلطة فتوش',
                        default => "وجبة {$k}",
                    },
                    'description' => 'وجبة طازجة ومميزة مع توابل سورية أصيلة',
                    'price' => rand(1200, 8500),
                    'discount_price' => rand(0, 1) ? rand(900, 6500) : null,
                    'image' => null,
                    'is_featured' => (bool) rand(0, 1),
                    'is_available' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
