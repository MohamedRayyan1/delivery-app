<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverOrderHistoryResource extends JsonResource
{
    public function toArray($request): array
    {
        // 1. جلب العلاقات بأمان لتجنب أخطاء النل (Null Reference)
        $order = $this->order;
        $restaurant = $order ? $order->restaurant : null;

        return [
            // ملاحظة: الـ Frontend غالباً يهمّه رقم الطلب وليس رقم رحلة التوصيل
            'id' => $this->order_id,
            'delivery_request_id' => $this->id, // رقم الرحلة الداخلي (اختياري)

            // 2. الوصول لبيانات المطعم عبر علاقة الطلب
            'restaurant_name' => $restaurant ? $restaurant->name : 'غير محدد',
            'restaurant_logo' => ($restaurant && $restaurant->logo) ? asset('storage/' . $restaurant->logo) : null,

            // 3. حالة رحلة التوصيل (من جدول delivery_requests)
            'status' => $this->status,

            // 4. الأرقام المالية
            'grand_total' => $order ? (float) $order->grand_total : 0, // إجمالي فاتورة الزبون
            'delivery_fee' => (float) $this->offered_delivery_fee,     // أتعاب السائق الصافية

            // 5. وقت إنشاء طلب التوصيل
            'date_formatted' => $this->created_at->format('d M, h:i A'),
        ];
    }
}
