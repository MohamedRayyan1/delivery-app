<?php

namespace App\Http\Resources\Vendor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Vendor\WeeklySalesResource;
use App\Http\Resources\Vendor\MonthlySalesResource;
use App\Http\Resources\Vendor\WeeklyComparisonResource;

class DashboardOverviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        $todaySales = $this->resource['today_sales'];
        $change = $this->resource['sales_change_percentage'] ?? 0;
        // تحويل كائن الـ Paginated Orders إلى Resource Collection
        $orders = $this->resource['orders_pagination'];
        return [

            'sales' => [
                'today' => [
                    'value' => $todaySales,
                    'formatted' => number_format($todaySales, 0, '.', ',') . ' ل.س',
                ],
                'change_percentage' => [
                    'value' => $change,
                    'formatted' => ($change >= 0 ? '+' : '') . $change . '%',
                ],
            ],
            'orders' => [
                'active' => $this->resource['active_orders'],
                'completed' => $this->resource['completed_orders'],
            ],

            'rating' => [
                'score' => $this->resource['restaurant_rating'] ?? 0,
                'total_reviews' => $this->resource['reviews_count'],
            ],
            'top_selling_items' => TopSellingItemResource::collection($this->resource['top_items']),
            'weekly' => [
                'sales' => WeeklySalesResource::collection($this->resource['weekly_sales']),
                'comparison' => new WeeklyComparisonResource($this->resource['weekly_comparison']),
            ],

            'monthly' => [
                'sales' => MonthlySalesResource::collection($this->resource['monthly_sales']),
            ],
            'recent_orders' => [
                'data' => OrderListItemResource::collection($orders),
                'pagination' => [
                    'current_page' => $orders->currentPage(),
                    'last_page'    => $orders->lastPage(),
                    'per_page'     => $orders->perPage(),
                    'total'        => $orders->total(),
                    'next_page_url' => $orders->nextPageUrl(),
                    'prev_page_url' => $orders->previousPageUrl(),
                ],
            ],
        ];
    }
}
