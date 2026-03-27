<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderTrackingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'order_id'   => $this->id,
            'status'     => $this->status,
            'delivery_confirmation_code' => $this->delivery_confirmation_code,

            // تفاصيل المطعم
            'restaurant' => [
                'name' => $this->restaurant->name ?? 'غير محدد',
                'logo' => $this->restaurant->logo ? asset('storage/' . $this->restaurant->logo) : null,
            ],

            // ملخص الوجبات (مثل: وجبة شاورما، مقبلات...)
            'order_details' => $this->items->map(function($item) {
                return $item->Item->name;
            })->implode('، '),

            // بيانات السائق
            'driver' => $this->when($this->driver_id && $this->driver, function() {
                return [
                    'name'  => $this->driver->user->name ?? null,
                    'phone' => $this->driver->user->phone ?? null,
                    'current_location' => [
                        'lat' => (float) ($this->driver->current_lat ?? 0),
                        'lng' => (float) ($this->driver->current_lng ?? 0),
                    ],
                ];
            }),
        ];
    }


}
