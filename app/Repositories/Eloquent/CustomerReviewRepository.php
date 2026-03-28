<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Models\Review;

class CustomerReviewRepository
{
    public function getOrderForReview(int $userId, int $orderId)
    {
        return Order::where('id', $orderId)
            ->where('user_id', $userId)
            ->firstOrFail();
    }

    public function upsertReview(array $matchAttributes, array $data)
    {
        return Review::updateOrCreate($matchAttributes, $data);
    }

    public function getUserReviews(int $userId, int $perPage = 15)
    {
        return Review::with(['restaurant:id,name,logo', 'driver.user:id,name', 'order:id,created_at'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->cursorPaginate($perPage);
    }

    public function deleteReview(int $userId, int $reviewId)
    {
        return Review::where('id', $reviewId)
            ->where('user_id', $userId)
            ->delete();
    }
}
