<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverOrderHistoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'restaurant_name' => $this->restaurant ? $this->restaurant->name : 'غير محدد',
            'restaurant_logo' => ($this->restaurant && $this->restaurant->logo) ? asset('storage/' . $this->restaurant->logo) : null,
            'status' => $this->status, // Frontend can translate this (e.g. delivered -> مكتمل)
            'grand_total' => (float) $this->grand_total,
            'date_formatted' => $this->created_at->format('d M, h:i A'),
        ];
    }
}
