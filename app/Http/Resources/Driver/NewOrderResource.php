<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class NewOrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'order_id'       => $this->id,
            'status'         => 'جديد',
            // أرباح هذا الطلب (من جدول طلبات التوصيل الوسيط)
            'expected_profit' => $this->deliveryRequest->offered_delivery_fee ?? 0,

            // تفاصيل المسار
            'route' => [
                'pickup_point'  => $this->restaurant->name,
                'delivery_point' => $this->address->detailed_address,
            ],

            // ملخص الطلب (الأصناف)
            'order_summary' => $this->items->map(function ($item) {
                return [
                    'item' => $item->quantity . ' x ' . $item->product_name,
                ];
            }),

            'payment_method' => $this->payment_method_text, // مثلاً: كاش أو دفع إلكتروني
            'total_amount'   => $this->items->sum(fn($i) => $i->price * $i->quantity) + $this->delivery_fee,
        ];
    }
}
