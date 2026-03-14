<?php

namespace App\Repositories\Eloquent;

use App\Models\Ad;

class VendorAdRepository
{
    public function createAd(array $data)
    {
        return Ad::create($data);
    }

    public function updateAd(int $id, int $restaurantId, array $data)
    {
        return Ad::where('id', $id)
            ->where('restaurant_id', $restaurantId)
            ->update($data);
    }

    public function findAdById(int $id, int $restaurantId)
    {
        return Ad::where('id', $id)
            ->where('restaurant_id', $restaurantId)
            ->firstOrFail();
    }

    public function getRestaurantAds(int $restaurantId)
    {
        return Ad::where('restaurant_id', $restaurantId)->orderBy('created_at', 'desc')->get();
    }

    public function deleteAd(int $id, int $restaurantId)
    {
        return Ad::where('id', $id)
            ->where('restaurant_id', $restaurantId)
            ->delete();
    }
}
