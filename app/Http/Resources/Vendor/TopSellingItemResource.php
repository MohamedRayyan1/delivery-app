<?php

namespace App\Http\Resources\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;

class TopSellingItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->item_id,
            'name' => $this->item_name,
            // التأكد من مسار الصورة الصحيح حسب إعداداتك (storage أو url مباشر)
            'image' => $this->item_image ? asset('storage/' . $this->item_image) : null,
            'orders_count' => (int) $this->total_quantity,
            // القيمة المنسقة مع إشارة %
            'percentage_formatted' => $this->sales_percentage . '%',
            // القيمة كرقم صافي (تستخدمها واجهة Vue/React لرسم شريط التقدم البرتقالي)
            'percentage_value' => (float) $this->sales_percentage,
        ];
    }
}
