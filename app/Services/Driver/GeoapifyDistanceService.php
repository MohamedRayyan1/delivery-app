<?php

namespace App\Services\Driver;

use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Facades\Log;

class GeoapifyDistanceService
{
    protected string $apiKey;

    public function __construct()
    {
        $apiKeyFromConfig = config('services.geoapify.api_key');

        if (empty($apiKeyFromConfig) || !is_string($apiKeyFromConfig)) {
            throw new Exception('GEOAPIFY_API_KEY غير موجود أو فارغ. تأكد من إضافته في .env و config/services.php');
        }

        $this->apiKey = $apiKeyFromConfig;
    }

    /**
     * حساب المسافة + الزمن الطرقي باستخدام Geoapify حصراً
     *
     * @return array { distance_km: float, duration_minutes: int }
     * @throws Exception في حال فشل الخدمة الخارجية
     */
    public function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): array
    {
        // إذا كانت الإحداثيات متطابقة تماماً، نوفر استدعاء الـ API
        if (abs($lat1 - $lat2) < 0.0001 && abs($lng1 - $lng2) < 0.0001) {
            return ['distance_km' => 0.0, 'duration_minutes' => 0];
        }

        try {
            $payload = [
                'mode'    => 'drive',
                'sources' => [['location' => [$lng1, $lat1]]],
                'targets' => [['location' => [$lng2, $lat2]]]
            ];
            // \Log::info("Requesting Geoapify for: " . json_encode($payload));
            $response = Http::timeout(10)
                ->post("https://api.geoapify.com/v1/routematrix?apiKey={$this->apiKey}", $payload);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['sources_to_targets'][0][0])) {
                    $result = $data['sources_to_targets'][0][0];

                    // التحقق من وجود خطأ في المصفوفة نفسها (مثل عدم وجود طريق)
                    if (isset($result['error'])) {
                        throw new Exception('Geoapify Route Error: ' . ($result['error'] ?? 'No route found'));
                    }

                    $distanceMeters = $result['distance'] ?? 0;
                    $timeSeconds    = $result['time'] ?? 0;

                    return [
                        'distance_km'      => round($distanceMeters / 1000, 2),
                        'duration_minutes' => (int) round($timeSeconds / 60),
                    ];
                }
            }

            // في حال فشل الاستجابة أو كانت غير مكتملة
            throw new Exception('Geoapify API failed or returned invalid data');
        } catch (Exception $e) {
            Log::error('Geoapify Critical Error', [
                'message' => $e->getMessage(),
                'coords' => "from ($lat1, $lng1) to ($lat2, $lng2)"
            ]);

            // نعيد قيم صفرية أو نرفع الخطأ حسب رغبتك في معالجة الخطأ في الـ Service
            // هنا سنعيد 999 كم لضمان استبعاد الطلب من الفلترة في حال تعطل الخدمة
            return ['distance_km' => 999.0, 'duration_minutes' => 999];
        }
    }

    /**
     * حساب المصفوفة لعدة أهداف بضربة واحدة
     */
    public function calculateMatrix(array $source, array $driversLocations)
    {
        try {
            $payload = [
                'mode' => 'drive',
                'sources' => [$source],
                'targets' => $driversLocations
            ];

            $response = Http::timeout(15)
                ->post("https://api.geoapify.com/v1/routematrix?apiKey={$this->apiKey}", $payload);

            if ($response->failed()) {
                throw new Exception('Geoapify Matrix API failed');
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('Geoapify Matrix Error', ['message' => $e->getMessage()]);
            return null;
        }
    }
}
