<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubMenuSectionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. نجلب كل المطاعم
        $restaurantIds = DB::table('restaurants')->pluck('id');

        foreach ($restaurantIds as $restaurantId) {

            // 2. نجلب الـ menu_section_id من الجدول الوسيط الخاص بهذا المطعم
            $menuSectionIds = DB::table('menu_section_restaurant')
                ->where('restaurant_id', $restaurantId)
                ->pluck('menu_section_id');

            // 3. إذا كان للمطعم أقسام في الجدول الوسيط، ننشئ الأقسام الفرعية
            if ($menuSectionIds->isNotEmpty()) {
                for ($j = 1; $j <= 3; $j++) {
                    DB::table('sub_menu_sections')->insert([
                        'restaurant_id'   => $restaurantId,
                        // نختار واحد من الأقسام المرتبطة بهذا المطعم بالجدول الوسيط
                        'menu_section_id' => $menuSectionIds->random(),
                        'name' => match ($j) {
                            1 => 'مشاوي على الفحم',
                            2 => 'وجبات عائلية',
                            default => "قسم فرعي إضافي {$j}",
                        },
                        'image'      => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
