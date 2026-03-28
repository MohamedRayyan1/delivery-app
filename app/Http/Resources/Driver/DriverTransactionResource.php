<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverTransactionResource extends JsonResource
{
    public function toArray($request): array
    {
        $date = clone $this->updated_at;
        $formattedDate = $date->isToday()
            ? 'Today, ' . $date->format('g:i A')
            : $date->format('M d, g:i A');

        return [
            'id' => $this->id,
            'restaurant_name' => $this->restaurant ? $this->restaurant->name : 'غير محدد',
            'amount' => (float) $this->delivery_fee,
            'status' => $this->status,
            'date_time' => $formattedDate,
        ];
    }
}
