<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Hamza',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '0994552143',
                'city' => 'Raqqa',

            ]
        );
    }
}
