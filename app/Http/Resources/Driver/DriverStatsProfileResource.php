<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverStatsProfileResource extends JsonResource
{
    public function toArray($request): array
    {
        $governorate = $this->user->governorate ?? '';
        $city = $this->user->city ?? '';
        $address = trim($governorate . '، ' . $city, '، ');

        return [
            'id' => $this->id,
            'name' => $this->user->name,
            'address' => $address !== '' ? $address : 'غير محدد',
            'is_online' => (bool)$this->is_online,
            'total_orders' => $this->orders_count ?? 0,
            'average_rating' => round($this->average_rating ?? 0, 1),
            'today_earnings' => (float)($this->today_earnings ?? 0),
            'reviews' => DriverReviewResource::collection($this->whenLoaded('reviews')),
        ];
    }
}
