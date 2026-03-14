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

    public function banUser(int $userId): bool
    {
        $user = User::find($userId);
        if ($user) {
            $user->update(['is_banned' => true]);
            return true;
        }

        return false;
    }

    public function unbanUser(int $userId): bool
    {
        $user = User::find($userId);
        if ($user) {
            $user->update(['is_banned' => false]);
            return true;
        }

        return false;
    }
}
