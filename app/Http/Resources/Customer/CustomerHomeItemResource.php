<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerHomeItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'restaurant_name' => $this->whenLoaded('subSection', function () {
                // نعرض اسم أول مطعم مرتبط بالقسم (إن وجد)
                $section = $this->subSection->section;
                $restaurant = $section->restaurants->first() ?? $section->restaurant;
                return $restaurant?->name;
            }),
        ];
    }
}
