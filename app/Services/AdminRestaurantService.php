<?php

namespace App\Services;

use App\Repositories\Contracts\AdminRestaurantRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class AdminRestaurantService
{
    protected $repository;

    public function __construct(AdminRestaurantRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function listRestaurants($perPage = 15)
    {
        return $this->repository->paginate($perPage);
    }

    public function getRestaurant($id)
    {
        return $this->repository->findById($id);
    }

    public function storeRestaurant(array $data)
    {
        $restaurant = $this->repository->create($data);
        Cache::forget('home_active_restaurants');
        return $restaurant;
    }

    public function updateRestaurant($id, array $data)
    {
        $restaurant = $this->repository->update($id, $data);
        Cache::forget('home_active_restaurants');
        return $restaurant;
    }

    public function deleteRestaurant($id)
    {
        $this->repository->delete($id);
        Cache::forget('home_active_restaurants');
    }
}
