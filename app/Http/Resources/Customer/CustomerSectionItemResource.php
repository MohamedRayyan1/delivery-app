<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerSectionItemResource extends JsonResource
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
            'is_favorite' => (bool) $this->is_fav_id, // إذا الحقل موجود يعني true
            'extras' => $this->whenLoaded('extras', function () {
                return $this->extras->map(function ($extra) {
                    return [
                        'id' => $extra->id,
                        'name' => $extra->name,
                        'price' => (float) $extra->price
                    ];
                });
            }),

        ];
    }
}
