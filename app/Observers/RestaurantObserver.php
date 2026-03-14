<?php

namespace App\Observers;

use App\Models\Restaurant;
use App\Models\User;

class RestaurantObserver
{
    /**
     * Handle the Restaurant "created" event.
     */
    public function created(Restaurant $restaurant): void
    {
        // تحديث رتبة المستخدم المرتبط ليصبح مدير (vendor/manager)
        if ($restaurant->manager_user_id) {
            User::where('id', $restaurant->manager_user_id)
                ->update(['role' => 'vendor']); // أو 'manager' حسب تسميتك للرتب
        }
    }

    /**
     * Handle the Restaurant "updated" event.
     */
    public function updated(Restaurant $restaurant): void
    {
       // إذا تغير الـ manager_user_id، نقوم بترقية المدير الجديد
        if ($restaurant->isDirty('manager_user_id')) {
            $newManagerId = $restaurant->manager_user_id;

            User::where('id', $newManagerId)
                ->update(['role' => 'vendor']);

            // ملاحظة اختيارية: يمكن هنا سحب رتبة المدير القديم إذا لم يعد يملك مطاعم أخرى
        }
    }

    /**
     * Handle the Restaurant "deleted" event.
     */
    public function deleted(Restaurant $restaurant): void
    {
        //
    }

    /**
     * Handle the Restaurant "restored" event.
     */
    public function restored(Restaurant $restaurant): void
    {
        //
    }

    /**
     * Handle the Restaurant "force deleted" event.
     */
    public function forceDeleted(Restaurant $restaurant): void
    {
        //
    }
}
