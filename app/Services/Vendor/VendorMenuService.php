<?php

namespace App\Services\Vendor;

use App\Models\SubMenuSection;
use App\Repositories\Eloquent\VendorMenuRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class VendorMenuService
{
    protected $repository;

    public function __construct(VendorMenuRepository $repository) {
        $this->repository = $repository;
    }

    private function clearCache($resId) {
        Cache::forget("restaurant_menu_{$resId}");
    }

    // --- Sections ---

public function addSection( $data) {
    return DB::transaction(function () use ( $data) {
        // 1. معالجة رفع الصورة إذا وجدت
        if (request()->hasFile('image')) {
            $data['image'] = request()->file('image')->store('menu/sections', 'public');
        }

        $section = $this->repository->createSection($data);

        // ربط القسم بالمطعم في الجدول الوسيط (حتى يمكن أن يرتبط لاحقاً بعدة مطاعم)
        // $section->restaurants()->syncWithoutDetaching([$resId]);

        // 2. إرسال المهمة للـ Queue للمعالجة في الخلفية
        if (array_key_exists('image', $data)) {
            dispatch(new \App\Jobs\ProcessImageJob($section, 'image', $data['image']));
        }

        // $this->clearCache($resId);

        return [
        'id' => $section->id,
        'name' => $section->name,
        'image' => $section->image ? asset('storage/' . $section->image) : null,
        'created_at' => $section->created_at->toDateTimeString(),
        ];
    });
}

// تحديث قسم موجود
public function updateSection($id, $data) {
    return DB::transaction(function () use ($id, $data) {
        // 1. معالجة رفع الصورة الجديدة
        if (request()->hasFile('image')) {
            $data['image'] = request()->file('image')->store('menu/sections', 'public');
        }

        // 2. تحديث البيانات في الداتا بيس
        $this->repository->updateSection($id,  $data);
        $section = $this->repository->findSection($id);

        // 3. إرسال للـ Queue فقط إذا تم رفع صورة فعلياً
        if (array_key_exists('image', $data)) {
            dispatch(new \App\Jobs\ProcessImageJob($section, 'image', $data['image']));
        }

        return [
        'id' => $section->id,
        'name' => $section->name,
        'image' => $section->image ? asset('storage/' . $section->image) : null,
        'created_at' => $section->created_at->toDateTimeString(),
        ];
    });
}

    public function deleteSection($id) {
        return DB::transaction(function () use ($id) {
            $deleted = $this->repository->deleteSection($id);
            return $deleted;
        });
    }

    // --- Sub Sections ---

public function addSubSection($resId, $data) {
    return DB::transaction(function () use ($resId, $data) {
        if (request()->hasFile('image')) {
            $data['image'] = request()->file('image')->store('menu/sub-sections', 'public');
        }
        $data['restaurant_id'] = $resId; // تأكد من تمرير restaurant_id لإنشاء القسم الفرعي مرتبطاً بالمطعم
        $sub = $this->repository->createSubSection($data);

        if (array_key_exists('image', $data)) {
            dispatch(new \App\Jobs\ProcessImageJob($sub, 'image', $data['image']));
        }

        $this->clearCache($resId);
        return [
        'id' => $sub->id,
        'name' => $sub->name,
        'image' => $sub->image ? asset('storage/' . $sub->image) : null,
        'created_at' => $sub->created_at->toDateTimeString(),
        ];
    });
}

// تحديث قسم فرعي
public function updateSubSection($id, $resId, $data) {
    return DB::transaction(function () use ($id, $resId, $data) {
        // 1. التحقق من رفع صورة جديدة
        if (request()->hasFile('image')) {
            $data['image'] = request()->file('image')->store('menu/sub-sections', 'public');
        }

        // 2. التحديث في الداتا بيس وجلب الموديل المحدث
        $this->repository->updateSubSection($id, $resId, $data);
        $subSection = $this->repository->findSubSection($id);

        // 3. التوجيه للـ Queue في حال تم تغيير الصورة
        if (array_key_exists('image', $data)) {
            dispatch(new \App\Jobs\ProcessImageJob($subSection, 'image', $data['image']));
        }

        $this->clearCache($resId);
        return [
        'id' => $subSection->id,
        'name' => $subSection->name,
        'image' => $subSection->image ? asset('storage/' . $subSection->image) : null,
        'created_at' => $subSection->created_at->toDateTimeString(),
        ];

    });
}

    public function deleteSubSection($id, $resId) {
        $deleted = $this->repository->deleteSubSection($id, $resId);
        $this->clearCache($resId);
        return $deleted;
    }


    // --- Items ---
   // إضافة وجبة جديدة
public function addItem($resId, $data) {
    return DB::transaction(function () use ($resId, $data) {
        // 1. معالجة رفع الملف فيزيائياً لمجلد الوجبات
        if (request()->hasFile('image')) {
            $data['image'] = request()->file('image')->store('menu/items', 'public');
        }

        $item = $this->repository->createItem($data);

        // 2. إرسال المهمة للـ Queue (تستخدم مصفوفة $data للتأكد من وجود المفتاح)
        if (array_key_exists('image', $data)) {
            dispatch(new \App\Jobs\ProcessImageJob($item, 'image', $data['image']));
        }

        $this->clearCache($resId);
        return [
        'id' => $item->id,
        'sub_section_id' => $item->sub_section_id,
        'name' => $item->name,
        'description' => $item->description,
        'price' => $item->price,
        'discount_price' => $item->discount_price,
        'image' => $item->image ? asset('storage/' . $item->image) : null,
        'is_available' => (bool)$item->is_available,
        'is_featured' => (bool)$item->is_featured,
        'created_at' => $item->created_at->toDateTimeString(),
        ];

    });
}

// تحديث وجبة موجودة
public function updateItem($id, $resId, $data) {
    return DB::transaction(function () use ($id, $resId, $data) {
        // 1. معالجة رفع الصورة الجديدة (إذا وجدت)
        if (request()->hasFile('image')) {
            $data['image'] = request()->file('image')->store('menu/items', 'public');
        }

        // 2. تحديث الداتا بيس وجلب الموديل المحدث للـ Job
        $this->repository->updateItem($id, $resId, $data);
        $item = $this->repository->findItem($id, $resId);

        // 3. التحديث في الخلفية عبر الـ Queue
        if (array_key_exists('image', $data)) {
            dispatch(new \App\Jobs\ProcessImageJob($item, 'image', $data['image']));
        }

        $this->clearCache($resId);
                return [
        'id' => $item->id,
        'sub_section_id' => $item->sub_section_id,
        'name' => $item->name,
        'description' => $item->description,
        'price' => $item->price,
        'discount_price' => $item->discount_price,
        'image' => $item->image ? asset('storage/' . $item->image) : null,
        'is_available' => (bool)$item->is_available,
        'is_featured' => (bool)$item->is_featured,
        'created_at' => $item->created_at->toDateTimeString(),
        ];

    });
}


    public function deleteItem($id, $resId) {
        $deleted = $this->repository->deleteItem($id, $resId);
        $this->clearCache($resId);
        return $deleted;
    }

    public function getFullMenu(int $resId)
    {
        // بنستخدم الـ Repository لجلب الشجرة كاملة
        return $this->repository->getMenuTree($resId);
    }

}
