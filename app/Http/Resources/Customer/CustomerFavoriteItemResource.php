<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerFavoriteItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float)$this->price,
            'discount_price' => $this->discount_price ? (float)$this->discount_price : null,
            'image' => $this->image ? asset('storage/' . $this->image) : null,

        ];
    }
}
