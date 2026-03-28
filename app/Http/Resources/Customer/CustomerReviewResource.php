<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerReviewResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'restaurant' => [
                'id' => $this->restaurant_id,
                'name' => $this->restaurant ? $this->restaurant->name : null,
                'logo' => ($this->restaurant && $this->restaurant->logo) ? asset('storage/' . $this->restaurant->logo) : null,
                'rating' => $this->restaurant_rating ? (float)$this->restaurant_rating : null,
            ],
            'driver' => [
                'id' => $this->driver_id,
                'name' => ($this->driver && $this->driver->user) ? $this->driver->user->name : null,
                'rating' => $this->driver_rating ? (float)$this->driver_rating : null,
            ],
            'comment' => $this->comment,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
