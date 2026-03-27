<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Models\Driver;
use Carbon\Carbon;

class DriverProfileRepository
{
    public function getDriverByUserId(int $userId)
    {
        return Driver::where('user_id', $userId)->firstOrFail();
    }

    public function updateUser(int $userId, array $data)
    {
        return User::where('id', $userId)->update($data);
    }

    public function updateDriver(int $driverId, array $data)
    {
        return Driver::where('id', $driverId)->update($data);
    }

    public function getDriverProfileStats(int $driverId)
    {
        return Driver::with(['user'])
            ->withCount(['orders' => function ($query) {
                $query->where('status', 'delivered');
            }])
            ->withAvg('reviews as average_rating', 'driver_rating')
            ->withSum(['orders as today_earnings' => function ($query) {
                $query->where('status', 'delivered')
                      ->whereDate('created_at', Carbon::today());
            }], 'delivery_fee')
            ->with(['reviews' => function ($query) {
                $query->whereNotNull('driver_rating')
                      ->orderBy('created_at', 'desc')
                      ->with('user:id,name');
            }])
            ->findOrFail($driverId);
    }
}
