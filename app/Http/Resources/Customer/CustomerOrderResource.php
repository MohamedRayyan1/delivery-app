<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerOrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'restaurant_name' => $this->restaurant ? $this->restaurant->name : null,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'subtotal' => (float)$this->subtotal,
            'delivery_fee' => (float)$this->delivery_fee,
            'discount_amount' => (float)$this->discount_amount,
            'grand_total' => (float)$this->grand_total,
            'created_at' => $this->created_at->toDateTimeString(),

            // إضافة العنوان هنا
            // سيظهر فقط إذا قمت بعمل load('address') في الـ Repository
            'address' => $this->whenLoaded('address', function() {
                return [
                    'label' => $this->address->label,
                    'street' => $this->address->street,
                    'details' => $this->address->details,
                    'floor' => $this->address->floor,
                    'phone' => $this->address->phone,
                    'lat' => (float)$this->address->lat,
                    'lng' => (float)$this->address->lng,
                ];
            }),

            'items' => CustomerOrderItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
