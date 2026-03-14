<?php

namespace App\Http\Resources\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorAdResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'cost' => $this->cost,
        ];
    }
}
