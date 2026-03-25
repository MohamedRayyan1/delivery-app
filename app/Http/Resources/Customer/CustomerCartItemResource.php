<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerCartItemResource extends JsonResource
{
    public function toArray($request): array
    {
        $price = $this->Item->discount_price ?? $this->Item->price;
        $totalPrice = $price * $this->quantity;

        return [
            'id' => $this->id,
            'item_id' => $this->item_id,
            'name' => $this->Item->name,
            'image' => $this->Item->image ? asset('storage/' . $this->Item->image) : null,
            'unit_price' => (float)$price,
            'quantity' => $this->quantity,
            'total_price' => (float)$totalPrice,
            'notes' => $this->notes,
        ];
    }
}
