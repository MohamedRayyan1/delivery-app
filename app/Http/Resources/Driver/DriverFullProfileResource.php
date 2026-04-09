<?php

namespace App\Http\Resources\Driver;

use Illuminate\Http\Resources\Json\JsonResource;

class DriverFullProfileResource extends JsonResource
{
    public function toArray($request): array
    {
        // $this هنا تشير إلى كائن الـ Driver
        $user = $this->user;

        // تحويل الوثائق إلى مصفوفة مفتاحها نوع الوثيقة وقيمتها الرابط الكامل للصورة
        $documents = $this->documents->pluck('file_path', 'document_type')->map(function ($path) {
            return asset('storage/' . $path);
        });

        return [
            'personal_info' => [
                'name'  => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'city'  => $user->city,
            ],
            'vehicle_info' => [
                'vehicle_type'         => $this->vehicle_type,
                'vehicle_plate_number' => $this->vehicle_plate_number,
                'account_status'       => $this->account_status,
                'is_online'            => (bool) $this->is_online,
            ],
            'documents' => [
                'personal_photo'   => $documents->get('personal_photo'),
                'id_card_front'    => $documents->get('id_card_front'),
                'id_card_back'     => $documents->get('id_card_back'),
                'driver_license'   => $documents->get('driver_license'),
                'vehicle_mechanic' => $documents->get('vehicle_mechanic'),
            ]
        ];
    }
}
