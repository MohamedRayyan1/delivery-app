<?php

namespace App\Services\Admin;

use App\Repositories\Eloquent\AdminCouponRepository;

class AdminCouponService
{
    protected $repository;

    public function __construct(AdminCouponRepository $repository)
    {
        $this->repository = $repository;
    }

    public function listCoupons()
    {
        return $this->repository->getAllCoupons();
    }

    public function storeCoupon(array $data)
    {
        return $this->repository->createCoupon($data);
    }

    public function updateCoupon(int $id, array $data)
    {
        return $this->repository->updateCoupon($id, $data);
    }

    public function deleteCoupon(int $id)
    {
        return $this->repository->deleteCoupon($id);
    }
}
