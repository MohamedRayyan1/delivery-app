<?php

namespace App\Http\Resources\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;

class PerformanceChartResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'label' => $this['label'], // اسم الشهر أو اليوم
            'amount' => (float) $this['value'],
            'amount_formatted' => number_format($this['value'], 0) . ' ل.س'
        ];
    }
}
