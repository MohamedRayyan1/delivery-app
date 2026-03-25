<?php

namespace App\Services\Customer;

use App\Models\MenuSection;
use App\Models\MenuItem;
use Illuminate\Support\Facades\DB;

class CustomerSectionService
{
    /**
     * جلب قائمة الأقسام الرئيسية
     */
    public function listSections()
    {
        return MenuSection::orderBy('id', 'asc')->get();
    }

    /**
     * جلب تفاصيل القسم مع المطاعم والوجبات (المفضل أولاً)
     */
    public function getSectionRestaurants(int $sectionId)
    {
        $userId = auth()->id();
        $section = MenuSection::findOrFail($sectionId);

        // 1. جلب المطاعم المرتبطة بالقسم مع وضع المفضلة في البداية
        $restaurants = $section->restaurants()
            ->where('restaurants.status', 'active')
            // نستخدم Left Join لمعرفة إذا كان المطعم في مفضلة المستخدم الحالي
            ->leftJoin('favorite_restaurants', function ($join) use ($userId) {
                $join->on('restaurants.id', '=', 'favorite_restaurants.restaurant_id')
                     ->where('favorite_restaurants.user_id', '=', $userId);
            })
            // اختيار الحقول: نأخذ كل حقول المطعم، ومعرف المفضلة لنعرف الحالة بالريسورس
            ->select('restaurants.*', 'favorite_restaurants.id as is_fav_id')
            // الترتيب: المفضل أولاً (الموجود في جدول المفضلة يكون 1)، ثم المميز، ثم الأحدث
            ->orderByRaw('CASE WHEN favorite_restaurants.id IS NOT NULL THEN 1 ELSE 0 END DESC')
            ->orderByDesc('restaurants.is_featured')
            ->orderBy('restaurants.id', 'desc')
            ->get();

        $restaurantIds = $restaurants->pluck('id');

        // 2. جلب الوجبات التابعة لهذه المطاعم مع وضع المفضلة في البداية
        $items = MenuItem::whereHas('subSection', function ($query) use ($restaurantIds) {
                $query->whereIn('restaurant_id', $restaurantIds);
            })
            ->where('menu_items.is_available', true)
            // Left Join مع جدول مفضلة الوجبات
            ->leftJoin('favorite_items', function ($join) use ($userId) {
                $join->on('menu_items.id', '=', 'favorite_items.item_id')
                     ->where('favorite_items.user_id', '=', $userId);
            })
            // اختيار حقول الوجبة مع معرف المفضلة
            ->select('menu_items.*', 'favorite_items.id as is_fav_id')
            // الترتيب: المفضل أولاً، ثم عشوائي لبقية الوجبات لإعطاء تنوع في واجهة "جاهز"
            ->orderByRaw('CASE WHEN favorite_items.id IS NOT NULL THEN 1 ELSE 0 END DESC')
            ->inRandomOrder()
            // تحميل العلاقات اللازمة للريسورس (القسم الفرعي والمطعم التابع له)
            ->with(['extras', 'subSection.restaurant' => function ($query) {
                $query->select('id', 'name');
            }])
            ->take(15)
            ->get();

        return [
            'section' => $section,
            'restaurants' => $restaurants,
            'items' => $items,
        ];
    }
}
