<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Requests\Driver\LoginDriverRequest;
use App\Http\Requests\Driver\RegisterDriverRequest;
use App\Http\Requests\Driver\UpdateDriverProfileRequest;
use App\Services\Driver\DriverAuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DriverAuthController extends Controller
{
    public function __construct(protected DriverAuthService $service) {}

    public function register(RegisterDriverRequest $request): JsonResponse
    {
        $result = $this->service->register($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'تم التسجيل بنجاح. حسابك الآن في حالة "pending"، سيتم مراجعته.',
            'data'    => $result
        ]);
    }

    public function login(LoginDriverRequest $request): JsonResponse
    {
        try {
            $result = $this->service->login(
                $request->phone,
                $request->fcm_token ?? null
            );

            return response()->json([
                "status" => true,
                "message" => "تم إرسال رمز التحقق بنجاح",
                "data" => [
                    "token" => $result['token']
                ]
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
            ], 400);
        }
    }

    public function logout(): JsonResponse
    {
        $this->service->logout();
        return response()->json(['success' => true, 'message' => 'تم تسجيل الخروج بنجاح']);
    }

    public function updateProfile(UpdateDriverProfileRequest $request): JsonResponse
    {
        $result = $this->service->updateProfile(Auth::id(), $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الملف الشخصي بنجاح',
            'data'    => $result
        ]);
    }

    /**
     * عرض بيانات البروفايل الخاص بالسائق المسجل
     */
    public function profile(): JsonResponse
    {
        try {
            $profile = $this->service->getProfile(Auth::id());

            return response()->json([
                'success' => true,
                'data'    => $profile
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }
}
