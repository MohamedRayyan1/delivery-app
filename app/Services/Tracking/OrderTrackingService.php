<?php

namespace App\Services\Tracking;

use App\Repositories\Eloquent\DriverTrackingRepository;
use Exception;

class OrderTrackingService
{
    protected $repository;

    public function __construct(DriverTrackingRepository $repository)
    {
        $this->repository = $repository;
    }

    // دالة تحديث الموقع (يستدعيها تطبيق السائق)
    public function updateDriverLocation(int $driverId, int $orderId, float $lat, float $lng): void
    {
        // 1. التحقق الأمني: هل هذا السائق مكلف فعلاً بهذا الطلب؟ وهل الطلب نشط؟
        $isValid = $this->repository->verifyDriverOrder($driverId, $orderId);

        if (!$isValid) {
            throw new Exception('غير مصرح: السائق غير مرتبط بهذا الطلب أو الطلب غير نشط.');
        }

        // 2. تحديث الموقع في الذاكرة (Redis / Cache)
        $this->repository->updateLiveLocation($driverId, $lat, $lng);
    }

    // دالة جلب الموقع (يستدعيها تطبيق الزبون/المطعم)
    public function getDriverLocation(int $driverId, int $orderId): array
    {
        // 1. التحقق الأمني
        $isValid = $this->repository->verifyDriverOrder($driverId, $orderId);

        if (!$isValid) {
            throw new Exception('التتبع غير متاح: الطلب منتهي أو البيانات غير متطابقة.');
        }

        // 2. جلب الموقع من الذاكرة
        $location = $this->repository->getLiveLocation($driverId);

        if (!$location) {
            throw new Exception('لا يوجد اتصال حالي مع السائق (Offline).');
        }

        return $location;
    }
}
