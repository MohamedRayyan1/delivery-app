<?php

namespace App\Repositories\Eloquent;

use App\Models\CustomerProfile;
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
    public function acceptDeliveryRequest(int $requestId, int $driverId)
    {
        return DB::transaction(function () use ($requestId, $driverId) {
            $hasActiveOrder = DeliveryRequest::where('driver_id', $driverId)
                ->whereIn('status', ['accepted', 'picked_up'])
                ->exists();

            if ($hasActiveOrder) {
                return false;
            }

            // جلب الطلب والتأكد من حالته
            $deliveryRequest = DeliveryRequest::where('id', $requestId)
                ->where('status', 'pending')
                ->whereNull('driver_id')
                ->first();

            if (!$deliveryRequest) {
                return false;
            }

            // التحديث
            $deliveryRequest->update([
                'driver_id' => $driverId,
                'status'    => 'accepted'
            ]);

            Order::where('id', $deliveryRequest->order_id)->update([
                'driver_id' => $driverId,
            ]);

            // جلب العلاقات اللازمة للـ Resource وإرجاع الكائن
            return $deliveryRequest->load(['order.restaurant', 'order.address']);
        });
    }

    public function getDeliveredOrderSummary(int $driverId, int $orderId)
    {
        return Order::with([
            'restaurant:id,name',
            'address',
            'review' => function ($query) use ($driverId) {
                $query->where('driver_id', $driverId)->select('id', 'order_id', 'driver_rating');
            }
        ])
            ->where('id', $orderId)
            ->where('driver_id', $driverId)
            ->firstOrFail();
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

            // 3. جلب الطلب الأساسي لتحديثه ومعرفة صاحب الطلب
            $order = Order::find($deliveryRequest->order_id);

            if ($order) {
                // تحديث الجدول الرئيسي (orders) وتوثيق وقت الوصول
                $order->update([
                    'status'         => 'delivered',
                    'delivered_at'   => now(), // توثيق الوقت اللحظي
                    'payment_status' => 'paid' // تحديث حالة الدفع عند التسليم
                ]);

                // 4. إضافة نقطة إضافية للعميل
                CustomerProfile::firstOrCreate(
                    ['user_id' => $order->user_id],
                    ['points'  => 0]
                )->increment('points');
            }

            return $deliveryRequest;
        });
    }

    public function getOrderDetailsForSummary(int $orderId)
    {
        return Order::with([
            'restaurant:id,name,lat,lng,city',
            'address',
            'items.item:id,name', // جلب أسماء العناصر في الفاتورة
            'review',
            'deliveryRequest' // لجلب الربح المعروض للسائق
        ])->findOrFail($orderId);
    }
}
