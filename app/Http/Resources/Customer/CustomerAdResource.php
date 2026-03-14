<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerAdResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'restaurant_id' => $this->restaurant_id,
            'title' => $this->title,
            'content' => $this->content,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
        ];
    }
}
