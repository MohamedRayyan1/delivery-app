<?php

namespace App\Observers;

use App\Models\Review;
use App\Models\DriverDailyStat;
use Illuminate\Support\Facades\DB;

class ReviewObserver
{
    public function created(Review $review): void
    {
        if (!$review->driver_id || !$review->driver_rating) return;

        $driverId = $review->driver_id;
        $rating = (float) $review->driver_rating;
        // نستخدم تاريخ إنشاء المراجعة أو تاريخ الطلب المرتبط
        $statDate = $review->created_at->format('Y-m-d');

        DB::transaction(function () use ($driverId, $rating, $statDate) {
            // تحديث إحصائيات اليوم الخاصة بالتقييم
            DriverDailyStat::upsert(
                [
                    [
                        'driver_id' => $driverId,
                        'stat_date' => $statDate,
                        'rating_sum' => $rating,
                        'rating_count' => 1,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                ],
                ['driver_id', 'stat_date'],
                ['rating_sum' => DB::raw('rating_sum + VALUES(rating_sum)'), 'rating_count' => DB::raw('rating_count + VALUES(rating_count)')]
            );
        });
    }
}
