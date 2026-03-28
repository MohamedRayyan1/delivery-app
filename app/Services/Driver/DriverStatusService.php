<?php

namespace App\Services\Driver;

use App\Repositories\Eloquent\DriverStatusRepository;
use Exception;

class DriverStatusService
{
    protected $repository;

    public function __construct(DriverStatusRepository $repository)
    {
        $this->repository = $repository;
    }

    public function toggleOnlineStatus(int $userId, bool $isOnline)
    {
        $driver = $this->repository->getDriverByUserId($userId);

        if ($isOnline && $driver->account_status !== 'active') {
            throw new Exception('لا يمكنك استقبال الطلبات، حسابك ليس فعالاً بعد.');
        }

        $this->repository->updateOnlineStatus($driver->id, $isOnline);

        return $isOnline;
    }
}
