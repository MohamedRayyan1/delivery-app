<?php

namespace App\Repositories\Eloquent;

use App\Models\MenuSection;
use App\Models\SubMenuSection;
use App\Models\MenuItem;

class VendorMenuRepository
{
    // --- Sections ---
    public function createSection(array $data) {
         return MenuSection::create($data);
    }

    public function updateSection(int $id, int $resId, array $data) {
        return MenuSection::where('id', $id)->where('restaurant_id', $resId)->update($data);
    }

    public function deleteSection(int $id, int $resId) {
        return MenuSection::where('id', $id)->where('restaurant_id', $resId)->delete();
    }

    public function findSection(int $id, int $resId) {
        return MenuSection::where('id', $id)->where('restaurant_id', $resId)->first();
    }

    // --- Sub Sections ---
    public function createSubSection(array $data) { return SubMenuSection::create($data); }

    public function updateSubSection(int $id, int $resId, array $data) {
        return SubMenuSection::where('id', $id)
            ->whereHas('section', fn($q) => $q->where('restaurant_id', $resId))
            ->update($data);
    }

    public function deleteSubSection(int $id, int $resId) {
        return SubMenuSection::where('id', $id)
            ->whereHas('section', fn($q) => $q->where('restaurant_id', $resId))
            ->delete();
    }

        public function findSubSection(int $id, int $resId)
    {
        return SubMenuSection::where('id', $id)
            ->whereHas('section', function ($query) use ($resId) {
                $query->where('restaurant_id', $resId);
            })
            ->firstOrFail();
    }

    // --- Menu Items ---
    public function createItem(array $data) { return MenuItem::create($data); }

    public function updateItem(int $id, int $resId, array $data) {
        return MenuItem::where('id', $id)
            ->whereHas('subSection.section', fn($q) => $q->where('restaurant_id', $resId))
            ->update($data);
    }

    public function deleteItem(int $id, int $resId) {
        return MenuItem::where('id', $id)
            ->whereHas('subSection.section', fn($q) => $q->where('restaurant_id', $resId))
            ->delete();
    }

    public function findItem(int $id, int $resId)
    {
        return MenuItem::where('id', $id)
            ->whereHas('subSection.section', function ($query) use ($resId) {
                $query->where('restaurant_id', $resId);
            })
            ->firstOrFail();
    }

    public function getMenuTree(int $resId)
    {
        // جلب المطعم مع كامل هيكلية المنيو بطلب واحد فقط من الداتا بيس
        return \App\Models\MenuSection::where('restaurant_id', $resId)
            ->with(['subSections.items'])
            ->get();
    }
}
