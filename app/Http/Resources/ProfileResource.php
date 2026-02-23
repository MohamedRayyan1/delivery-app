<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'city' => $this->city,
            'fcm_token' => $this->fcm_token,
            'points' => $this->whenLoaded('customerProfile', function () {
                return $this->customerProfile->points;
            }, 0),
        ];
    }
}
