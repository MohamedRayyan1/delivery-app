<?php

namespace App\Repositories\Eloquent;

use App\Models\Ad;
use App\Models\MenuSection;
use App\Models\MenuItem;

class CustomerHomeRepository
{
    public function getActiveAds()
    {
        return Ad::where('is_active', true)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getSectionsWithRestaurants()
    {
        return MenuSection::with(['restaurants' => function ($query) {
                $query->select('restaurants.id', 'restaurants.name', 'restaurants.logo', 'restaurants.cover_image', 'restaurants.delivery_cost', 'restaurants.delivery_time', 'restaurants.status')
                      ->where('restaurants.status', 'active');
            }])
            ->whereHas('restaurants', function ($query) {
                $query->where('restaurants.status', 'active');
            })
            ->get();
    }

    public function getPopularItems()
    {
        return MenuItem::with(['subSection.section.restaurants' => function ($query) {
                $query->select('restaurants.id', 'restaurants.name')
                      ->where('restaurants.status', 'active');
            }])
            ->whereHas('subSection.section.restaurants', function ($query) {
                $query->where('restaurants.status', 'active');
            })
            ->where('is_available', true)
            ->where('is_featured', true)
            ->inRandomOrder()
            ->take(10)
            ->get();
    }
}
