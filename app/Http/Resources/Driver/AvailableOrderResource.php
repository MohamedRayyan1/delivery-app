<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class AvailableOrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'request_id'         => $this->id, // ID جدول طلبات التوصيل

            'order_id'           => $this->order_id,
            'restaurant'         => $this->order->restaurant->name,
            'restaurant_address' => $this->order->restaurant->city . ' - ' . $this->order->restaurant->governorate,
            'distance_km'        => round($this->distance_km, 2),
            
            'driver_profit'      => (float) $this->offered_delivery_fee,
            'duration_minutes'   => $this->duration_minutes ?? 0,
            'status'             => $this->status,
            'order_time'         => $this->created_at ? $this->created_at->diffForHumans() : 'غير معروف',
        ];
    }
}
