<?php

namespace App\Repositories\Eloquent;

use App\Models\DeliveryRequest;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class HomePageRepository
{
    // في ملف HomePageRepository.php
    public function getAvailableDeliveryRequestsByCity(string $city, string $vehicleType, array $excludedIds = [])
    {
        return DeliveryRequest::query()
            ->where('status', 'pending')
            ->whereNull('driver_id')
            // استبعاد الطلبات التي رفضها السائق يدوياً
            ->whereNotIn('id', $excludedIds)
            ->where('required_vehicle_type', $vehicleType)
            ->whereHas('order.restaurant', function ($query) use ($city) {
                $query->where('city', $city);
            })
            ->with(['order.restaurant', 'order.address'])
            ->latest()
            ->get();
    }

    /**
     * قبول طلب التوصيل وتحديث جدول الطلبات الأساسي
     */
    public function acceptDeliveryRequest(int $requestId, int $driverId): bool
    {
        return DB::transaction(function () use ($requestId, $driverId) {

            $hasActiveOrder = DeliveryRequest::where('driver_id', $driverId)
                ->whereIn('status', ['accepted', 'picked_up'])
                ->exists();

            if ($hasActiveOrder) {
                return false;
            }
            $updated = DeliveryRequest::where('id', $requestId)
                ->where('status', 'pending')
                ->whereNull('driver_id')
                ->update([
                    'driver_id' => $driverId,
                    'status'    => 'accepted'
                ]);

            if ($updated === 0) {
                return false;
            }
            $orderId = DeliveryRequest::where('id', $requestId)->value('order_id');

            Order::where('id', $orderId)->update([
                'driver_id' => $driverId,
            ]);

            return true;
        });
    }



    // HomePageRepository.php

    public function markAsPickedUp(int $requestId, int $driverId, string $invoicePath)
    {
        $deliveryRequest = DeliveryRequest::where('id', $requestId)
            ->where('driver_id', $driverId)
            ->where('status', 'accepted')
            ->first();

        if (!$deliveryRequest) {
            return null;
        }

        // تحديث الوسيط
        $deliveryRequest->update([
            'status'        => 'picked_up',
            'invoice_image' => $invoicePath
        ]);

        // تحديث الطلب الأساسي
        Order::where('id', $deliveryRequest->order_id)->update([
            'status'       => 'picked_up',
            'picked_up_at' => now(), //
        ]);

        return $deliveryRequest;
    }


    // HomePageRepository.php

    public function markAsDelivered(int $requestId, int $driverId)
    {
        return DB::transaction(function () use ($requestId, $driverId) {
            // 1. جلب سجل طلب التوصيل والتأكد من ملكيته للسائق وحالته الحالية
            $deliveryRequest = DeliveryRequest::where('id', $requestId)
                ->where('driver_id', $driverId)
                ->where('status', 'picked_up')
                ->first();

            if (!$deliveryRequest) {
                return null;
            }

            // 2. تحديث حالة طلب التوصيل (الوسيط)
            $deliveryRequest->update([
                'status' => 'delivered'
            ]);

            // 3. تحديث الجدول الرئيسي (orders) وتوثيق وقت الوصول
            Order::where('id', $deliveryRequest->order_id)->update([
                'status'       => 'delivered',
                'delivered_at' => now(), // توثيق الوقت اللحظي
                'payment_status' => 'paid' // تحديث حالة الدفع عند التسليم
            ]);

            return $deliveryRequest;
        });
    }
}
