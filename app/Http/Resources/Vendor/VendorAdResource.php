<?php

namespace App\Http\Resources\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class VendorAdResource extends JsonResource
{
    public function toArray($request): array
    {
        $now = Carbon::now();
        $startDate = Carbon::parse($this->start_date);
        $endDate = Carbon::parse($this->end_date);

        // 1. حساب حالة البادج (Badge) وزر التفعيل (Toggle)
        $statusBadge = 'منتهي';
        $isActiveToggle = false;

        // بافتراض أن حقل status في الداتا بيس يخزن (active/inactive) أو (1/0)
        if ($this->status === 'active' || $this->status == 1) {
            if ($now->between($startDate, $endDate)) {
                $statusBadge = 'نشط الآن';
                $isActiveToggle = true;
            } elseif ($now->lt($startDate)) {
                $statusBadge = 'مجدول';
                $isActiveToggle = true;
            } else {
                $statusBadge = 'منتهي';
                $isActiveToggle = false;
            }
        } else {
            $statusBadge = 'منتهي'; // إذا قام صاحب المطعم بإيقافه يدوياً
            $isActiveToggle = false;
        }

        // 2. تنسيق التاريخ باللغة العربية
        // ملاحظة: تأكد من أن 'locale' => 'ar' في ملف config/app.php
        $formattedStartDate = $startDate->translatedFormat('d F Y');
        $formattedEndDate = $endDate->translatedFormat('d F Y');

        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'image' => $this->image ? asset('storage/' . $this->image) : null,

            // الحقول المهيأة خصيصاً للواجهة المرفقة
            'date_range' => "{$formattedStartDate} - {$formattedEndDate}",
            'status_badge' => $statusBadge,
            'is_active' => $isActiveToggle,

            // نحتفظ بالتواريخ الأصلية لاحتمالية استخدامها في شاشة "تعديل الإعلان"
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            // 'cost' => (float) $this->cost,
        ];
    }
}
