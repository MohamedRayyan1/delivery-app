<?php

namespace App\Repositories\Eloquent;

use App\Models\Restaurant;

class VendorRepository
{
    public function getProfileByManager(int $managerId)
    {
        return Restaurant::where('manager_user_id', $managerId)->firstOrFail();
    }

    public function updateProfile(int $id, array $data)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->update($data);
        return $restaurant;
    }
}
