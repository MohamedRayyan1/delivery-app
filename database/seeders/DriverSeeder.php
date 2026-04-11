<?php
// database/seeders/DriverSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DriverSeeder extends Seeder
{
    public function run(): void
    {
        $driverUserIds = DB::table('users')->where('role', 'driver')->pluck('id');

        foreach ($driverUserIds as $userId) {
            DB::table('drivers')->insert([
                'user_id' => $userId,
                'is_online' => (bool) rand(0, 1),
                'account_status' => 'active',
                'total_earnings' => rand(15000, 85000),
                'vehicle_type' => rand(0, 1) ? 'motorcycle' : 'car',
                'vehicle_plate_number' => 'SY-' . rand(100, 999),
   // داخل فور لوب السائقين
'current_lat' => 33.5138 + (rand(-45, 45) / 1000), // نطاق 5 كم تقريباً
'current_lng' => 36.2765 + (rand(-45, 45) / 1000), // نطاق 5 كم تقريباً
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
