<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\OtpService;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    private OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    // 1. تسجيل مستخدم جديد
    public function register(RegisterRequest $request)
    {
        // إنشاء المستخدم
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            // باسورد داخلي عشوائي فقط لتلبية قيود قاعدة البيانات (لا يُستخدم في الدخول)
            'password' => Hash::make(Str::random(16)),
            'city' => $request->city,
            'role' => $request->role ?? 'customer', // بشكل افتراضي كل المسجلين بيكونوا زبائن
            'fcm_token' => $request->fcm_token,
        ]);


        // إنشاء بروفايل للزبون (نقاط 0) فوراً
        $user->customerProfile()->create(['points' => 0]);

        // إرسال كود التحقق إلى رقم الهاتف أثناء عملية التسجيل
        $sent = $this->otpService->sendOtp($user->phone);

        // if (! $sent) {
        //     return $this->errorResponse('فشل في إرسال رمز التحقق، يرجى المحاولة لاحقاً', 500);
        // }
          $token = $user->createToken('auth_token')->plainTextToken;

        // لا ننشئ توكن هنا، التوكن يُنشأ بعد التحقق من الـ OTP في /otp/verify
        return $this->successResponse(['token' => $token], 'تم إنشاء الحساب وإرسال رمز التحقق بنجاح', 201);
    }

    // 2. تسجيل الدخول
    public function login(LoginRequest $request)
    {
        $user = User::where('phone', $request->phone)->first();

        if (! $user) {
            return $this->errorResponse('رقم الهاتف غير مسجل، يرجى إنشاء حساب جديد.', 404);
        }

        if ($user->is_banned) {
            return $this->errorResponse('تم حظر هذا الحساب. يرجى التواصل مع الدعم.', 403);
        }

        // تحديث FCM Token إذا انبعت (عشان الإشعارات توصل عالجهاز الجديد)
        if ($request->fcm_token) {
            $user->update(['fcm_token' => $request->fcm_token]);
        }

        // إرسال كود التحقق إلى رقم الهاتف أثناء عملية تسجيل الدخول
        $sent = $this->otpService->sendOtp($user->phone);

        // if (! $sent) {
        //     return $this->errorResponse('فشل في إرسال رمز التحقق، يرجى المحاولة لاحقاً', 500);
        // }
        $token = $user->createToken('auth_token')->plainTextToken;

        // لا ننشئ توكن هنا، التوكن يُنشأ بعد التحقق من الـ OTP في /otp/verify
        return $this->successResponse(['token' => $token], 'تم إرسال رمز التحقق بنجاح');
    }


    public function logout(Request $request)
    {
        /** @var \Laravel\Sanctum\PersonalAccessToken $token */
        $token = $request->user()->currentAccessToken();

        $token->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    // 4. جلب بيانات المستخدم الحالي
    public function profile(Request $request)
    {
        return new UserResource($request->user());
    }
}
