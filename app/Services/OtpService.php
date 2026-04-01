<?php

namespace App\Services;


use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
class OtpService
{

    // private string $apiKey = 'dCOcq57cSz2HRu8vyp2Jc6:APA91bH_ovY8GlWbJPHKqhnZO6B9NeQBwVbMQ9dqUGZaICnFw3j0gxmeFrHmqU2Wxdf6NMSjKKNhHiKamT3DXhK3w9uewmDKckjCy5e1-V0i7uyTKrZrASw';
    private string $apiKey = 'f1HQBBLbTCeZfwCddEsBNQ:APA91bFj9_o-JI6HaCRkB6lRgpyLYMvCeIsAE6SoMxnVMIZkys2h2I0cff7h9R6avwykDUtwACCfvnGkkFyPbPZcVA5tgSwYXoZc0HWZTDYuy8D2TP0NbLI';
    private string $apiUrl = 'https://www.traccar.org/sms/';

    /** إعدادات Apple Review */
    private string $appleReviewPhone = '+15555550123';
    private string $appleStaticOtp   = '12345';

    /**
     * توليد وحفظ وإرسال الـ OTP
     */
    public function sendOtp(string $phone): bool
    {
        $isApple = ($phone === $this->appleReviewPhone);
        $code = $isApple ? $this->appleStaticOtp : (string) random_int(10000, 99999);

        $key = 'send-otp:' . $phone;

        if (RateLimiter::tooManyAttempts($key, 3)) {
            return false;
        }

        RateLimiter::hit($key, 60);

        // الحفظ في قاعدة البيانات
        Otp::create([
            'phone'      => $phone,
            'code'       => $code,
            'is_used'    => false,
            'expires_at' => now()->addMinutes(10),
        ]);

        // إذا كان رقم آبل، نكتفي بالحفظ ونعتبر الإرسال ناجح
        if ($isApple) {
            return true;
        }

        $message = "كود التحقق الخاص بك هو: $code. يرجى عدم مشاركته مع أي شخص. أبوس روحك اني";

        try {
            // إرسال الـ SMS باستخدام تقنيات لارافل الحديثة
                $response = Http::withoutVerifying()
                ->withHeaders([
                    'Authorization' => $this->apiKey,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json',
                ])->post($this->apiUrl, [
                    'to'      => $phone,
                    'message' => $message
                ]);
                Log::info('[SMS] Traccar Response', ['status' => $response->status(), 'body' => $response->body()]);

            if (!$response->successful()) {
                Log::error('[SMS] Failed to send OTP', ['phone' => $phone, 'response' => $response->body()]);
                return false;
            }

            return true;

        } catch (\Exception $e) {
            Log::error('[SMS] Exception: ' . $e->getMessage(), ['phone' => $phone]);
            return false;
        }
    }

    /**
     * التحقق من الـ OTP وإرجاع التوكن
     */
/**
     * التحقق من الـ OTP وإرجاع التوكن
     */
    public function verifyOtp(string $phone, string $code): array|string
    {
        // 1. تجاوز آبل
        if ($phone === $this->appleReviewPhone && $code === $this->appleStaticOtp) {
            $user = User::where('phone', $phone)->first();

            if (!$user) return 'User not found for Apple review phone.';
            if ($user->is_banned) return 'تم حظر هذا الحساب. يرجى التواصل مع الدعم.';

            return [
                'token' => $user->createToken('auth_token')->plainTextToken,
                'user'  => $user,
            ];
        }

        // 2. التحقق الفعلي من قاعدة البيانات
        $otp = Otp::where('phone', $phone)
            ->where('code', $code)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$otp) {
            return 'OTP is invalid or expired';
        }

        // 3. جلب المستخدم (بدون استخدام firstOrFail لتجنب الانهيار)
        $user = User::where('phone', $phone)->first();



        // إحراق الكود بعد التأكد من وجود المستخدم وصلاحية حسابه
        $otp->update(['is_used' => true]);

        if (!$user) {
            // ملاحظة هندسية: إذا لم يجد المستخدم، لا نحرق الكود (is_used) لكي يتمكن من استخدامه بعد التسجيل
            return 'لم يتم العثور على حساب مرتبظ بهذا الرقم. يرجى التسجيل أولاً.';
            }
            if ($user->is_banned) {
                return 'تم حظر هذا الحساب. يرجى التواصل مع الدعم.';
                }

        return [
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user'  => $user,
        ];
    }
}

