<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverEarningsResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'today_earnings' => $this['today_earnings'],
            'week_earnings' => $this['week_earnings'],
            'total_completed_orders' => $this['total_completed_orders'],
            'chart_data' => $this['chart_data'],
            'recent_transactions' => DriverTransactionResource::collection($this['recent_transactions']),
        ];
    }
}
