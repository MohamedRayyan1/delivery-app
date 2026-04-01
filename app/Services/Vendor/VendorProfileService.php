<?php

namespace App\Services\Vendor;

use App\Repositories\Eloquent\VendorRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class VendorProfileService
{
    protected $repository;

    public function __construct(VendorRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getProfile(int $managerId)
    {
        return $this->repository->getProfileByManager($managerId);
    }

public function updateProfile($id, array $data)
{
    return DB::transaction(function () use ($id, $data) {
        // 1. معالجة الرفع (نستخدم request() مباشرة للتأكد من وجود الملف)
        if (request()->hasFile('logo')) {
            $data['logo'] = request()->file('logo')->store('restaurants/logos', 'public');
        }

        if (request()->hasFile('cover_image')) {
            $data['cover_image'] = request()->file('cover_image')->store('restaurants/covers', 'public');
        }

        // 2. التحديث في قاعدة البيانات
        $restaurant = $this->repository->updateProfile($id, $data);

        // 3. إرسال المهمة للـ Queue (نتأكد من وجود القيمة بالمصفوفة قبل الإرسال)
        if (array_key_exists('logo', $data)) {
            dispatch(new \App\Jobs\ProcessImageJob($restaurant, 'logo', $data['logo']));
        }

        if (array_key_exists('cover_image', $data)) {
            dispatch(new \App\Jobs\ProcessImageJob($restaurant, 'cover_image', $data['cover_image']));
        }

        Cache::forget('home_active_restaurants');
        Cache::forget("restaurant_details_{$id}");


        return $restaurant;
    });

}
}
