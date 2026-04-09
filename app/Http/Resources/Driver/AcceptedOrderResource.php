<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class AcceptedOrderResource extends JsonResource
{
    /**
     * تحويل الكائن إلى مصفوفة JSON مستقلة
     */
    public function toArray($request): array
    {
        // نصل للطلب عبر العلاقة المحملة في الـ Repository
        $order = $this->order;
        $restaurant = $order->restaurant;
        $address = $order->address;

        return [
            'order_id'         => $order->id,
            'restaurant_name'  => $restaurant->name ?? 'غير محدد',
            'restaurant_lat'   => (float) $restaurant->lat,
            'restaurant_lng'   => (float) $restaurant->lng,
            'delivery_address' => $address->street ?? $address->label ?? 'عنوان الزبون',
        ];
    }
}
