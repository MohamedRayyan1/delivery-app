<?php

namespace App\Http\Resources\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorMenuCategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        $items = collect();

        if ($this->relationLoaded('subSections')) {
            foreach ($this->subSections as $subSection) {
                foreach ($subSection->items as $item) {
                    $items->push($item);
                }
            }
        }

        return [
            'section_id' => $this->id,
            'section_name' => $this->name,
            'items' => VendorMenuItemResource::collection($items),
        ];
    }
}
