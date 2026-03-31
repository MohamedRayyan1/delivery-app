<?php

namespace App\Http\Resources\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderListItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'order_number' => '#' . $this->id, // عرض المعرف كـ "رقم طلب"
            'customer_name' => $this->user->name ?? 'زبون غير معروف',
            'order_date' => $this->created_at->format('Y-m-d H:i'),
            'time_ago' => $this->created_at->diffForHumans(), // مثل: "منذ دقيقتين"
            'subtotal' => (float) $this->subtotal,
            'subtotal_formatted' => number_format($this->subtotal, 0) . ' ل.س',
            'status' => $this->status,
            'status_label' => $this->getStatusLabel($this->status),
        ];
    }

    // دالة مساعدة لتحويل الحالة لنص مفهوم للواجهة
    private function getStatusLabel($status)
    {
        $labels = [
            'pending'   => 'قيد الانتظار',
            'preparing' => 'قيد التحضير',
            'picked_up' => 'مع السائق',
            'delivered' => 'تم التسليم',
        ];

        return $labels[$status] ?? $status;
    }
}
