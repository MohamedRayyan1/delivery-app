<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function getProfileWithPoints(int $userId)
    {
        return User::with('customerProfile')->find($userId);
    }

    public function updateProfile(int $userId, array $data)
    {
        $user = User::find($userId);

        if ($user) {
            $user->update($data);
            return $user;
        }

        return null;
    }

    public function deleteUser(int $userId)
    {
        $user = User::find($userId);

        if ($user) {
            return $user->delete();
        }

        return false;
    }
}
