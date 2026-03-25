<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminCouponResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'discount_type' => $this->discount_type,
            'value' => (float)$this->value,
            'min_order_price' => $this->min_order_price ? (float)$this->min_order_price : null,
            'expiry_date' => $this->expiry_date->toDateTimeString(),
            'usage_limit' => $this->usage_limit,
            'is_valid' => $this->isValid(),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
