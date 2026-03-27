<?php
// database/seeders/SupportContactSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupportContactSeeder extends Seeder
{
    public function run(): void
    {
        // 20 جهة اتصال دعم (واحد أو اثنان لكل محافظة)
        $governorates = DB::table('governorates')->pluck('name');

        foreach ($governorates as $gov) {
            DB::table('support_contacts')->insert([
                'governorate' => $gov,
                'phone_number' => '011' . rand(1000000, 9999999),
                'note' => 'دعم فني واستفسارات الطلبات',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (rand(0, 1)) {
                DB::table('support_contacts')->insert([
                    'governorate' => $gov,
                    'phone_number' => '093' . rand(1000000, 9999999),
                    'note' => 'دعم السائقين والشكاوى',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
