<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Services\Driver\DriverProfileService;
use App\Http\Requests\Driver\UpdateDriverProfileRequest;
use App\Http\Resources\Driver\DriverFullProfileResource;
use App\Http\Resources\Driver\DriverResource;
use App\Http\Resources\Driver\DriverStatsProfileResource;
use Auth;
use Request;

class DriverProfileController extends Controller
{
    protected $profileService;

    public function __construct(DriverProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function update(UpdateDriverProfileRequest $request)
    {
        try {
            $updatedDriver = $this->profileService->updateProfile(
                $request->user()->id,
                $request->validated()
            );

            $message = 'تم تحديث البيانات بنجاح.';
            if ($updatedDriver->account_status === 'pending') {
                $message .= ' يرجى العلم أنه تم إعادة حالة حسابك إلى (قيد الانتظار) لمراجعة معلومات المركبة الجديدة.';
            }

            return $this->successResponse(new DriverResource($updatedDriver), $message);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء تحديث البيانات: ' . $e->getMessage()
            ], 500);
        }
    }


    public function showProfile(Request $request)
    {
        try {
            $driverId = Auth::user()->driver->id;

            $profile = $this->profileService->getProfile($driverId);

            return $this->successResponse(new DriverStatsProfileResource($profile));
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء جلب الملف الشخصي: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getProfileDetails()
    {
        try {
            // جلب السائق المرتبط بالمستخدم الحالي مع كافة علاقاته
            $driver = Auth::user()->driver->load(['user', 'documents']);

            return $this->successResponse(
                new DriverFullProfileResource($driver),
                'تم جلب بيانات الملف الشخصي بنجاح'
            );
        } catch (\Exception $e) {
            return $this->errorResponse('حدث خطأ أثناء جلب البيانات', 500);
        }
    }
}
