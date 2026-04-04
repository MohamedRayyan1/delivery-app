<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            ['name' => 'مشاوي',          'image_id' => 292],   // grilled meat
            ['name' => 'مقبلات',         'image_id' => 431],   // mezze platter
            ['name' => 'سلطات',          'image_id' => 1080],  // fresh salad
            ['name' => 'شوربات',         'image_id' => 1060],  // soup
            ['name' => 'مأكولات رئيسية', 'image_id' => 292],   // main dishes
            ['name' => 'ساندويشات',      'image_id' => 106],   // sandwiches / shawarma
            ['name' => 'حلويات',         'image_id' => 1083],  // desserts
            ['name' => 'مشروبات',        'image_id' => 201],   // drinks
            ['name' => 'وجبات سريعة',    'image_id' => 292],   // fast food
        ];

        foreach ($sections as $index => $section) {
            DB::table('menu_sections')->insert([
                'name'  => $section['name'],
                'image' => "https://picsum.photos/id/" . $section['image_id'] . "/800/600",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
