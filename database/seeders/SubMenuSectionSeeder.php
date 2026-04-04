<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubMenuSectionSeeder extends Seeder
{
    public function run(): void
    {
        $restaurantIds = DB::table('restaurants')->pluck('id');

        foreach ($restaurantIds as $restaurantId) {
            $menuSectionIds = DB::table('menu_section_restaurant')
                ->where('restaurant_id', $restaurantId)
                ->pluck('menu_section_id');

            if ($menuSectionIds->isNotEmpty()) {
                for ($j = 1; $j <= 3; $j++) {
                    DB::table('sub_menu_sections')->insert([
                        'restaurant_id'   => $restaurantId,
                        'menu_section_id' => $menuSectionIds->random(),
                        'name' => match ($j) {
                            1 => 'مشاوي على الفحم',
                            2 => 'وجبات عائلية',
                            default => "قسم فرعي إضافي {$j}",
                        },
                        // صور طعام سوري مناسبة
                        'image' => "https://picsum.photos/id/" . (310 + $restaurantId + $j) . "/800/600",
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
