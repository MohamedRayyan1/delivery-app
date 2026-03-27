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
     * حساب المسافة + الزمن الطرقي باستخدام Geoapify
     *
     * @return array { distance_km: float, duration_minutes: int }
     */
    public function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): array
    {
        if (abs($lat1 - $lat2) < 0.0001 && abs($lng1 - $lng2) < 0.0001) {
            return ['distance_km' => 0.0, 'duration_minutes' => 0];
        }

        try {
            $payload = [
                'mode'    => 'drive',
                'sources' => [['location' => [$lng1, $lat1]]],
                'targets' => [['location' => [$lng2, $lat2]]]
            ];

            $response = Http::timeout(10)
                ->post("https://api.geoapify.com/v1/routematrix?apiKey={$this->apiKey}", $payload);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['sources_to_targets'][0][0])) {
                    $result = $data['sources_to_targets'][0][0];

                    $distanceMeters = $result['distance'] ?? 0;
                    $timeSeconds    = $result['time'] ?? 0;

                    return [
                        'distance_km'     => round($distanceMeters / 1000, 2),
                        'duration_minutes' => (int) round($timeSeconds / 60),   // تحويل إلى دقائق
                    ];
                }
            }

            Log::warning('Geoapify failed - using fallback');
        } catch (Exception $e) {
            Log::error('Geoapify Distance Error', ['message' => $e->getMessage()]);
        }

        // Fallback (Haversine للمسافة فقط + تقدير زمن بسيط)
        $distanceKm = $this->haversineFallback($lat1, $lng1, $lat2, $lng2);
        $estimatedMinutes = max(1, (int) round($distanceKm * 2.5)); // تقدير 2.5 دقيقة لكل كم

        return [
            'distance_km'     => $distanceKm,
            'duration_minutes' => $estimatedMinutes,
        ];
    }

    private function haversineFallback(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    // داخل GeoapifyDistanceService.php

    public function calculateMatrix(array $source, array $driversLocations)
    {
        // $source = ['location' => [lng, lat]]
        // $driversLocations = [ ['location' => [lng, lat]], ... ]

        $payload = [
            'mode' => 'drive',
            'sources' => [$source],
            'targets' => $driversLocations
        ];

        $response = Http::post("https://api.geoapify.com/v1/routematrix?apiKey={$this->apiKey}", $payload);

        return $response->json(); // ستحتوي النتيجة على المسافات لكل الأهداف (السائقين) بضربة واحدة
    }
}
