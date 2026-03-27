<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Services\Driver\DriverRegistrationService;
use App\Http\Requests\Driver\DriverRegisterRequest;
use App\Http\Resources\Driver\DriverResource;

class DriverAuthController extends Controller
{
    protected $registrationService;

    public function __construct(DriverRegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    public function register(DriverRegisterRequest $request)
    {
        try {
            $driver = $this->registrationService->registerDriver($request->validated());

            $token = $driver->user->createToken('driver_auth_token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'تم تسجيل حساب السائق بنجاح، يرجى انتظار موافقة الإدارة على الثبوتيات.',
                'data' => [
                    'driver' => new DriverResource($driver),
                    'token' => $token,
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء التسجيل: ' . $e->getMessage()
            ], 500);
        }
    }
}
