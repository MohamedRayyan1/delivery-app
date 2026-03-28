<?php

namespace App\Services\Driver;

use App\Repositories\Eloquent\DriverOrderHistoryRepository;

class DriverOrderHistoryService
{
    protected $repository;

    public function __construct(DriverOrderHistoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getHistory(int $driverId, array $filters)
    {
        $status = $filters['status'] ?? null;
        $search = $filters['search'] ?? null;
        $perPage = $filters['per_page'] ?? 15;

        return $this->repository->getOrdersHistory($driverId, $status, $search, $perPage);
    }
}
