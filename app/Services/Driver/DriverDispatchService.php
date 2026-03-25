<?php

namespace App\Services;

use App\Repositories\DriverDispatchRepository;
use App\Traits\LocationTrait; // التابع لحساب المسافة

class DriverDispatchService
{
    use LocationTrait;

    protected $repo;

    public function __construct(DriverDispatchRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * 🔥 التابع الرئيسي
     * حساب المسافة بين المطعم وعنوان الزبون
     * يدعم حالتين:
     * 1. عنوان محفوظ (address_id)
     * 2. موقع حالي (lat/lng)
     */
    public function calculateDistanceToCustomer(
        int $restaurantId,
        ?int $addressId = null,
        ?float $lat = null,
        ?float $lng = null
    ): ?float {
        $restaurant = $this->repo->getRestaurantById($restaurantId);

        if (!$restaurant || !$restaurant->lat || !$restaurant->lng) {
            return null;
        }

        // الحالة 1: عنوان محفوظ
        if ($addressId) {
            $address = $this->repo->getUserAddress($addressId);
            if (!$address) return null;

            return $this->calculateDistanceInKm(
                (float)$restaurant->lat,
                (float)$restaurant->lng,
                (float)$address->lat,
                (float)$address->lng
            );
        }

        // الحالة 2: الموقع الحالي
        if ($lat && $lng) {
            return $this->calculateDistanceInKm(
                (float)$restaurant->lat,
                (float)$restaurant->lng,
                (float)$lat,
                (float)$lng
            );
        }

        return null;
    }
}
