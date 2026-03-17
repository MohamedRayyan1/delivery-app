<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Driver;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DriverSeeder extends Seeder
{
    public function run(): void
    {
        // 1. إنشاء سائق تجريبي ثابت للتطوير
        $adminUser = User::create([
            'name'      => 'سائق تجريبي',
            'phone'     => '0900000000',
            'email'     => 'driver@example.com',
            'password'  => Hash::make('password'),
            'role'      => 'driver', //
            'city'      => 'دمشق',
            'is_banned' => false,
        ]);

        Driver::create([
            'user_id'              => $adminUser->id,
            'is_online'            => true, //
            'account_status'       => 'active', //
            'vehicle_type'         => 'motorcycle', //
            'vehicle_plate_number' => 'ABC-123',
            'current_lat'          => 33.5138,
            'current_lng'          => 36.2765,
        ]);

        // 2. إنشاء مجموعة سائقين عشوائيين باستخدام Factory (اختياري)
        // أو استخدام حلقة تكرار بسيطة:
        $cities = ['دمشق', 'حلب', 'حمص', 'اللاذقية'];
        $vehicles = ['motorcycle', 'car'];

        for ($i = 1; $i <= 10; $i++) {
            $user = User::create([
                'name'     => "Driver Name $i",
                'phone'    => '091111111' . $i,
                'role'     => 'driver',
                'password' => Hash::make('password'),
                'city'     => $cities[array_rand($cities)],
            ]);

            Driver::create([
                'user_id'              => $user->id,
                'is_online'            => (bool)rand(0, 1), // تنويع حالة الاتصال
                'account_status'       => $i % 3 == 0 ? 'pending' : 'active', // تنويع حالة الحساب
                'vehicle_type'         => $vehicles[array_rand($vehicles)],
                'vehicle_plate_number' => 'PLATE-' . rand(1000, 9999),
            ]);
        }
    }
}
