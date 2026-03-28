<?php

namespace App\Jobs;

use App\Models\DeliveryRequest;
use App\Models\Driver;
use App\Services\Driver\GeoapifyDistanceService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotifyNearbyDriversJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $deliveryRequest;

    public function __construct(DeliveryRequest $deliveryRequest)
    {
        $this->deliveryRequest = $deliveryRequest;
    }

    // عدد مرات إعادة المحاولة في حال فشل الـ API
    public $tries = 3;

    // عدد الثواني للانتظار قبل إعادة المحاولة
    public $backoff = 10;
    // حذف المهمة إذا فشلت تماماً لمنع تكدس الطابور
    public $deleteWhenMissingModels = true;
    public function handle(GeoapifyDistanceService $distanceService): void
    {
        $request = $this->deliveryRequest;
        $restaurant = $request->order->restaurant;

        // 1. فلترة السائقين (المنخل الخشن)
        $drivers = Driver::where('is_online', true)
            ->where('account_status', 'active')
            ->where('vehicle_type', $request->required_vehicle_type)
            ->whereHas('user', function ($q) use ($restaurant) {
                $q->where('city', $restaurant->city);
            })
            ->whereNotNull('current_lat')
            ->get();

        Log::info("Job Started: Found " . $drivers->count() . " potential drivers in {$restaurant->city}");

        if ($drivers->isEmpty()) return;

        // 2. مصفوفة المواقع لـ Geoapify
        $source = ['location' => [(float)$restaurant->lng, (float)$restaurant->lat]];
        $targets = $drivers->map(fn($d) => ['location' => [(float)$d->current_lng, (float)$d->current_lat]])->toArray();

        // 3. استدعاء Matrix Mode
        try {
            $matrixResult = $distanceService->calculateMatrix($source, $targets);

            if (isset($matrixResult['sources_to_targets'][0])) {
                $results = $matrixResult['sources_to_targets'][0];

                foreach ($results as $index => $info) {
                    $distanceKm = isset($info['distance']) ? $info['distance'] / 1000 : null;

                    if ($distanceKm !== null && $distanceKm <= 5.0) {
                        $targetDriver = $drivers[$index];

                        // بدلاً من إرسال إشعار، سنطبع في الـ Log
                        Log::channel('single')->info("🎯 MATCH FOUND: Driver ID: {$targetDriver->id} is {$distanceKm} km away from Request ID: {$request->id}");
                    } else {
                        Log::channel('single')->info("⏩ Driver ID: {$drivers[$index]->id} is too far ({$distanceKm} km)");
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("Matrix API Error in Job: " . $e->getMessage());
        }
    }

    /**
     * الحصول على الوسيط الذي يجب أن تمر من خلاله الوظيفة.
     */
    public function middleware(): array
    {
        return [new RateLimited('geoapify-limiter')];
    }
}
