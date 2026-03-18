<?php

namespace App\Services\Vendor;

use App\Repositories\Eloquent\VendorExtraRepository;
use Illuminate\Support\Facades\Cache;
use Exception;

class VendorExtraService
{
    protected $repository;

    public function __construct(VendorExtraRepository $repository)
    {
        $this->repository = $repository;
    }

    public function addExtra(int $resId, array $data)
    {
        // $isOwner = $this->repository->checkMenuItemOwnership($data['menu_item_id'], $resId);

        // if (!$isOwner) {
        //     throw new Exception('لا تملك صلاحية إضافة تفاصيل لهذه الوجبة، أو الوجبة غير موجودة.');
        // }

        $extra = $this->repository->createExtra($data);

        Cache::forget("customer_restaurant_menu_{$resId}");

        return $extra;
    }

    public function updateExtra(int $id, int $resId, array $data)
    {
        $updated = $this->repository->updateExtra($id, $resId, $data);

        if (!$updated) {
            throw new Exception('الإضافة غير موجودة أو لا تملك صلاحية تعديلها.');
        }

        Cache::forget("customer_restaurant_menu_{$resId}");

        return true;
    }

    public function deleteExtra(int $id, int $resId)
    {
        $deleted = $this->repository->deleteExtra($id, $resId);

        if (!$deleted) {
            throw new Exception('الإضافة غير موجودة أو لا تملك صلاحية حذفها.');
        }

        Cache::forget("customer_restaurant_menu_{$resId}");

        return true;
    }
}
