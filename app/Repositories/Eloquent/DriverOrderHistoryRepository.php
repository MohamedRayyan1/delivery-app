<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;

class DriverOrderHistoryRepository
{
    public function getOrdersHistory(int $driverId, ?string $status, ?string $search, int $perPage)
    {
        return Order::select('id', 'restaurant_id', 'status', 'grand_total', 'created_at')
            ->with(['restaurant:id,name,logo'])
            ->where('driver_id', $driverId)
            ->when($status, function ($query, $status) {
                if ($status === 'new') {
                    // حالات الطلب الجديد أو قيد التوصيل
                    $query->whereNotIn('status', ['delivered', 'cancelled']);
                } else {
                    $query->where('status', $status);
                }
            })
            ->when($search, function ($query, $search) {
                // البحث بجزء من رقم الطلب (ID)
                $query->where('id', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->cursorPaginate($perPage);
    }
}
