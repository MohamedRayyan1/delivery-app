<?php

namespace App\Http\Resources\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorOrderItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'name' => $this->Item ? $this->Item->name : 'Unknown Item',
            'quantity' => $this->quantity,
        ];
    }
}
