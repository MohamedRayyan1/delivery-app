<?php

namespace App\Http\Resources\Vendor;

use Illuminate\Http\Resources\Json\JsonResource;

class MonthlySalesResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'month' => $this['month'],
            'total' => number_format($this['total'], 0, '.', ',') . ' ل.س',
        ];
    }
}
