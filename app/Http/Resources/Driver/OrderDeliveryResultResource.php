<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderDeliveryResultResource extends JsonResource
{
    public function toArray($request): array
    {
        // الوصول للطلب الأساسي المرتبط
        $order = $this->order;

        return [
            'request_id'      => $this->id,
            'order_id'        => $this->order_id,
            'status'          => $this->status,
            'delivered_at'    => now()->toDateTimeString(),

            'financial_summary' => [

                // ربح السائق الصافي (من جدول delivery_requests)
                'driver_profit'     => (float) $this->offered_delivery_fee,

                // قيمة الأوردر (سعر الوجبات فقط)
                'items_subtotal'     => (float) $order->subtotal,

                // رسوم التوصيل الكلية (المسجلة في الأوردر)
                'total_delivery_fee' => (float) $order->delivery_fee,

                // المجموع الكلي (المبلغ الذي دفعه أو سيفتعه الزبون شامل كل شيء)
                'grand_total'        => (float) $order->grand_total,
            ],

        ];
    }
}
