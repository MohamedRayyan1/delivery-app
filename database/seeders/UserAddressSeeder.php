<?php
// database/seeders/UserAddressSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserAddressSeeder extends Seeder
{
    public function run(): void
    {
        // ~40 عنوان (كل عميل له 2 عنوان تقريباً) - مرتبط مع users
        $users = DB::table('users')->whereIn('role', ['customer', 'driver'])->get();

        foreach ($users as $user) {
            // عنوان 1
            DB::table('user_addresses')->insert([
                'user_id' => $user->id,
                'label' => 'المنزل',
                'street' => 'شارع الثورة',
                'details' => 'مقابل الجامع الكبير',
                'floor' => rand(1, 10),
                'phone' => $user->phone,
                'lat' => 33.5138 + (rand(-30, 30) / 1000),
                'lng' => 36.2765 + (rand(-30, 30) / 1000),
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // عنوان 2 (ليس لكل المستخدمين)
            if (rand(0, 1)) {
                DB::table('user_addresses')->insert([
                    'user_id' => $user->id,
                    'label' => 'العمل',
                    'street' => 'ساحة الأمويين',
                    'details' => 'بجانب البريد',
                    'floor' => rand(1, 5),
                    'phone' => $user->phone,
                    'lat' => 33.5100 + (rand(-20, 20) / 1000),
                    'lng' => 36.2900 + (rand(-20, 20) / 1000),
                    'is_default' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
