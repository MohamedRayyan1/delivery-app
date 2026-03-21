<?php
// app/Services/Driver/DriverHomeService.php

namespace App\Services\Driver;

use App\Repositories\Driver\DriverHomeRepository;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Carbon;

class DriverHomeService
{
    public function __construct(protected DriverHomeRepository $repository) {}

    public function toggleOnlineStatus(int $userId, bool $isOnline): array
    {
        return DB::transaction(function () use ($userId, $isOnline) {
            $driver = $this->repository->findDriverForUpdate($userId);
            if (!$driver) {
                throw new Exception('بيانات السائق غير متوفرة.');
            }

            if ($driver->is_online === $isOnline) {
                return [
                    'is_online' => $isOnline,
                    'message' => 'الحالة بالفعل محدثة مسبقاً'
                ];
            }

            $updatedDriver = $this->repository->updateOnlineStatus($driver, $isOnline);
            return [
                'is_online' => $updatedDriver->is_online,
                'message'   => $isOnline
                    ? 'أنت الآن متصل ومستعد لاستقبال الطلبات'
                    : 'أنت الآن غير متصل'
            ];
        });
    }

    /**
     * بيانات الصفحة الرئيسية
     */
    public function getHomeData(int $userId): array
    {
        $driver = $this->repository->findDriverByUserId($userId);
        if (!$driver) {
            throw new Exception('السائق غير موجود');
        }

        return [
            'is_online' => (bool) $driver->is_online,
            'earnings_today' => $this->repository->getTodayEarnings($driver->id),
            'completed_orders_today' => $this->repository->getCompletedOrdersCount($driver->id),
            'total_completed_orders' => $this->repository->getTotalCompletedOrders($driver->id), // الجديد
            'rating' => round($this->repository->getAverageRating($driver->id), 1),
            'total_earnings' => $this->repository->getTotalEarnings($driver->id),
        ];
    }

    /**
     * تقرير الأرباح الأسبوعي
     */
    public function getWeeklyReport(int $userId, ?string $startDateStr = null): array
    {
        $driver = $this->repository->findDriverByUserId($userId);
        if (!$driver) {
            throw new Exception('السائق غير موجود');
        }

        $startDate = $startDateStr
            ? Carbon::parse($startDateStr)->startOfDay()
            : Carbon::today()->startOfWeek();

        return $this->repository->getWeeklyEarnings($driver->id, $startDate);
    }
}
