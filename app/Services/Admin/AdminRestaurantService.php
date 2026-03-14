<?php

namespace App\Services\Admin;

use App\Repositories\Contracts\AdminRestaurantRepositoryInterface;
use DB;
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
            return DB::transaction(function () use ($data) {
                // 1. استخراج الـ IDs الخاصة بالأقسام قبل حفظ المطعم
                $sectionIds = $data['menu_section_ids'];
                unset($data['menu_section_ids']);

                // 3. إنشاء المطعم (الـ Observer سيتكفل بترقية رتبة المستخدم إلى vendor)
                $restaurant = $this->repository->create($data);
                Cache::forget('home_active_restaurants');

            // 4. الربط في الجدول الوسيط menu_section_restaurant
            $restaurant->sections()->sync($sectionIds);

            return $restaurant;
        });
        }


    public function updateRestaurant(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $restaurant = $this->repository->findById($id);

            // تحديث الأقسام إذا تم تمريرها
            if (isset($data['menu_section_ids'])) {
                $restaurant->sections()->sync($data['menu_section_ids']);
                unset($data['menu_section_ids']);
            }
        $restaurant = $this->repository->update($id, $data);
        Cache::forget('home_active_restaurants');
        return $restaurant;
        });
    }

    public function deleteRestaurant($id)
    {
        $this->repository->delete($id);
        Cache::forget('home_active_restaurants');
    }
}
