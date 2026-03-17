<?php

namespace App\Services\Driver;

use App\Repositories\Driver\DriverHomeRepository;
use Illuminate\Support\Facades\DB;
use Exception;

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

            // // تحقق من حالة الحساب
            // if (isset($driver->status) && $driver->status !== 'approved') {
            //     throw new Exception('لا يمكنك تغيير الحالة قبل تفعيل الحساب.');
            // }


            // منع التحديث إذا نفس القيمة
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
     * 🏠 بيانات الصفحة الرئيسية
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
            'completed_orders' => $this->repository->getCompletedOrdersCount($driver->id),
            'rating' => round($this->repository->getAverageRating($driver->id), 1),
        ];
    }
}
