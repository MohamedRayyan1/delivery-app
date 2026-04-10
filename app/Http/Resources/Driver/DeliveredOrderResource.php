<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class DeliveredOrderResource extends JsonResource
{
    public function toArray($request)
    {
        $itemsTotal = $this->items->sum(fn($i) => $i->price * $i->quantity);

        return [
            'order_id'   => $this->id,
            'status'     => 'تم التوصيل',
            'net_profit' => $this->deliveryRequest->offered_delivery_fee ?? 0,

            'route_details' => [
                'from' => $this->restaurant->name,
                'to'   => $this->address->title,
                'picked_up_at' => $this->picked_up_at->format('h:i A'),
                'delivered_at' => $this->delivered_at->format('h:i A'),
            ],

            'invoice_details' => [
                'sub_total'    => $itemsTotal,
                'delivery_fee' => $this->delivery_fee,
                'total_paid'   => $itemsTotal + $this->delivery_fee,
            ],

            'customer_feedback' => $this->review->driver_rating ?? 'لا يوجد تقييم بعد',
        ];
    }
}
