<?php

namespace App\Http\Resources\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportCardResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'title' => $this['title'],
            'value' => $this['is_currency']
                ? number_format($this['value'], 0) . ' ل.س'
                : number_format($this['value'], 0),
            'growth' => [
                'percentage' => ($this['growth'] >= 0 ? '+' : '') . $this['growth'] . '%',
                'is_positive' => $this['growth'] >= 0, // لتحديد لون السهم (أخضر/أحمر)
                'status' => $this['growth'] >= 0 ? 'increase' : 'decrease'
            ]
        ];
    }
}
