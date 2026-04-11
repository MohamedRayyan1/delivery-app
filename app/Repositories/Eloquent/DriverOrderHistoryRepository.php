<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;

class DriverOrderHistoryRepository
{
public function getOrdersHistory(int $driverId, ?string $status, ?string $search, int $perPage)
    {
        // نستعلم من مودل طلبات التوصيل لأنه الأساس الآن
        return \App\Models\DeliveryRequest::select('id', 'order_id', 'driver_id', 'status', 'offered_delivery_fee', 'created_at')
            ->with([
                // جلب بيانات الطلب الأساسية مع المطعم المرتبط به
                'order:id,restaurant_id,grand_total,created_at',
                'order.restaurant:id,name,logo'
            ])
            ->where('driver_id', $driverId)
            ->when($status, function ($query, $status) {
                if ($status === 'pending') {
                    // "pending" هنا تعني للواجهة الأمامية "الطلبات النشطة"
                    // لذلك نجلب كل الحالات ما عدا "تم التوصيل"
                    $query->whereNotIn('status', ['delivered']);
                } else {
                    // البحث بحالة محددة (accepted, picked_up, delivered)
                    $query->where('status', $status);
                }
            })
            ->when($search, function ($query, $search) {
                // استعلام صاروخي لأن order_id موجود في نفس الجدول (لا داعي لـ JOIN أو whereHas)
                $query->where('order_id', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->cursorPaginate($perPage);
    }
}
