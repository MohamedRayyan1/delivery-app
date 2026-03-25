<?php

namespace App\Services\Customer;

use App\Repositories\Eloquent\CustomerFavoriteRepository;
use App\Models\Restaurant;
use App\Models\MenuItem;
use Exception;

class CustomerFavoriteService
{
    protected $repository;

    public function __construct(CustomerFavoriteRepository $repository)
    {
        $this->repository = $repository;
    }

    public function toggleRestaurant(int $userId, int $restaurantId): array
    {
        $restaurantExists = Restaurant::where('id', $restaurantId)
            ->where('status', 'active')
            ->exists();

        if (!$restaurantExists) {
            throw new Exception('المطعم غير موجود أو غير متاح حالياً.');
        }

        $isAdded = $this->repository->toggleFavoriteRestaurant($userId, $restaurantId);

        return [
            'is_favorite' => $isAdded,
            'message' => $isAdded ? 'تمت إضافة المطعم إلى المفضلة بنجاح.' : 'تمت إزالة المطعم من المفضلة بنجاح.'
        ];
    }

    public function toggleItem(int $userId, int $itemId): array
    {
        $itemExists = MenuItem::where('id', $itemId)
            ->where('is_available', true)
            ->exists();

        if (!$itemExists) {
            throw new Exception('الوجبة غير موجودة أو غير متاحة حالياً.');
        }

        $isAdded = $this->repository->toggleFavoriteItem($userId, $itemId);

        return [
            'is_favorite' => $isAdded,
            'message' => $isAdded ? 'تمت إضافة الوجبة إلى المفضلة بنجاح.' : 'تمت إزالة الوجبة من المفضلة بنجاح.'
        ];
    }

    public function getUserFavorites(int $userId): array
    {
        return [
            'restaurants' => $this->repository->getUserFavoriteRestaurants($userId),
            'items' => $this->repository->getUserFavoriteItems($userId),
        ];
    }
}
