<?php
// database/seeders/MenuSectionRestaurantSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSectionRestaurantSeeder extends Seeder
{
    public function run(): void
    {
        // pivot table - ربط الأقسام بالمطاعم (حوالي 80 سطر)
        $menuSectionIds = DB::table('menu_sections')->pluck('id');
        $restaurantIds = DB::table('restaurants')->pluck('id');

        foreach ($restaurantIds as $restaurantId) {
            $selectedSections = $menuSectionIds->random(rand(4, 8));
            foreach ($selectedSections as $sectionId) {
                DB::table('menu_section_restaurant')->insert([
                    'menu_section_id' => $sectionId,
                    'restaurant_id' => $restaurantId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
