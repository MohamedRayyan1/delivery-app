<?php
// database/seeders/CustomerProfileSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerProfileSeeder extends Seeder
{
    public function run(): void
    {
        // مرتبط مع users (role = customer) فقط - 20 سطر
        $customerIds = DB::table('users')->where('role', 'customer')->pluck('id');

        foreach ($customerIds as $userId) {
            DB::table('customer_profiles')->insert([
                'user_id' => $userId,
                'points' => rand(50, 1200),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
