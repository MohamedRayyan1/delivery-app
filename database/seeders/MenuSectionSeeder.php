<?php

namespace Database\Seeders;

use App\Models\MenuSection;
use Illuminate\Database\Seeder;

class MenuSectionSeeder extends Seeder
{
    public function run(): void
    {
        // إنشاء 10 سجلات مباشرة
        MenuSection::factory()->count(10)->create();
    }
}
