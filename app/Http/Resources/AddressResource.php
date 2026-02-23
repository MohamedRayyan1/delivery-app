<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'street' => $this->street,
            'details' => $this->details,
            'floor' => $this->floor,
            'phone' => $this->phone,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'is_default' => $this->is_default,
        ];
    }
}
