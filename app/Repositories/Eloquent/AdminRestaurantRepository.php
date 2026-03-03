<?php

namespace App\Repositories\Eloquent;

use App\Models\Restaurant;
use App\Repositories\Contracts\AdminRestaurantRepositoryInterface;

class AdminRestaurantRepository implements AdminRestaurantRepositoryInterface
{
    public function paginate(int $perPage)
    {
        return Restaurant::latest()->paginate($perPage);
    }

    public function findById(int $id)
    {
        return Restaurant::findOrFail($id);
    }

    public function create(array $data)
    {
        return Restaurant::create($data);
    }

    public function update(int $id, array $data)
    {
        $restaurant = $this->findById($id);
        $restaurant->update($data);
        return $restaurant;
    }

    public function delete(int $id)
    {
        $restaurant = $this->findById($id);
        return $restaurant->delete();
    }
}
