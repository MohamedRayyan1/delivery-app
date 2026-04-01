<?php

namespace App\Http\Resources\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorOrderResource extends JsonResource
{
    public function toArray($request): array
    {
        $statusAr = match($this->status) {
            'pending' => 'طلب جديد',
            'preparing' => 'قيد التحضير',
            'waiting_driver' => 'بانتظار السائق',
            'delivered' => 'مكتمل',
            'cancelled' => 'ملغي',
            default => 'غير محدد',
        };

        return [
            'order_id' => $this->id,
            'customer' => [
                'name' => $this->user ? $this->user->name : 'عميل غير معروف',
                'phone' => $this->user ? $this->user->phone : null,
            ],
            'items' => VendorOrderItemResource::collection($this->whenLoaded('items')),
            'status' => $this->status,
            'status_ar' => $statusAr,
            'grand_total' => (float) $this->grand_total,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
