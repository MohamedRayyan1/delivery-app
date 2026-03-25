<?php

namespace App\Traits;

use App\Models\Driver;
use App\Models\Restaurant;

trait LocationTrait
{
    /**
     * حساب المسافة بين نقطتين جغرافيتين بالكيلومتر (Haversine formula)
     * @param float $lat1 Latitude نقطة البداية
     * @param float $lng1 Longitude نقطة البداية
     * @param float $lat2 Latitude نقطة النهاية
     * @param float $lng2 Longitude نقطة النهاية
     * @return float المسافة بالكيلومتر (مع دقة 2 منازل)
     */
    public function calculateDistanceInKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371.0; // نصف قطر الأرض بالكيلومتر

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    /**
     * حساب المسافة بين المطعم وعنوان الزبون (سواء عنوان مخزن أو عنوان حالي)
     * @param \App\Models\Restaurant $restaurant
     * @param float $customerLat
     * @param float $customerLng
     * @return float
     */
    public function getDistanceFromRestaurantToCustomerAddress(Restaurant $restaurant, float $customerLat, float $customerLng): float
    {
        return $this->calculateDistanceInKm(
            $restaurant->lat,
            $restaurant->lng,
            $customerLat,
            $customerLng
        );
    }

    /**
     * حساب المسافة بين المطعم والسائق الحالي
     * @param \App\Models\Restaurant $restaurant
     * @param \App\Models\Driver $driver
     * @return float|null (null إذا لم يكن للسائق موقع حالي)
     */
    public function getDistanceFromRestaurantToDriver(Restaurant $restaurant, Driver $driver): ?float
    {
        if (is_null($driver->current_lat) || is_null($driver->current_lng)) {
            return null;
        }

        return $this->calculateDistanceInKm(
            (float) $restaurant->lat,
            (float) $restaurant->lng,
            (float) $driver->current_lat,
            (float) $driver->current_lng
        );
    }
}
