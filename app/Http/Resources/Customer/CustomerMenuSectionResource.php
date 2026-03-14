<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerMenuSectionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'sub_sections' => CustomerSubMenuSectionResource::collection($this->whenLoaded('subSections')),
        ];
    }
}
