<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Governorate;

class GovernorateSeeder extends Seeder
{
    public function run(): void
    {
        $governorates = [
            'دمشق',
            'ريف دمشق',
            'حلب',
            'حمص',
            'حماة',
            'اللاذقية',
            'طرطوس',
            'إدلب',
            'درعا',
            'السويداء',
            'القنيطرة',
            'دير الزور',
            'الحسكة',
            'الرقة',
        ];

        foreach ($governorates as $governorate) {
            Governorate::firstOrCreate(['name' => $governorate]);
        }
    }
}
