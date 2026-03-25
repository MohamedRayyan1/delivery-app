<?php

namespace App\Repositories\Eloquent;

use App\Models\Restaurant;
use App\Models\MenuItem;
use Illuminate\Support\Facades\DB;

class CustomerFavoriteRepository
{
    public function toggleFavoriteRestaurant(int $userId, int $restaurantId): bool
    {
        $exists = DB::table('favorite_restaurants')
            ->where('user_id', $userId)
            ->where('restaurant_id', $restaurantId)
            ->exists();

        if ($exists) {
            DB::table('favorite_restaurants')
                ->where('user_id', $userId)
                ->where('restaurant_id', $restaurantId)
                ->delete();
            return false;
        }

        DB::table('favorite_restaurants')->insert([
            'user_id' => $userId,
            'restaurant_id' => $restaurantId,
        ]);

        return true;
    }

    public function toggleFavoriteItem(int $userId, int $itemId): bool
    {
        $exists = DB::table('favorite_items')
            ->where('user_id', $userId)
            ->where('item_id', $itemId)
            ->exists();

        if ($exists) {
            DB::table('favorite_items')
                ->where('user_id', $userId)
                ->where('item_id', $itemId)
                ->delete();
            return false;
        }

        DB::table('favorite_items')->insert([
            'user_id' => $userId,
            'item_id' => $itemId,
        ]);

        return true;
    }

    public function getUserFavoriteRestaurants(int $userId)
    {
        return Restaurant::select('restaurants.*')
            ->join('favorite_restaurants', 'restaurants.id', '=', 'favorite_restaurants.restaurant_id')
            ->where('favorite_restaurants.user_id', $userId)
            ->where('restaurants.status', 'active')
            ->orderBy('favorite_restaurants.created_at', 'desc')
            ->get();
    }

    public function getUserFavoriteItems(int $userId)
    {
        return MenuItem::select('menu_items.*')
            ->join('favorite_items', 'menu_items.id', '=', 'favorite_items.item_id')
            ->where('favorite_items.user_id', $userId)
            ->where('menu_items.is_available', true)
            ->with(['subSection.section.restaurants' => function ($query) {
                $query->select('restaurants.id', 'restaurants.name')->where('restaurants.status', 'active');
            }])
            ->orderBy('favorite_items.created_at', 'desc')
            ->get();
    }

}
