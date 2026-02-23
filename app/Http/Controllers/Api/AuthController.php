<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // 1. تسجيل مستخدم جديد
    public function register(RegisterRequest $request)
    {
        // إنشاء المستخدم
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'city' => $request->city,
            'role' => $request->role ?? 'customer', // بشكل افتراضي كل المسجلين بيكونوا زبائن
            'fcm_token' => $request->fcm_token,
        ]);

     
        // إنشاء بروفايل للزبون (نقاط 0) فوراً
        $user->customerProfile()->create(['points' => 0]);

        // إنشاء التوكن
        $token = $user->createToken('auth_token')->plainTextToken;
        // حقن التوكن داخل الأوبجكت عشان يظهر بالـ Resource
        $user->token = $token;

        return $this->successResponse(new UserResource($user), 'تم إنشاء الحساب بنجاح', 201);

    }

    // 2. تسجيل الدخول
    public function login(LoginRequest $request)
    {
        $user = User::where('phone', $request->phone)->first();

        // التحقق من الباسورد
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'phone' => ['بيانات الاعتماد غير صحيحة.'],
            ]);
        }

        // تحديث FCM Token إذا انبعت (عشان الإشعارات توصل عالجهاز الجديد)
        if ($request->fcm_token) {
            $user->update(['fcm_token' => $request->fcm_token]);
        }

        // إنشاء توكن جديد
        // ملاحظة: فيك تحذف التوكنات القديمة $user->tokens()->delete(); لو بدك دخول من جهاز واحد بس
        $token = $user->createToken('auth_token')->plainTextToken;
        $user->token = $token;

        return $this->successResponse(new UserResource($user), 'تم تسجيل الدخول بنجاح');
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
