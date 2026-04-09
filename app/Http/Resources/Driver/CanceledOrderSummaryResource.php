<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class CanceledOrderSummaryResource extends JsonResource
{


    public function toArray($request): array
    {
        return [
            'order_id' => $this->id,
            'status' => 'canceled',
            'route' => [
                'pickup' => [
                    'name' => $this->restaurant->name,
                    'location' => ['lat' => $this->restaurant->lat, 'lng' => $this->restaurant->lng]
                ],
                'dropoff' => [
                    'name' => $this->address->label ?? 'عنوان العميل',
                    'details' => $this->address->details
                ],
            ],
            'invoice_summary' => [
                'meals_value' => (float) $this->subtotal,
                'delivery_fee' => (float) $this->delivery_fee,
                'grand_total' => (float) $this->grand_total,
            ],
            'payment_status' => $this->payment_status, // "غير مدفوع"
        ];
    }
}
