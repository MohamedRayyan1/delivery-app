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
            // عرض الإضافات الخاصة بهذه الوجبة كـ Snapshot
            'extras' => $this->whenLoaded('extras', function () {
                return $this->extras->map(function ($extra) {
                    return [
                        'id' => $extra->extra_id,
                        'name' => $extra->extra_name,
                        'price' => (float)$extra->extra_price,
                    ];
                });
            }),
        ];
    }
}
