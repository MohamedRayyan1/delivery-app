<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $subSectionIds = DB::table('sub_menu_sections')->pluck('id');

        foreach ($subSectionIds as $index => $subSectionId) {
            for ($k = 1; $k <= 4; $k++) {
                $itemName = match (rand(1, 6)) {
                    1 => 'كباب دمشقي',
                    2 => 'شاورما دجاج',
                    3 => 'برجر لحم',
                    4 => 'سلطة فتوش',
                    5 => 'كبة مقلية',
                    default => 'شيش طاووق',
                };

                // اختيار image_id حسب نوع الوجبة لتكون الصورة مطابقة
                $imageId = match ($itemName) {
                    'كباب دمشقي', 'شيش طاووق' => 292,   // grilled meat / kebab
                    'شاورما دجاج'               => 1015,  // shawarma
                    'برجر لحم'                  => 106,   // burger
                    'سلطة فتوش'                 => 1080,  // salad / fattoush
                    'كبة مقلية'                 => 431,   // appetizers / kibbeh
                    default                      => 292,
                };

                DB::table('menu_items')->insert([
                    'sub_section_id' => $subSectionId,
                    'name'           => $itemName,
                    'description'    => 'وجبة طازجة ومميزة مع توابل سورية أصيلة',
                    'price'          => rand(1200, 8500),
                    'discount_price' => rand(0, 1) ? rand(900, 6500) : null,
                    'image'          => "https://picsum.photos/id/" . $imageId . "/800/600",
                    'is_featured'    => (bool) rand(0, 1),
                    'is_available'   => true,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }
        }
    }
}
