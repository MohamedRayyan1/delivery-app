<?php
// database/seeders/SubMenuSectionSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubMenuSectionSeeder extends Seeder
{
    public function run(): void
    {
        // 60 قسم فرعي (3 لكل مطعم) - مرتبط مع restaurants
        $restaurantIds = DB::table('restaurants')->pluck('id');

        foreach ($restaurantIds as $restaurantId) {
            for ($j = 1; $j <= 3; $j++) {
                DB::table('sub_menu_sections')->insert([
                    'restaurant_id' => $restaurantId,
                    'name' => match ($j) {
                        1 => 'مشاوي على الفحم',
                        2 => 'وجبات عائلية',
                        default => "قسم فرعي {$j}",
                    },
                    'image' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
