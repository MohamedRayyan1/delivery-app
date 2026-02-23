<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;

class ProfileService
{
    private UserRepositoryInterface $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function getProfile(int $userId)
    {
        return $this->userRepo->getProfileWithPoints($userId);
    }

    public function updateProfile(int $userId, array $data)
    {
        $this->userRepo->updateProfile($userId, $data);
        return $this->userRepo->getProfileWithPoints($userId);
    }

    public function updateFcmToken(int $userId, string $token)
    {
        return $this->userRepo->updateProfile($userId, ['fcm_token' => $token]);
    }

    public function deleteAccount(int $userId)
    {
        return $this->userRepo->deleteUser($userId);
    }
}
