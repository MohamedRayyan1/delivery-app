<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerCartResource extends JsonResource
{
    public function toArray($request): array
    {
  
        return [
            'id' => $this->id,
            'restaurant_id' => $this->restaurant_id,
            'restaurant_name' => $this->restaurant ? $this->restaurant->name : null,
            'items' => CustomerCartItemResource::collection($this->whenLoaded('items')),
            'subtotal' => (float)$this->calculated_subtotal,
            'delivery_fee' => (float)$this->calculated_delivery_fee,
            'grand_total' => (float)$this->calculated_grand_total,
        ];
    }
}
