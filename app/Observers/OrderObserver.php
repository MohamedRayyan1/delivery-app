<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\DriverDailyStat;
use Illuminate\Support\Facades\DB;

class OrderObserver
{
    public function updated(Order $order): void
    {
        // التحقق من تغير الحالة إلى مكتمل فقط
        if ($order->isDirty('status') && $order->status === 'completed' && $order->delivered_at) {

            $driver = $order->driver;
            if (!$driver) return;

            // حساب ربح هذا الطلب
            $orderProfit = $order->delivery_fee * ($order->applied_driver_share / 100);
            $statDate = $order->delivered_at->format('Y-m-d');

            DB::transaction(function () use ($driver, $orderProfit, $statDate) {
                // 1. تحديث إجمالي الأرباح في جدول السائق (موجود مسبقاً)
                $driver->increment('total_earnings', $orderProfit);

                // 2. تحديث أو إنشاء سجل الإحصائيات اليومية (الجديد)
                // نستخدم upsert لضمان الأداء العالي وتجنب مشاكل التزامن
                DriverDailyStat::upsert(
                    [
                        [
                            'driver_id' => $driver->id,
                            'stat_date' => $statDate,
                            'earnings' => $orderProfit,
                            'completed_orders' => 1,
                            'updated_at' => now(),
                            'created_at' => now(),
                        ]
                    ],
                    ['driver_id', 'stat_date'], // القيود الفريدة
                    ['earnings' => DB::raw('earnings + VALUES(earnings)'), 'completed_orders' => DB::raw('completed_orders + VALUES(completed_orders)')]
                );
            });
        }
    }
}
