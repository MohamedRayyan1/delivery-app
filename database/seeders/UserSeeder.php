<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 20 عميل + 10 سائق + 8 مدير مطعم + 2 أدمن = واقعي أكثر
        // العملاء
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

        // السائقون
        for ($i = 1; $i <= 10; $i++) {
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

        // مديري المطاعم (8 مطاعم)
        for ($i = 1; $i <= 8; $i++) {
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

        // الأدمن
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
