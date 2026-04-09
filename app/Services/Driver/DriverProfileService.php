<?php

namespace App\Services\Driver;

use App\Repositories\Eloquent\DriverProfileRepository;
use Illuminate\Support\Facades\DB;

class DriverProfileService
{
    protected $repository;

    public function __construct(DriverProfileRepository $repository)
    {
        $this->repository = $repository;
    }

    public function updateProfile(int $userId, array $data)
    {

        return DB::transaction(function () use ($userId, $data) {


            $driver = $this->repository->getDriverByUserId($userId);

            $userData = [];
            if (isset($data['name'])) $userData['name'] = $data['name'];
            if (isset($data['email'])) $userData['email'] = $data['email'];
            if (isset($data['phone'])) $userData['phone'] = $data['phone'];
            if (isset($data['city'])) $userData['city'] = $data['city'];
            if (!empty($userData)) {
                $this->repository->updateUser($userId, $userData);
            }

            $driverData = [];
            $vehicleChanged = false;

            if (isset($data['vehicle_type']) && $data['vehicle_type'] !== $driver->vehicle_type) {
                $driverData['vehicle_type'] = $data['vehicle_type'];
                $vehicleChanged = true;
            }

            if (array_key_exists('vehicle_plate_number', $data) && $data['vehicle_plate_number'] !== $driver->vehicle_plate_number) {
                $driverData['vehicle_plate_number'] = $data['vehicle_plate_number'];
                $vehicleChanged = true;
            }

            if ($vehicleChanged) {
                $driverData['account_status'] = 'pending';
                // اختياري: إذا غير مركبته قد نضطر لجعله offline فوراً حتى يتم القبول
                $driverData['is_online'] = false;
            }

            if (!empty($driverData)) {
                $this->repository->updateDriver($driver->id, $driverData);
            }
            
            $documentTypes = [
                'personal_photo',
                'id_card_front',
                'id_card_back',
                'driver_license',
                'vehicle_mechanic'
            ];

            foreach ($documentTypes as $type) {
                if (request()->hasFile($type)) {
                    // تخزين الملف بشكل فيزيائي سريع
                    $path = request()->file($type)->store("drivers/{$driver->id}/documents", 'public');

                    // إرسال مهمة للـ Queue لمعالجة السجل في قاعدة البيانات
                    // ملاحظة: تأكد من أن Job (ProcessDriverDocument) يقوم بـ updateOrCreate للسجل
                    \App\Jobs\ProcessDriverDocument::dispatch($driver->id, $type, $path);

                    $needsRevalidation = true; // أي تغيير في الوثائق يتطلب مراجعة الإدارة
                }
            }

            // 4. إذا تغيرت المركبة أو الوثائق، نحول الحساب لـ pending
            if ($needsRevalidation) {
                $driverData['account_status'] = 'pending';
                $driverData['is_online'] = false;
            }

            if (!empty($driverData)) {
                $this->repository->updateDriver($driver->id, $driverData);
            }

            // إعادة جلب السائق مع كافة البيانات والوثائق المحدثة
            return $this->repository->getDriverByUserId($userId)->load(['user', 'documents']);
        });
    }


    public function getProfile(int $driverId)
    {
        return $this->repository->getDriverProfileStats($driverId);
    }
}
