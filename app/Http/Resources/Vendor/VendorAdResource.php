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

        $statusBadge = '';
        $isActiveToggle = false;

        switch ($this->status) {
            case 'pending':
                $statusBadge = 'قيد المراجعة';
                $isActiveToggle = false;
                break;

            case 'waiting_payment':
                $statusBadge = 'بانتظار الدفع';
                $isActiveToggle = false;
                break;

            case 'approved':
                if ($now->between($startDate, $endDate)) {
                    if ($this->is_active) {
                        $statusBadge = 'نشط الآن';
                        $isActiveToggle = true;
                    } else {
                        $statusBadge = 'متوقف مؤقتاً';
                        $isActiveToggle = false;
                    }
                } elseif ($now->lt($startDate)) {
                    $statusBadge = 'مجدول';
                    $isActiveToggle = true;
                } else {
                        $statusBadge = 'منتهي';
                    $isActiveToggle = false;
                }
                break;

            case 'expired':
            default:
                $statusBadge = 'منتهي';
                $isActiveToggle = false;
                break;
        }

        $formattedStartDate = $startDate->translatedFormat('d F Y');
        $formattedEndDate = $endDate->translatedFormat('d F Y');

        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'date_range' => "{$formattedStartDate} - {$formattedEndDate}",
            'status_badge' => $statusBadge,
            'is_active' => $isActiveToggle,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
        ];
    }
}
