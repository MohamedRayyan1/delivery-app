<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerHomeRestaurantResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'logo' => $this->logo ? asset('storage/' . $this->logo) : null,
            'cover_image' => $this->cover_image ? asset('storage/' . $this->cover_image) : null,
            'delivery_cost' => $this->delivery_cost,
            'delivery_time' => $this->delivery_time,
            'status' => $this->status,
        ];
    }
}
