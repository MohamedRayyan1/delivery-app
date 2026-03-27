<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user' => [
                'name' => $this->user->name,
                'email' => $this->user->email,
                'phone' => $this->user->phone,
            ],
            'is_online' => (bool)$this->is_online,
            'account_status' => $this->account_status,
            'vehicle_type' => $this->vehicle_type,
            'vehicle_plate_number' => $this->vehicle_plate_number,
            'documents' => DriverDocumentResource::collection($this->whenLoaded('documents')),
            'total_earnings' => (float)$this->total_earnings,
        ];
    }
}
