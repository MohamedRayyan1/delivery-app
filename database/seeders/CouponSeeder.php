<?php
// database/seeders/CouponSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        // 10 كوبونات
        for ($i = 1; $i <= 10; $i++) {
            DB::table('coupons')->insert([
                'code' => 'RAM' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'discount_type' => rand(0, 1) ? 'percent' : 'fixed',
                'value' => rand(5, 50),
                'min_order_price' => rand(2000, 8000),
                'expiry_date' => now()->addDays(rand(15, 90)),
                'usage_limit' => rand(20, 200),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
