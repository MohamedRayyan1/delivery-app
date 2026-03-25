<?php

namespace App\Repositories\Eloquent;

use App\Models\Restaurant;

class CustomerSearchRepository
{
public function searchItemsGroupedByRestaurant(string $keyword, int $perPage = 10)
{
    return Restaurant::where('status', 'active')
        // البحث عن المطاعم التي لديها وجبات تطابق الكلمة المفتاحية في أقسامها الفرعية
        ->whereHas('subMenuSections.items', function ($query) use ($keyword) {
            $query->where('name', 'LIKE', "%{$keyword}%")
                  ->where('is_available', true);
        })
        // تحميل الأقسام الفرعية والوجبات المطابقة فقط (Eager Loading Optimization)
        ->with(['subMenuSections' => function ($subQuery) use ($keyword) {
            $subQuery->whereHas('items', function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                  ->where('is_available', true);
            })->with(['items' => function ($itemQuery) use ($keyword) {
                $itemQuery->where('name', 'LIKE', "%{$keyword}%")
                          ->where('is_available', true);
            }]);
        }])
        ->cursorPaginate($perPage);
}
}
