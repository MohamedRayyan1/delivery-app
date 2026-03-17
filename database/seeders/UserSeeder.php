<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
   // UserSeeder.php
public function run(): void
{
    // إنشاء مسؤول نظام (Admin)
    User::create([
        'name'     => 'Admin Account',
        'phone'    => '0933333333',
        'role'     => 'admin',
        'password' => Hash::make('admin123'),
    ]);

    // إنشاء مجموعة زبائن عشوائيين
    for ($i = 1; $i <= 5; $i++) {
        User::create([
            'name'  => "Customer $i",
            'phone' => '094444444' . $i,
            'role'  => 'customer',
            'password' => Hash::make('password'),
        ]);
    }
}
}
