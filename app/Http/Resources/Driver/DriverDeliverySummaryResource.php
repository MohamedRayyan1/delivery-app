<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverDeliverySummaryResource extends JsonResource
{
    public function toArray($request): array
    {
        // تجهيز العنوان (شارع، مدينة)
        $addressParts = array_filter([$this->address->street ?? '', $this->address->city ?? '']);
        $dropoffName = implode('، ', $addressParts);

        return [
            'id' => $this->id,
            'customer_rating' => $this->review ? (float) $this->review->driver_rating : null,
            'net_profit' => (float) $this->delivery_fee,
            'route' => [
                'pickup' => [
                    'name' => $this->restaurant ? $this->restaurant->name : 'غير محدد',
                    'time' => $this->created_at->format('h:i A'),
                ],
                'dropoff' => [
                    'name' => $dropoffName !== '' ? $dropoffName : 'عنوان غير محدد',
                    'time' => $this->updated_at->format('h:i A'),
                ],
            ],
            'invoice' => [
                'meals_value' => (float) $this->subtotal,
                'delivery_fee' => (float) $this->delivery_fee,
                'payment_method' => $this->payment_method,
                'grand_total' => (float) $this->grand_total,
            ],
        ];
    }
}
