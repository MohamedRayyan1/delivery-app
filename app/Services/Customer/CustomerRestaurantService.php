<?php

namespace App\Services\Customer;

use App\Repositories\Eloquent\CustomerRestaurantRepository;
use Illuminate\Support\Facades\Cache;

class CustomerRestaurantService
{
    protected $repository;

    public function __construct(CustomerRestaurantRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getRestaurantsList(array $filters = [])
    {
        return $this->repository->getActiveRestaurants($filters);
    }

    public function getRestaurantFullMenu(int $restaurantId)
    {
        return Cache::rememberForever("customer_restaurant_menu_{$restaurantId}", function () use ($restaurantId) {
            return $this->repository->getRestaurantWithFullMenu($restaurantId);
        });
    }
}
