<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverReviewResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'customer_name' => $this->user ? $this->user->name : 'عميل',
            'rating' => (float)$this->driver_rating,
            'comment' => $this->comment,
            'date' => $this->created_at->diffForHumans(),
        ];
    }
}
