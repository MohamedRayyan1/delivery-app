<?php
// database/seeders/UserSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 30 مستخدم مترابطين (20 عميل + 5 سائق + 3 مدير مطعم + 2 أدمن)
        // كل البيانات مترابطة مع باقي الجداول (role, city, phone unique)

        // 1. العملاء (20)
        for ($i = 1; $i <= 20; $i++) {
            DB::table('users')->insert([
                'name' => "عميل {$i}",
                'phone' => "093" . str_pad($i, 7, '0', STR_PAD_LEFT),
                'email' => "customer{$i}@example.com",
                'password' => Hash::make('password'),
                'role' => 'customer',
                'city' => 'دمشق',
                'fcm_token' => null,
                'is_banned' => false,
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. السائقون (5)
        for ($i = 1; $i <= 5; $i++) {
            DB::table('users')->insert([
                'name' => "سائق {$i}",
                'phone' => "094" . str_pad($i, 7, '0', STR_PAD_LEFT),
                'email' => "driver{$i}@example.com",
                'password' => Hash::make('password'),
                'role' => 'driver',
                'city' => 'دمشق',
                'fcm_token' => null,
                'is_banned' => false,
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. مديري المطاعم (3)
        for ($i = 1; $i <= 3; $i++) {
            DB::table('users')->insert([
                'name' => "مدير مطعم {$i}",
                'phone' => "095" . str_pad($i, 7, '0', STR_PAD_LEFT),
                'email' => "manager{$i}@example.com",
                'password' => Hash::make('password'),
                'role' => 'vendor',
                'city' => 'دمشق',
                'fcm_token' => null,
                'is_banned' => false,
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4. الأدمن (2)
        for ($i = 1; $i <= 2; $i++) {
            DB::table('users')->insert([
                'name' => "أدمن {$i}",
                'phone' => "096" . str_pad($i, 7, '0', STR_PAD_LEFT),
                'email' => "admin{$i}@example.com",
                'password' => Hash::make('password'),
                'role' => 'admin',
                'city' => 'دمشق',
                'fcm_token' => null,
                'is_banned' => false,
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
