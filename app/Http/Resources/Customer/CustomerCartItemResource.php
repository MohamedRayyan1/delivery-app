<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerCartItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'cart_item_id' => $this->id,
            'item_id' => $this->item_id,
            'name' => $this->Item ? $this->Item->name : 'وجبة محذوفة',
            'image' => ($this->Item && $this->Item->image) ? asset('storage/' . $this->Item->image) : null,
            'quantity' => $this->quantity,
            'unit_price' => (float)($this->calculated_item_price ?? 0),
            'total_price' => (float)($this->calculated_total_price ?? 0),
            'notes' => $this->notes,
            'extras' => $this->whenLoaded('extras', function () {
                return $this->extras->map(function ($extra) {
                    return [
                        'id' => $extra->id,
                        'name' => $extra->name,
                        'price' => (float)$extra->price,
                    ];
                });
            }),
        ];
    }
}
