<?php

namespace App\Repositories\Eloquent;

use App\Models\DeliveryRequest;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class HomePageRepository
{
  public function getAvailableDeliveryRequestsByCity(string $city, string $vehicleType, array $excludedIds = [])
{
    return DeliveryRequest::query()
        ->where('status', 'pending')
        ->whereNull('driver_id')
        ->whereNotIn('id', $excludedIds)
        // الفلترة حسب نوع المركبة المطلوبة للطلب
        ->where('required_vehicle_type', $vehicleType)
        ->whereHas('order.restaurant', function ($query) use ($city) {
            $query->where('city', $city);
        })
        ->with([
            'order.restaurant',
            'order.address'
        ])
        ->latest()
        ->get();
}

    public function acceptDeliveryRequest(int $requestId, int $driverId): bool
    {
        return DB::transaction(function () use ($requestId, $driverId) {
            $request = DeliveryRequest::where('id', $requestId)
                ->where('status', 'pending')
                ->lockForUpdate()
                ->first();

            if (!$request) return false;

            $request->update([
                'driver_id' => $driverId,
                'status'    => 'accepted'
            ]);

            Order::where('id', $request->order_id)->update([
                'driver_id' => $driverId,
                'status'    => 'accepted'
            ]);

            return true;
        });
    }
}
