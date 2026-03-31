<?php

namespace App\Repositories\Eloquent;

use Illuminate\Support\Facades\DB;

class RestaurantReportRepository
{
    // 👥 عدد العملاء (Distinct)
    public function countCustomers($restaurantId, $from = null, $to = null)
    {
        return DB::table('orders')
            ->where('restaurant_id', $restaurantId)
            ->where('status', 'delivered')
            ->when($from, fn($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('created_at', '<=', $to))
            ->distinct('user_id')
            ->count('user_id');
    }

    // 💰 صافي الدخل
    public function getNetIncome($restaurantId, $from = null, $to = null)
    {
        return DB::table('orders')
            ->where('restaurant_id', $restaurantId)
            ->where('status', 'delivered')
            ->when($from, fn($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('created_at', '<=', $to))
            ->selectRaw("
                SUM(
                    (subtotal - discount_amount)
                    * (1 - applied_restaurant_commission / 100)
                ) as net_income
            ")
            ->value('net_income') ?? 0;
    }

    // 📊 إجمالي المبيعات
    public function getTotalSales($restaurantId, $from = null, $to = null)
    {
        return DB::table('orders')
            ->where('restaurant_id', $restaurantId)
            ->where('status', 'delivered')
            ->when($from, fn($q) => $q->whereDate('created_at', '>=', $from))
            ->when($to, fn($q) => $q->whereDate('created_at', '<=', $to))
            ->sum('subtotal');
    }

    public function getMonthlyNetIncome($restaurantId, $year)
    {
        return DB::table('orders')
            ->where('restaurant_id', $restaurantId)
            ->where('status', 'delivered')
            ->whereYear('created_at', $year)
            ->selectRaw("
                MONTH(created_at) as month,
                SUM(
                    (subtotal - discount_amount)
                    * (1 - applied_restaurant_commission / 100)
                ) as total
            ")
            ->groupByRaw("MONTH(created_at)")
            ->pluck('total', 'month'); // key = month
    }
}
