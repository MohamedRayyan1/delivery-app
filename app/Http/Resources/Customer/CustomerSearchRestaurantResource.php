<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;
class CustomerSearchRestaurantResource extends JsonResource
{
    public function toArray($request): array
    {
        $matchedItems = collect();

        // الوصول للعلاقة الصحيحة subMenuSections التي تم تحميلها في الـ Repository
        if ($this->relationLoaded('subMenuSections')) {
            foreach ($this->subMenuSections as $subSection) {
                // ندمج الوجبات الموجودة في كل قسم فرعي
                if ($subSection->relationLoaded('items')) {
                    $matchedItems = $matchedItems->merge($subSection->items);
                }
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'logo' => $this->logo ? asset('storage/' . $this->logo) : null,
            'delivery_cost' => (float)$this->delivery_cost,
            'delivery_time' => $this->delivery_time,
            'rating' => (float)$this->rating,
            'matched_items' => CustomerSectionItemResource::collection($matchedItems),
        ];
    }
}
