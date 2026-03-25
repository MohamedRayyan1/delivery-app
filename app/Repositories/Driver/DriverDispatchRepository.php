<?php

namespace App\Repositories;

use App\Models\Restaurant;
use App\Models\UserAddress;

class DriverDispatchRepository
{
    /**
     * جلب بيانات المطعم
     */
    public function getRestaurantById(int $restaurantId): ?Restaurant
    {
        return Restaurant::find($restaurantId);
    }

    /**
     * جلب عنوان مستخدم محفوظ (إذا اختاره الزبون)
     */
    public function getUserAddress(int $addressId): ?UserAddress
    {
        return UserAddress::find($addressId);
    }
}
