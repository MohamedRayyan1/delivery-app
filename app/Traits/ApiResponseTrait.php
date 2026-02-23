<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    /**
     * استجابة النجاح (Success Response)
     * الشكل الموحد لأي عملية ناجحة
     */
    public function successResponse($data = null, string $message = 'تمت العملية بنجاح', int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * استجابة الخطأ (Error Response)
     * الشكل الموحد لأي خطأ (سيرفر، منطق، غير موجود)
     */
    public function errorResponse(string $message, int $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'status' => false,
            'message' => $message,
        ];

        // إذا كان هناك تفاصيل للأخطاء (مثل أخطاء التحقق) نضيفها
        if (!is_null($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}
