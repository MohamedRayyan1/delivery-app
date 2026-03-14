<?php

namespace App\Services\Customer;

use App\Models\MenuSection;
use App\Models\Order;

class CustomerSectionService
{
    public function listSections()
    {
        return MenuSection::orderBy('id', 'asc')->get();
    }

    public function getSectionRestaurants(int $sectionId)
    {
        $section = MenuSection::findOrFail($sectionId);

        // المطاعم المرتبطة بهذا القسم
        $baseQuery = $section->restaurants()->where('status', 'active');

        $allRestaurants = (clone $baseQuery)
            ->orderByDesc('is_featured')
            ->orderBy('id', 'desc')
            ->get();

        // الأكثر طلباً: نعتمد على عدد الطلبات المرتبطة بالمطعم
        $popularRestaurants = (clone $baseQuery)
            ->withCount(['orders as orders_count' => function ($query) {
                $query->where('status', 'delivered');
            }])
            ->orderByDesc('orders_count')
            ->orderByDesc('rating')
            ->take(10)
            ->get();

        return [
            'section' => $section,
            'restaurants' => $allRestaurants,
            'popular_restaurants' => $popularRestaurants,
        ];
    }
}

