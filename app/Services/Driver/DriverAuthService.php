<?php

namespace App\Services\Driver;

use App\Jobs\ProcessDriverImageJob;
use App\Repositories\Driver\DriverAuthRepository;
use Illuminate\Support\Facades\Auth;
use App\Services\OtpService;

class DriverAuthService
{

    protected DriverAuthRepository $repository;
    protected OtpService $otpService;

    public function __construct(DriverAuthRepository $repository, OtpService $otpService)
    {
        $this->repository = $repository;
        $this->otpService = $otpService;
    }
    public function register(array $validated)
    {
        $user = $this->repository->createUser($validated);

        $licensePath = null;
        if (isset($validated['license_image']) && $validated['license_image'] instanceof \Illuminate\Http\UploadedFile) {
            $licensePath = $validated['license_image']->store('temp/drivers/licenses', 'public');
        }

        $driver = $this->repository->createDriver([
            'vehicle_type'        => $validated['vehicle_type'],
            'vehicle_plate_number' => $validated['vehicle_plate_number'],
            'license_image'       => $licensePath,
        ], $user->id);

        if ($licensePath) {
            ProcessDriverImageJob::dispatch($driver->id, $licensePath);
        }

        return ['user' => $user, 'driver' => $driver];
    }

    public function login(string $phone, ?string $fcmToken = null)
    {
        $user = $this->repository->findUserByPhone($phone);

        if (!$user || $user->role !== 'driver' || $user->is_banned) {
            throw new \Exception('بيانات الدخول غير صحيحة أو الحساب محظور');
        }

        if ($fcmToken) {
            $this->repository->updateUser($user->id, ['fcm_token' => $fcmToken]);
        }

        $this->otpService->sendOtp($user->phone);

        $token = $user->createToken('driver-token')->plainTextToken;

        return ['token' => $token];
    }

    public function logout()
    {
        /** @var \Laravel\Sanctum\PersonalAccessToken $token */
        $token = Auth::user()?->currentAccessToken();
        $token?->delete();
    }

    public function updateProfile(int $userId, array $validated)
    {
        $this->repository->updateUser($userId, $validated);

        $driver = $this->repository->findDriverByUserId($userId);

        if ($driver) {
            $driverData = [];
            if (isset($validated['vehicle_type']))         $driverData['vehicle_type']        = $validated['vehicle_type'];
            if (isset($validated['vehicle_plate_number'])) $driverData['vehicle_plate_number'] = $validated['vehicle_plate_number'];
            if (isset($validated['license_image']) && $validated['license_image'] instanceof \Illuminate\Http\UploadedFile) {
                $tempPath = $validated['license_image']->store('temp/drivers/licenses', 'public');
                $driverData['license_image'] = $tempPath;
                ProcessDriverImageJob::dispatch($driver->id, $tempPath);
            }
            $this->repository->updateDriver($driver->id, $driverData);
        }

        return $driver->fresh();
    }

    public function getProfile(int $userId): array
    {
        $profile = $this->repository->getProfile($userId);

        if (!$profile) {
            throw new \Exception('البروفايل غير موجود أو ليس سائقاً');
        }
        return $profile;
    }
}
