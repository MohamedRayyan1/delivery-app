<?php
// database/seeders/GovernorateSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GovernorateSeeder extends Seeder
{
    public function run(): void
    {
        $governorates = [
            ['name' => 'دمشق'],
            ['name' => 'ريف دمشق'],
            ['name' => 'حلب'],
            ['name' => 'حمص'],
            ['name' => 'حماة'],
            ['name' => 'اللاذقية'],
            ['name' => 'طرطوس'],
            ['name' => 'إدلب'],
            ['name' => 'دير الزور'],
            ['name' => 'الرقة'],
            ['name' => 'الحسكة'],
            ['name' => 'السويداء'],
            ['name' => 'درعا'],
            ['name' => 'القنيطرة'],
        ];

        foreach ($governorates as $gov) {
            DB::table('governorates')->insert([
                'name' => $gov['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
