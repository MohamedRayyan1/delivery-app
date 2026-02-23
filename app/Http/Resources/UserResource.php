<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'city' => $this->city,
            'role' => $this->role, // customer, driver...
            'is_banned' => (bool) $this->is_banned,
            // بنرجع التوكن بس إذا كنا باعتينه معنا (في حالة اللوجين والريجستر)
            'token' => $this->when($this->token, $this->token),
        ];
    }
}
