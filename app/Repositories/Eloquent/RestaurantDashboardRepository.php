<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Models\Restaurant;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RestaurantDashboardRepository
{
    /**
     * جلب إجمالي مبيعات اليوم للمطعم بناءً على الطلبات المكتملة
     */

    public function getTodaySales(int $restaurantId): float
    {
        $start = Carbon::now()->startOfDay();
        $end = Carbon::now()->endOfDay();

        return (float) Order::where('restaurant_id', $restaurantId)
            ->whereBetween('created_at', [$start, $end])
            ->whereNotIn('status', ['pending'])
            ->sum('subtotal');
    }
    /**
     * جلب إجمالي مبيعات يوم أمس للمطعم
     */
    public function getYesterdaySales(int $restaurantId): float
    {
        $start = Carbon::yesterday()->startOfDay();
        $end = Carbon::yesterday()->endOfDay();

        return (float) Order::where('restaurant_id', $restaurantId)
            ->whereBetween('created_at', [$start, $end])
            ->whereNotIn('status', ['pending'])
            ->sum('subtotal');
    }

    /**
     * جلب عدد الطلبات النشطة (قيد التحضير أو مع السائق فقط)
     */
    public function getActiveOrdersCount(int $restaurantId): int
    {
        return Order::where('restaurant_id', $restaurantId)
            ->whereIn('status', ['preparing', 'picked_up'])
            ->count();
    }
    /**
     * جلب عدد الطلبات المنتهية (تم التوصيل بنجاح)
     */
    public function getCompletedOrdersCount(int $restaurantId): int
    {
        return Order::where('restaurant_id', $restaurantId)
            ->where('status', 'delivered')
            ->count();
    }
    /**
     * جلب تقييم المطعم وعدد التقييمات
     */
    public function getRestaurantRatingStats(int $restaurantId): array
    {
        // نجلب المطعم مباشرة لنحصل على القيمة المخزنة مسبقاً
        $restaurant = Restaurant::select('id', 'rating')
            ->withCount(['reviews as reviews_count' => function ($query) {
                $query->whereNotNull('restaurant_rating');
            }])
            ->find($restaurantId);

        return [
            'rating' => $restaurant ? (float) $restaurant->rating : 0.0,
            'reviews_count' => $restaurant ? $restaurant->reviews_count : 0,
        ];
    }
    public function getTopSellingItems(int $restaurantId, ?int $limit = null): \Illuminate\Support\Collection
    {
        // 1. حساب إجمالي الكميات المباعة بشكل منفصل وآمن
        $totalSoldQuantity = \DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.restaurant_id', $restaurantId)
            ->where('orders.status', 'delivered')
            ->sum('order_items.quantity');

        // ضمان عدم القسمة على صفر
        $totalSoldQuantity = $totalSoldQuantity > 0 ? $totalSoldQuantity : 1;

        // 2. بناء الاستعلام الرئيسي للأصناف
        $query = \DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('menu_items', 'order_items.item_id', '=', 'menu_items.id')
            ->select(
                'menu_items.id as item_id',
                'menu_items.name as item_name',
                'menu_items.image as item_image',
                \DB::raw('SUM(order_items.quantity) as total_quantity'),
                // استخدام المتغير المحسوب مباشرة في الاستعلام
                \DB::raw("ROUND((SUM(order_items.quantity) * 100 / {$totalSoldQuantity}), 1) as sales_percentage")
            )
            ->where('orders.restaurant_id', $restaurantId)
            ->where('orders.status', 'delivered')
            ->groupBy('menu_items.id', 'menu_items.name', 'menu_items.image')
            ->orderByDesc('total_quantity');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }
    public function getWeeklySalesPerDay(int $restaurantId): array
    {
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::SUNDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SATURDAY);

        // 🔥 Query واحد فقط
        $sales = Order::select(
            DB::raw('DATE(delivered_at) as date'),
            DB::raw('SUM(subtotal) as total')
        )
            ->where('restaurant_id', $restaurantId)
            ->where('status', 'delivered')
            ->whereBetween('delivered_at', [$startOfWeek, $endOfWeek])
            ->groupBy(DB::raw('DATE(delivered_at)'))
            ->pluck('total', 'date') // key = date
            ->toArray();

        $result = [];

        for ($i = 0; $i < 7; $i++) {

            $date = $startOfWeek->copy()->addDays($i)->toDateString();

            $result[] = [
                'day' => Carbon::parse($date)->translatedFormat('l'),
                'total' => (float) ($sales[$date] ?? 0),
            ];
        }

        return $result;
    }
    public function getLastSixMonthsSales(int $restaurantId): array
    {
        $startDate = Carbon::now()->subMonths(5)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $sales = Order::select(
            DB::raw('DATE_FORMAT(delivered_at, "%Y-%m") as month'),
            DB::raw('SUM(subtotal) as total')
        )
            ->where('restaurant_id', $restaurantId)
            ->where('status', 'delivered')
            ->whereBetween('delivered_at', [$startDate, $endDate])
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $result = [];

        for ($i = 0; $i < 6; $i++) {

            $date = $startDate->copy()->addMonths($i);
            $key = $date->format('Y-m');

            $result[] = [
                'month' => $date->translatedFormat('F'), // اسم الشهر
                'total' => (float) ($sales[$key] ?? 0),
            ];
        }

        return $result;
    }
    public function getWeeklyComparison(int $restaurantId): array
    {
        // الأسبوع الحالي
        $currentStart = Carbon::now()->startOfWeek(Carbon::SUNDAY);
        $currentEnd = Carbon::now()->endOfWeek(Carbon::SATURDAY);

        // الأسبوع السابق
        $previousStart = $currentStart->copy()->subWeek();
        $previousEnd = $currentEnd->copy()->subWeek();

        $currentTotal = Order::where('restaurant_id', $restaurantId)
            ->where('status', 'delivered')
            ->whereBetween('delivered_at', [$currentStart, $currentEnd])
            ->sum('subtotal');

        $previousTotal = Order::where('restaurant_id', $restaurantId)
            ->where('status', 'delivered')
            ->whereBetween('delivered_at', [$previousStart, $previousEnd])
            ->sum('subtotal');

        // حساب النسبة
        if ($previousTotal > 0) {
            $change = (($currentTotal - $previousTotal) / $previousTotal) * 100;
        } else {
            $change = $currentTotal > 0 ? 100 : 0;
        }

        return [
            'current_week_total' => (float) $currentTotal,
            'previous_week_total' => (float) $previousTotal,
            'change_percentage' => round($change, 1),
        ];
    }

    /**
     * جلب قائمة الطلبات مع الترقيم
     * الترتيب: الأقدم أولاً
     */
    public function getOrdersPaginated(int $restaurantId, int $perPage = 10)
    {
        return Order::where('restaurant_id', $restaurantId)
            ->with('user:id,name')
            ->orderBy('created_at', 'asc')
            ->paginate($perPage); // استخدام الترقيم هنا
    }
}
