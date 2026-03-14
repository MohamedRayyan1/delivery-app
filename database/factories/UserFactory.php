<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => fake()->unique()->phoneNumber(), // توليد رقم هاتف فريد
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),

            // إضافة الحقول الجديدة بناءً على المايجريشن الخاص بك
            'role' => fake()->randomElement(['customer', 'driver', 'restaurant_manager']),
            'city' => fake()->randomElement(['دمشق', 'حلب', 'حمص', 'اللاذقية']),
            'fcm_token' => Str::random(60),
            'is_banned' => false,

            'remember_token' => Str::random(10),
        ];
    }

    // حالة خاصة لإنشاء مسؤول (Admin)
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }
}
