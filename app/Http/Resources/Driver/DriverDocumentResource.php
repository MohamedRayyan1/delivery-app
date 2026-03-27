<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverDocumentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'document_type' => $this->document_type,
            'file_url' => $this->file_path ? asset('storage/' . $this->file_path) : null,
            'status' => $this->status,
        ];
    }
}
