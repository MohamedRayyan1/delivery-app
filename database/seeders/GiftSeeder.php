<?php
// database/seeders/GiftSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GiftSeeder extends Seeder
{
    public function run(): void
    {
        // 10 هدايا نقاط
        for ($i = 1; $i <= 10; $i++) {
            DB::table('gifts')->insert([
                'name' => match ($i) {
                    1 => 'قسيمة خصم 5000',
                    2 => 'وجبة مجانية',
                    default => "هدية {$i}",
                },
                'points' => rand(200, 1500),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
