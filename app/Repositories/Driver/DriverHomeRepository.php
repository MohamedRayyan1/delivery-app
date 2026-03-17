<?php

namespace App\Repositories\Driver;

use App\Models\Driver;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Support\Carbon;

class DriverHomeRepository
{
    /**
     * جلب السائق
     */
    public function findDriverForUpdate(int $userId): ?Driver
    {
        return Driver::where('user_id', $userId)->first();
    }

    public function findDriverByUserId(int $userId): ?Driver
    {
        return Driver::where('user_id', $userId)->first();
    }

    /**
     * تحديث الحالة وإرجاع الكائن
     */
    public function updateOnlineStatus(Driver $driver, bool $isOnline): Driver
    {
        $driver->update([
            'is_online' => $isOnline
        ]);

        return $driver->fresh();
    }

    /**
     *  الأرباح اليومية
     */
    public function getTodayEarnings(int $driverId): float
    {
        return (float) Order::where('driver_id', $driverId)
            ->where('status', 'completed')
            ->whereDate('delivered_at', Carbon::today())
            ->sum(\DB::raw('delivery_fee * (applied_driver_share / 100)'));
    }

    /**
     *  الطلبات المكتملة
     */
    public function getCompletedOrdersCount(int $driverId): int
    {
        return Order::where('driver_id', $driverId)
            ->where('status', 'completed')
            ->count();
    }

    /**
     *  التقييم العام
     */
    public function getAverageRating(int $driverId): float
    {
        return (float) Review::where('driver_id', $driverId)
            ->whereNotNull('driver_rating')
            ->avg('driver_rating');
    }
}
