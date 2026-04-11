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
}
