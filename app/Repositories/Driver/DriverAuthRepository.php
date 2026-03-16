<?php

namespace App\Repositories\Driver;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DriverAuthRepository
{
    public function createUser(array $data): User
    {
        return User::create([
            'name'      => $data['name'],
            'phone'     => $data['phone'],
            'email'     => $data['email'] ?? null,
            'password'  => Hash::make(Str::random(16)),
            'role'      => 'driver',
            'city'      => $data['city'] ?? null,
            'fcm_token' => $data['fcm_token'] ?? null,
        ]);
    }

    public function createDriver(array $driverData, int $userId): Driver
    {
        return Driver::create(array_merge($driverData, ['user_id' => $userId]));
    }

    public function findUserByPhone(string $phone): ?User
    {
        return User::where('phone', $phone)->first();
    }

    public function findDriverByUserId(int $userId): ?Driver
    {
        return Driver::where('user_id', $userId)->first();
    }

    public function updateUser(int $userId, array $data): bool
    {
        $update = [];
        if (isset($data['name']))      $update['name']      = $data['name'];
        if (isset($data['email']))     $update['email']     = $data['email'];
        if (isset($data['city']))      $update['city']      = $data['city'];
        if (isset($data['fcm_token'])) $update['fcm_token'] = $data['fcm_token'];
        if (isset($data['password']))  $update['password']  = Hash::make($data['password']);

        return User::where('id', $userId)->update($update);
    }

    public function updateDriver(int $driverId, array $data): bool
    {
        return Driver::where('id', $driverId)->update($data);
    }

    public function getProfile(int $userId): ?array
    {
        $user = User::with('driver')->find($userId);

        if (!$user || $user->role !== 'driver') {
            return null;
        }

        return [
            'user'  => $user,
            'driver' => $user->driver,
        ];
    }
}
