<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class PendingOrderSummaryResource extends JsonResource
{
    public function toArray($request): array
    {
        // 1. جلب سجل طلب التوصيل المرتبط بالطلب الأساسي
        $deliveryRequest = $this->deliveryRequest;

        return [
            'order_id' => $this->id,

            // أرباح الديلفري الصافية للسائق من هذا الأوردر
            'delivery_profit' => (float) ($deliveryRequest->offered_delivery_fee ?? 0),

            'route' => [
                'pickup' => [
                    'name' => $this->restaurant->name,
                    'location' => [
                        'lat' => (float) $this->restaurant->lat,
                        'lng' => (float) $this->restaurant->lng,
                    ],
                    'address_details' => $this->restaurant->city ?? '',
                ],
                'dropoff' => [
                    'customer_name' => $this->user->name,
                    'location' => [
                        'lat' => (float) $this->address->lat,
                        'lng' => (float) $this->address->lng,
                    ],
                    'address_details' => [
                        'label' => $this->address->label, // منزل، عمل..
                        'street' => $this->address->street,
                        'building_details' => $this->address->details, // رقم البناية/الشقة
                        'floor' => $this->address->floor,
                    ],
                ],
            ],

            // تفاصيل الوجبات (Items)
            'order_items' => $this->items->map(fn($item) => [
                'name' => $item->item->name ?? 'وجبة',
                'quantity' => $item->quantity,
                'price' => (float) $item->unit_price,
                // إذا كان هناك إضافات (Extras) للوجبة
                'extras' => $item->extras->map(fn($extra) => [
                    'name' => $extra->extra_item_name,
                    'price' => (float) $extra->price,
                ]),
            ]),

            'payment' => [
                'method' => $this->payment_method, // "نقداً" أو "أونلاين"
                'status' => $this->payment_status,
                'total_to_collect' => (float) $this->grand_total, // المبلغ الذي سيقبضه السائق من الزبون
            ],

            'order_date' => $this->created_at->toDateTimeString(),
        ];
    }
}
