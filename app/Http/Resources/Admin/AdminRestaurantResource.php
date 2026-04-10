<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\Customer\CustomerMenuSectionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminRestaurantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'manager_user_id' => $this->manager_user_id,
            'name' => $this->name,
            'governorate' => $this->governorate,
            'city' => $this->city,
            'status' => $this->status,
            'sections' => CustomerMenuSectionResource::collection($this->whenLoaded('sections')),
            'logo' => $this->logo ? asset('storage/' . $this->logo) : null,
            'cover_image' => $this->cover_image ? asset('storage/' . $this->cover_image) : null,
            'description' => $this->description,
            'rating' => $this->rating,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'delivery_cost' => $this->delivery_cost,
            'min_order_price' => $this->min_order_price,
            'delivery_time' => $this->delivery_time,
            'is_featured' => (bool)$this->is_featured,
        ];
    }
}
