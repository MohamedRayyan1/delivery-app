<?php

namespace App\Repositories\Eloquent;

use App\Models\Coupon;

class AdminCouponRepository
{
    public function getAllCoupons()
    {
        return Coupon::orderBy('created_at', 'desc')->get();
    }

    public function findCouponById(int $id)
    {
        return Coupon::findOrFail($id);
    }

    public function createCoupon(array $data)
    {
        return Coupon::create($data);
    }

    public function updateCoupon(int $id, array $data)
    {
        $coupon = $this->findCouponById($id);
        $coupon->update($data);
        return $coupon;
    }

    public function deleteCoupon(int $id)
    {
        $coupon = $this->findCouponById($id);
        return $coupon->delete();
    }
}
