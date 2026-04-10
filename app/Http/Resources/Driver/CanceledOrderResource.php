<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class CanceledOrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'order_id'      => $this->id,
            'status'        => 'ملغى',
            'cancel_reason' => $this->cancel_reason, // سبب الإلغاء

            'route_path' => [
                'pickup'  => $this->restaurant->name,
                'dropoff' => $this->address->title,
            ],

            'invoice_summary' => [
                'total_value' => $this->items->sum(fn($i) => $i->price * $i->quantity) + $this->delivery_fee,
            ],

            'payment_status' => $this->payment_status, // مثلاً: 'unpaid' أو 'refunded'
        ];
    }
}
