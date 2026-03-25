<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerOrderListResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'restaurant_name' => $this->restaurant ? $this->restaurant->name : null,
            'restaurant_logo' => ($this->restaurant && $this->restaurant->logo) ? asset('storage/' . $this->restaurant->logo) : null,
            'status' => $this->status,
            'grand_total' => (float)$this->grand_total,
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
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
