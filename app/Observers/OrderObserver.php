<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    public function updated(Order $order): void
    {
        // إذا تغيرت حالة الطلب إلى مكتمل (completed) ولم يكن قد حُسب سابقاً
        // نتحقق من الحالة القديمة والحالة الجديدة
        if ($order->isDirty('status') && $order->status === 'completed') {

            $driver = $order->driver;

            if ($driver) {
                // حساب ربح هذا الطلب فقط
                $orderProfit = $order->delivery_fee * ($order->applied_driver_share / 100);

                // تحديث حقل الإجمالي في جدول السائق بعملية حسابية ذرية (Atomic) لمنع تضارب البيانات
                $driver->increment('total_earnings', $orderProfit);
            }
        }
    }
}
