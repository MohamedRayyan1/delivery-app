<?php

namespace App\Http\Resources\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;

class WeeklyComparisonResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'current_week' => number_format($this['current_week_total'], 0, '.', ',') . ' ل.س',
            'previous_week' => number_format($this['previous_week_total'], 0, '.', ',') . ' ل.س',
            'change_percentage' => ($this['change_percentage'] >= 0 ? '+' : '') .
                $this['change_percentage'] . '%',
        ];
    }
}
