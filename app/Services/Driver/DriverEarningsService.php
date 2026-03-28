<?php

namespace App\Services\Driver;

use App\Repositories\Eloquent\DriverEarningsRepository;
use Carbon\Carbon;

class DriverEarningsService
{
    protected $repository;

    public function __construct(DriverEarningsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getEarningsDashboard(int $driverId, ?string $startDate, ?string $endDate)
    {
        $todayStr = Carbon::today()->toDateString();

        $startOfWeekStr = Carbon::now()->startOfWeek()->toDateString();
        $endOfWeekStr = Carbon::now()->endOfWeek()->toDateString();

        $chartStart = $startDate ? Carbon::parse($startDate) : Carbon::now()->subDays(6);
        $chartEnd = $endDate ? Carbon::parse($endDate) : Carbon::now();

        $todayEarnings = $this->repository->getEarningsBetween($driverId, $todayStr, $todayStr);
        $weekEarnings = $this->repository->getEarningsBetween($driverId, $startOfWeekStr, $endOfWeekStr);
        $totalOrders = $this->repository->getTotalCompletedOrders($driverId);

        $recentTransactions = $this->repository->getRecentTransactions($driverId, 5);

        $dailyData = $this->repository->getDailyEarnings(
            $driverId,
            $chartStart->toDateString(),
            $chartEnd->toDateString()
        )->keyBy('date');

        $chartData = [];
        $currentDate = $chartStart->copy();

        while ($currentDate->lte($chartEnd)) {
            $dateStr = $currentDate->toDateString();

            $chartData[] = [
                'date' => $dateStr,
                'day_name' => $currentDate->format('D'),
                'total' => isset($dailyData[$dateStr]) ? (float)$dailyData[$dateStr]->total : 0.0,
            ];

            $currentDate->addDay();
        }

        return [
            'today_earnings' => (float)$todayEarnings,
            'week_earnings' => (float)$weekEarnings,
            'total_completed_orders' => $totalOrders,
            'chart_data' => $chartData,
            'recent_transactions' => $recentTransactions,
        ];
    }

    public function getTransactionsHistory(int $driverId, int $perPage)
    {
        return $this->repository->getPaginatedTransactions($driverId, $perPage);
    }
}
