<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SendOtpRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Services\OtpService;

class OtpController extends Controller
{
    private OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function send(SendOtpRequest $request)
    {
        $sent = $this->otpService->sendOtp($request->phone);

        if (!$sent) {
            return $this->errorResponse('فشل في إرسال رمز التحقق، يرجى المحاولة لاحقاً', 500);
        }

        return $this->successResponse(null, 'تم إرسال رمز التحقق بنجاح');
    }

    public function verify(VerifyOtpRequest $request)
    {
        $result = $this->otpService->verifyOtp($request->phone, $request->code);

        if (is_string($result)) {
            return $this->errorResponse($result, 400);
        }

        return $this->successResponse($result, 'تم التحقق بنجاح');
    }
}
