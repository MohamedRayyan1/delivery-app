<?php

namespace App\Repositories\Eloquent;

use App\Models\Driver;

class DriverStatusRepository
{
    public function getDriverByUserId(int $userId)
    {
        return Driver::where('user_id', $userId)->firstOrFail();
    }

    public function updateOnlineStatus(int $driverId, bool $isOnline)
    {
        return Driver::where('id', $driverId)->update([
            'is_online' => $isOnline
        ]);
    }
}
