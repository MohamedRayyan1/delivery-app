<?php

namespace App\Services\Vendor;

use App\Repositories\Eloquent\VendorOrderRepository;
use Exception;

class VendorOrderService
{
    protected $repository;

    public function __construct(VendorOrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getOrdersList(int $restaurantId, array $filters)
    {
        $status = $filters['status'] ?? null;
        $search = $filters['search'] ?? null;
        $perPage = $filters['per_page'] ?? 15;

        return $this->repository->getRestaurantOrders($restaurantId, $status, $search, $perPage);
    }

    public function acceptOrder(int $restaurantId, int $orderId)
    {
        // 1. جلب الطلب (التأكد الأمني من أن الطلب يخص هذا المطعم حصراً)
        $order = $this->repository->findByIdAndRestaurant($orderId, $restaurantId);

        // 2. التحقق من حالة الطلب (Business Logic Rule)
        if ($order->status !== 'pending') {
            throw new Exception('لا يمكن قبول هذا الطلب. حالته الحالية ليست قيد الانتظار.');
        }

        // 3. تحديث الحالة في قاعدة البيانات
        $this->repository->updateStatus($orderId, 'preparing');

        // 4. تحديث الكائن الحالي وإعادته للكنترولر
        $order->status = 'preparing';

        return $order;
    }


    public function requestDriver(int $restaurantId, int $orderId, ?string $vehicleType)
    {
        // جلب الطلب مع التحقق من الملكية والحالة
        $order = $this->repository->findPreparingOrder($orderId, $restaurantId);

        if (!$order) {
            throw new Exception('لا يمكن طلب سائق لهذا الطلب؛ تأكد أن حالة الطلب "قيد التحضير".');
        }

        // التحقق من عدم وجود طلب توصيل مسبق لنفس الأوردر
        if ($order->deliveryRequest()->exists()) {
            throw new Exception('لقد قمت بطلب سائق لهذا الطلب بالفعل.');
        }

        return $this->repository->createDeliveryRequest([
            'order_id'              => $order->id,
            'offered_delivery_fee'  => $order->driver_earnings, // تم الحساب هنا عبر الـ Accessor في الموديل
            'required_vehicle_type' => $vehicleType,
            'status'                => 'pending'
        ]);
    }
}
