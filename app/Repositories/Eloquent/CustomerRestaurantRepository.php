<?php

namespace App\Repositories\Eloquent;

use App\Models\Restaurant;

class CustomerRestaurantRepository
{
    public function getActiveRestaurants(array $filters = [], int $perPage = 15)
    {
        $query = Restaurant::where('status', 'active');

        if (isset($filters['search']) && !empty($filters['search'])) {
            $query->where('name', 'LIKE', '%' . $filters['search'] . '%');
        }

        if (isset($filters['city']) && !empty($filters['city'])) {
            $query->where('city', $filters['city']);
        }

        $query->orderBy('is_featured', 'desc')->orderBy('id', 'desc');

        return $query->cursorPaginate($perPage);
    }

    public function getRestaurantWithFullMenu(int $restaurantId)
    {
        return Restaurant::where('id', $restaurantId)
            ->where('status', 'active')
            ->with(['sections' => function ($sectionQuery) {
                $sectionQuery->with(['subSections' => function ($subSectionQuery) {
                    $subSectionQuery->with(['items' => function ($itemQuery) {
                        $itemQuery->where('is_available', true);
                    }]);
                }]);
            }])
            ->firstOrFail();
    }
}
