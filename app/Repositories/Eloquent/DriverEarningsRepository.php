<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class DriverEarningsRepository
{
    public function getTotalCompletedOrders(int $driverId)
    {
        return Order::where('driver_id', $driverId)
            ->where('status', 'delivered')
            ->count();
    }

    public function getEarningsBetween(int $driverId, string $startDate, string $endDate)
    {
        return Order::where('driver_id', $driverId)
            ->where('status', 'delivered')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->sum('delivery_fee');
    }

    public function getDailyEarnings(int $driverId, string $startDate, string $endDate)
    {
        return Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(delivery_fee) as total')
            )
            ->where('driver_id', $driverId)
            ->where('status', 'delivered')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
    }

    public function getRecentTransactions(int $driverId, int $limit = 5)
    {
        return Order::with(['restaurant:id,name'])
            ->where('driver_id', $driverId)
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getPaginatedTransactions(int $driverId, int $perPage = 15)
    {
        return Order::with(['restaurant:id,name'])
            ->where('driver_id', $driverId)
            ->orderBy('updated_at', 'desc')
            ->cursorPaginate($perPage);
    }
}
