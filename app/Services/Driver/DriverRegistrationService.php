<?php

namespace App\Services\Driver;

use App\Jobs\ProcessImageJob;
use App\Models\DriverDocument;
use App\Repositories\Eloquent\DriverRegistrationRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DriverRegistrationService
{
    protected $repository;

    public function __construct(DriverRegistrationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function registerDriver(array $data)
    {
        return DB::transaction(function () use ($data) {

            $userData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'city' => $data['city'],
                'fcm_token' => $data['fcm_token'],
                'role' => 'driver',
                'password' => Hash::make($data['password']),
            ];
            $user = $this->repository->createUser($userData);

            $driverData = [
                'user_id' => $user->id,
                'vehicle_type' => $data['vehicle_type'],
                'vehicle_plate_number' => $data['vehicle_plate_number'] ?? null,
                'account_status' => 'pending',
                'is_online' => false,
            ];

            $driver = $this->repository->createDriver($driverData);

            $documentTypes = [
                'personal_photo',
                'id_card_front',
                'id_card_back',
                'driver_license',
            ];

            foreach ($documentTypes as $type) {
                if (request()->hasFile($type)) {
                    // 1. تخزين الملف الخام بسرعة البرق
                    $path = request()->file($type)->store("drivers/{$driver->id}/documents", 'public');

                    // 2. إنشاء السجل فوراً لضمان عدم ضياع بيانات السائق (عملية سريعة جداً)
                    DriverDocument::updateOrCreate(
                        [
                            'driver_id'     => $driver->id,
                            'document_type' => $type,
                        ],
                        [
                            'file_path' => $path,
                            'status'    => 'pending',
                        ]
                    );

                    // 3. إرسال الصورة لمعالج المهام الموحد الخاص بك لضغطها في الخلفية
                    // نمرر null لأن الجوب الخاص بك لا يستخدم الموديل، بل يعتمد على المسار فقط
                    ProcessImageJob::dispatch( $path)->afterCommit();
                }
            }

            return $driver->load(['user']);
        });
    }
}
