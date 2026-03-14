<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
    public function getProfileWithPoints(int $userId);
    public function updateProfile(int $userId, array $data);
    public function deleteUser(int $userId);
    public function banUser(int $userId): bool;
    public function unbanUser(int $userId): bool;
}
