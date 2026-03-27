<?php
// database/seeders/MenuSectionSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSectionSeeder extends Seeder
{
    public function run(): void
    {
        // 10 أقسام رئيسية (عامة)
        for ($i = 1; $i <= 10; $i++) {
            DB::table('menu_sections')->insert([
                'name' => match ($i) {
                    1 => 'مشاوي',
                    2 => 'مقبلات',
                    3 => 'سلطات',
                    4 => 'شوربات',
                    5 => 'مأكولات رئيسية',
                    6 => 'ساندويشات',
                    7 => 'حلويات',
                    8 => 'مشروبات',
                    9 => 'وجبات سريعة',
                    default => "قسم {$i}",
                },
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
