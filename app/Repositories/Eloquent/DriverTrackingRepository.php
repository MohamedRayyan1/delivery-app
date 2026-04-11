<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use Illuminate\Support\Facades\Cache;

class DriverTrackingRepository
{
    public function verifyDriverOrder(int $driverId, int $orderId): bool
    {
        // Cache the validation for 5 minutes to prevent DB bottleneck during high-frequency live tracking
        return Cache::remember("verify_tracking_{$driverId}_{$orderId}", 300, function () use ($driverId, $orderId) {
            return Order::where('id', $orderId)
                ->where('driver_id', $driverId)
                ->whereIn('status', ['picked_up', 'preparing'])
                ->exists();
        });
    }

    public function updateLiveLocation(int $driverId, float $lat, float $lng): void
    {
        $locationData = [
            'lat' => $lat,
            'lng' => $lng,
            'updated_at' => now()->timestamp
        ];

        // Store coordinates in RAM for blazing fast read/write (Expires in 1 hour if driver goes offline)
        Cache::put("driver_{$driverId}_live_location", $locationData, 3600);
    }

    public function getLiveLocation(int $driverId): ?array
    {
        return Cache::get("driver_{$driverId}_live_location");
    }
}
