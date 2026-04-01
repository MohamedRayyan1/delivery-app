<?php

namespace App\Services\Vendor;

use App\Repositories\Eloquent\VendorOrderRepository;

class VendorOrderService
{
    protected $repository;

    public function __construct(VendorOrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getOrdersList(int $restaurantId, array $filters)
    {
        $status = $filters['status'] ?? null;
        $search = $filters['search'] ?? null;
        $perPage = $filters['per_page'] ?? 15;

        return $this->repository->getRestaurantOrders($restaurantId, $status, $search, $perPage);
    }
}
