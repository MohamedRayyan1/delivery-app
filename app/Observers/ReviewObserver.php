<?php

namespace App\Observers;

use App\Models\Review;
use App\Models\Restaurant;

class ReviewObserver
{
    
    public function created(Review $review): void
    {
        // نتحقق أن التقييم يخص مطعماً ولديه تقييم فعلي
        if ($review->restaurant_id && $review->restaurant_rating) {

            $restaurant = Restaurant::find($review->restaurant_id);

            if ($restaurant) {
                // جلب عدد التقييمات السابقة التي لها تقييم للمطعم فقط
                $oldCount = Review::where('restaurant_id', $restaurant->id)
                    ->whereNotNull('restaurant_rating')
                    ->where('id', '!=', $review->id)   // نستثني التقييم الحالي
                    ->count();

                $oldAvg     = (float) $restaurant->rating;
                $newRating  = (float) $review->restaurant_rating;

                // الصيغة الرياضية الفعّالة (Incremental Average)
                $newAvg = $oldCount === 0
                    ? $newRating
                    : (($oldAvg * $oldCount) + $newRating) / ($oldCount + 1);

                // تحديث التقييم في جدول المطاعم (بدون إعادة حساب كل التقييمات)
                $restaurant->update([
                    'rating' => round($newAvg, 2)
                ]);
            }
        }
    }
}
