<?php

namespace App\Services\Vendor;

use App\Repositories\Eloquent\AdminRestaurantRepository;
use App\Repositories\Eloquent\VendorRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Storage;

class VendorProfileService
{
    protected $repository;
    protected $adminRestaurantRepository;

    public function __construct(VendorRepository $repository , AdminRestaurantRepository $adminRestaurantRepository)
    {
        $this->repository = $repository;
        $this->adminRestaurantRepository = $adminRestaurantRepository;
    }

    public function getProfile(int $managerId)
    {
        return $this->repository->getProfileByManager($managerId);
    }

public function updateProfile($id, array $data)
{
    return DB::transaction(function () use ($id, $data) {

        // 1. جلب السجل الحالي (لحذف الصور القديمة)
        $restaurant = $this->adminRestaurantRepository->findById($id);

        // 2. رفع اللوجو
        if (request()->hasFile('logo')) {

            // حذف القديم
            if ($restaurant->logo && Storage::disk('public')->exists($restaurant->logo)) {
                Storage::disk('public')->delete($restaurant->logo);
            }

            $data['logo'] = request()->file('logo')->store('restaurants/logos', 'public');
        }

        // 3. رفع صورة الغلاف
        if (request()->hasFile('cover_image')) {

            // حذف القديم
            if ($restaurant->cover_image && Storage::disk('public')->exists($restaurant->cover_image)) {
                Storage::disk('public')->delete($restaurant->cover_image);
            }

            $data['cover_image'] = request()->file('cover_image')->store('restaurants/covers', 'public');
        }

        // 4. تحديث البيانات
        $restaurant = $this->repository->updateProfile($id, $data);

        // 5. إرسال Jobs بعد نجاح الـ transaction
        if (isset($data['logo'])) {
            \App\Jobs\ProcessImageJob::dispatch($data['logo'])->afterCommit();
        }

        if (isset($data['cover_image'])) {
            \App\Jobs\ProcessImageJob::dispatch($data['cover_image'])->afterCommit();
        }

        // 6. تنظيف الكاش
        Cache::forget('home_active_restaurants');
        Cache::forget("restaurant_details_{$id}");

        return $restaurant;
    });
}
}
