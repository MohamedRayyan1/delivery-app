<?php
// app/Repositories/Driver/DriverHomeRepository.php

namespace App\Repositories\Driver;

use App\Models\Driver;
use App\Models\DriverDailyStat;
use App\Models\Review;
use Illuminate\Support\Carbon;

class DriverHomeRepository
{
    public function findDriverForUpdate(int $userId): ?Driver
    {
        return Driver::where('user_id', $userId)->first();
    }
    public function findDriverByUserId(int $userId): ?Driver
    {
        return Driver::where('user_id', $userId)->first();
    }
    public function updateOnlineStatus(Driver $driver, bool $isOnline): Driver
    {
        $driver->update(['is_online' => $isOnline]);
        return $driver->fresh();
    }

    /**
     * الأرباح اليومية (من جدول الإحصائيات السريع)
     */
    public function getTodayEarnings(int $driverId): float
    {
        $today = Carbon::today();
        $stat = DriverDailyStat::where('driver_id', $driverId)
            ->where('stat_date', $today)
            ->first();

        return $stat ? (float) $stat->earnings : 0;
    }

    /**
     * الأرباح الكلية (من جدول السائق - مخزنة مسبقاً)
     */
    public function getTotalEarnings(int $driverId): float
    {
        return (float) DriverDailyStat::where('driver_id', $driverId)->sum('earnings');
    }

    /**
     * الطلبات المكتملة اليوم (من جدول الإحصائيات)
     */
    public function getCompletedOrdersCount(int $driverId): int
    {
        $today = Carbon::today();
        $stat = DriverDailyStat::where('driver_id', $driverId)
            ->where('stat_date', $today)
            ->first();

        return $stat ? (int) $stat->completed_orders : 0;
    }

    /**
     * التقييم العام (محسوب من جدول الإحصائيات المتراكم)
     * ملاحظة: لضمان الدقة 100% مع البيانات القديمة، يمكن جمع البيانات من هذا الجدول
     */
    public function getAverageRating(int $driverId): float
    {
        // الخيار الأفضل للأداء: جمع البيانات من جدول الإحصائيات اليومية
        $stats = DriverDailyStat::where('driver_id', $driverId)
            ->selectRaw('SUM(rating_sum) as total_sum, SUM(rating_count) as total_count')
            ->first();

        if ($stats && $stats->total_count > 0) {
            return (float) ($stats->total_sum / $stats->total_count);
        }

        return (float) Review::where('driver_id', $driverId)
            ->whereNotNull('driver_rating')
            ->avg('driver_rating');
    }

    /**
     * تقرير الأرباح الأسبوعي (7 أيام)
     */
    public function getWeeklyEarnings(int $driverId, Carbon $startDate): array
    {
        $endDate = $startDate->copy()->addDays(6);

        // جلب السجلات الموجودة فقط من قاعدة البيانات
        $stats = DriverDailyStat::where('driver_id', $driverId)
            ->whereBetween('stat_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get()
            ->keyBy('stat_date'); // مفهرسة بالتاريخ لسهولة الوصول

        $dailyData = [];
        $totalWeeklyEarnings = 0;

        for ($i = 0; $i < 7; $i++) {
            $currentDate = $startDate->copy()->addDays($i);
            $dateKey = $currentDate->format('Y-m-d');

            $dayEarnings = 0;
            $dayOrders = 0;

            if (isset($stats[$dateKey])) {
                $dayEarnings = (float) $stats[$dateKey]->earnings;
                $dayOrders = (int) $stats[$dateKey]->completed_orders;
            }

            $totalWeeklyEarnings += $dayEarnings;

            $dailyData[] = [
                'date' => $dateKey,
                'day_name' => $currentDate->format('l'),
                'earnings' => $dayEarnings,
                'completed_orders' => $dayOrders,
            ];
        }

        return [
            'total_weekly_earnings' => $totalWeeklyEarnings,
            'daily_breakdown' => $dailyData,
            'period' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
            ]
        ];
    }

    public function getTotalCompletedOrders(int $driverId): int
    {
        return (int) DriverDailyStat::where('driver_id', $driverId)->sum('completed_orders');
    }

}
