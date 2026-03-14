<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerRestaurantDetailsResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'governorate' => $this->governorate,
            'city' => $this->city,
            'description' => $this->description,
            'logo' => $this->logo ? asset('storage/' . $this->logo) : null,
            'cover_image' => $this->cover_image ? asset('storage/' . $this->cover_image) : null,
            'delivery_cost' => $this->delivery_cost,
            'min_order_price' => $this->min_order_price,
            'delivery_time' => $this->delivery_time,
            'is_featured' => (bool)$this->is_featured,
            'menu' => CustomerMenuSectionResource::collection($this->whenLoaded('sections')),
        ];
    }
}
