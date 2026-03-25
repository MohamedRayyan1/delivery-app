<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerOrderItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'item_name' => $this->Item ? $this->Item->name : 'Unknown Item',
            'quantity' => $this->quantity,
            'unit_price' => (float)$this->unit_price,
            'total_price' => (float)$this->total_price,
        ];
    }
}
