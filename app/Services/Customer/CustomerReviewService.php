<?php

namespace App\Services\Customer;

use App\Repositories\Eloquent\CustomerReviewRepository;
use Exception;

class CustomerReviewService
{
    protected $repository;

    public function __construct(CustomerReviewRepository $repository)
    {
        $this->repository = $repository;
    }

    public function reviewOrder(int $userId, int $orderId, array $data)
    {
        $order = $this->repository->getOrderForReview($userId, $orderId);

        if ($order->status !== 'delivered') {
            throw new Exception('لا يمكن تقييم الطلب إلا بعد استلامه.');
        }

        if (empty($data['restaurant_rating']) && empty($data['driver_rating']) && empty($data['comment'])) {
            throw new Exception('يجب تقديم تقييم للمطعم، أو للسائق، أو كتابة تعليق على الأقل.');
        }

        $matchAttributes = [
            'user_id' => $userId,
            'order_id' => $order->id,
        ];

        $reviewData = [
            'restaurant_id' => $order->restaurant_id,
            'restaurant_rating' => $data['restaurant_rating'] ?? null,
            'driver_id' => $order->driver_id,
            'driver_rating' => $data['driver_rating'] ?? null,
            'comment' => $data['comment'] ?? null,
        ];

        return $this->repository->upsertReview($matchAttributes, $reviewData);
    }

    public function getUserReviews(int $userId)
    {
        return $this->repository->getUserReviews($userId);
    }

    public function deleteReview(int $userId, int $reviewId)
    {
        $deleted = $this->repository->deleteReview($userId, $reviewId);

        if (!$deleted) {
            throw new Exception('التقييم غير موجود أو لا تملك صلاحية حذفه.');
        }

        return true;
    }
}
