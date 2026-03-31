<?php

namespace App\Services\Vendor;

use App\Repositories\Eloquent\RestaurantDashboardRepository;
use Illuminate\Support\Facades\Cache;

class RestaurantDashboardService
{
    protected $dashboardRepository;

    public function __construct(RestaurantDashboardRepository $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    /**
     * تجميع بيانات البطاقات العلوية للوحة التحكم
     */
    /**
     * تجميع كافة بيانات لوحة التحكم (بما فيها الأداء الأسبوعي والشهري)
     */
    public function getOverviewData(int $restaurantId): array
    {
        return Cache::remember(
            "dashboard_full_overview_{$restaurantId}",
            now()->addSecond(15),
            function () use ($restaurantId) {

                $ratingStats = $this->dashboardRepository->getRestaurantRatingStats($restaurantId);

                return [
                    // البطاقات العلوية
                    'today_sales' => $this->dashboardRepository->getTodaySales($restaurantId),
                    'active_orders' => $this->dashboardRepository->getActiveOrdersCount($restaurantId),
                    'completed_orders' => $this->dashboardRepository->getCompletedOrdersCount($restaurantId),
                    'restaurant_rating' => $ratingStats['rating'],
                    'reviews_count' => $ratingStats['reviews_count'],

                    // الأصناف الأكثر مبيعاً
                    'top_items' => $this->dashboardRepository->getTopSellingItems($restaurantId, 3),

                    'weekly_sales' => $this->dashboardRepository->getWeeklySalesPerDay($restaurantId),
                    'monthly_sales' => $this->dashboardRepository->getLastSixMonthsSales($restaurantId),
                    'weekly_comparison' => $this->dashboardRepository->getWeeklyComparison($restaurantId),

                    'orders_pagination' => $this->dashboardRepository->getOrdersPaginated($restaurantId, 10),

                ];
            }
        );
    }

    public function getAllTopSellingItems(int $restaurantId)
    {
        return $this->dashboardRepository->getTopSellingItems($restaurantId);
    }
}
