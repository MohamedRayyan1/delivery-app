<?php

namespace App\Services\Vendor;

use App\Repositories\Eloquent\RestaurantReportRepository;
use Carbon\CarbonPeriod;
use Barryvdh\DomPDF\Facade\Pdf;
class RestaurantReportService
{
    public function __construct(private RestaurantReportRepository $repo) {}

    public function getCards($restaurantId)
    {
        // اليوم
        $todayFrom = now()->startOfDay();
        $todayTo = now();

        // أمس
        $yesterdayFrom = now()->subDay()->startOfDay();
        $yesterdayTo = now()->subDay()->endOfDay();

        return [
            'customers' => $this->buildCard(
                $this->repo->countCustomers($restaurantId, $todayFrom, $todayTo),
                $this->repo->countCustomers($restaurantId, $yesterdayFrom, $yesterdayTo)
            ),

            'net_income' => $this->buildCard(
                $this->repo->getNetIncome($restaurantId, $todayFrom, $todayTo),
                $this->repo->getNetIncome($restaurantId, $yesterdayFrom, $yesterdayTo),
                true
            ),

            'total_sales' => $this->buildCard(
                $this->repo->getTotalSales($restaurantId, $todayFrom, $todayTo),
                $this->repo->getTotalSales($restaurantId, $yesterdayFrom, $yesterdayTo),
                true
            ),
        ];
    }

    private function buildCard($current, $previous, $isMoney = false)
    {
        if ($previous == 0 && $current > 0) {
            $percentage = 100;
        } elseif ($previous == 0) {
            $percentage = 0;
        } else {
            $percentage = (($current - $previous) / $previous) * 100;
        }

        return [
            'value' => $isMoney ? round($current, 2) : (int) $current,
            'percentage' => round($percentage, 2),
            'trend' => $percentage >= 0 ? 'up' : 'down'
        ];
    }

    public function getMonthlyGrowth($restaurantId)
    {
        $currentYear = now()->year;
        $lastYear = $currentYear - 1;

        $currentData = $this->repo->getMonthlyNetIncome($restaurantId, $currentYear);
        $lastData = $this->repo->getMonthlyNetIncome($restaurantId, $lastYear);

        $months = [];

        // 🔥 آخر 6 أشهر بدون تكرار
        $start = now()->subMonths(5)->startOfMonth();
        $end = now()->startOfMonth();

        $period = CarbonPeriod::create($start, '1 month', $end);

        foreach ($period as $date) {
            $monthNumber = $date->month;

            $months[] = [
                'month' => $date->format('M'),
                'current' => round($currentData[$monthNumber] ?? 0, 2),
                'last_year' => round($lastData[$monthNumber] ?? 0, 2),
            ];
        }

        return $months;
    }



    public function generatePdf($restaurantId)
    {
        $cards = $this->getCards($restaurantId);
        $growth = $this->getMonthlyGrowth($restaurantId);
        $pdf = Pdf::loadView('reports.restaurant-report', [
            'cards' => $cards,
            'growth' => $growth
        ]);

        return $pdf->download('restaurant-report.pdf');
    }
}
