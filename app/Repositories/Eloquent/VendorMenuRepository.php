<?php

namespace App\Repositories\Eloquent;

use App\Models\MenuSection;
use App\Models\SubMenuSection;
use App\Models\MenuItem;
use App\Models\Restaurant;

class VendorMenuRepository
{
    // --- Sections ---
    public function createSection(array $data) {
         return MenuSection::create($data);
    }

    public function updateSection(int $id, array $data) {
        return MenuSection::where('id', $id)->update($data);
    }

    public function deleteSection(int $id) {
        return MenuSection::where('id', $id)->delete();
    }

    public function findSection(int $id) {
        return MenuSection::where('id', $id)->first();
    }

    // --- Sub Sections ---
    public function createSubSection(array $data) { return SubMenuSection::create($data); }

    public function updateSubSection(int $id, int $resId, array $data) {
        return SubMenuSection::where('id', $id)
            ->update($data);
    }

    public function deleteSubSection(int $id, int $resId) {
        return SubMenuSection::where('id', $id)
            ->delete();
    }

        public function findSubSection(int $id)
    {
        return SubMenuSection::where('id', $id)
        ->firstOrFail();
    }

    // --- Menu Items ---

    public function findItemById(int $itemId)
    {
        // استخدام with لضمان جلب كل البيانات اللازمة بـ Query واحد فقط
        return MenuItem::where('is_available', true)
            ->with(['extras'])->find($itemId);
    }


    public function createItem(array $data) { return MenuItem::create($data); }

    public function updateItem(int $id, int $resId, array $data) {
        return MenuItem::where('id', $id)
                  ->update($data);
    }

    public function deleteItem(int $id, int $resId) {
        return MenuItem::where('id', $id)
              ->delete();
    }

    public function findItem(int $id, int $resId)
    {
        return MenuItem::where('id', $id)
            ->firstOrFail();
    }

    public function getMenuTree(int $resId)
    {
        $restaurant = Restaurant::find($resId);
// بما أن sub_menu_sections مرتبطة بـ restaurant_id مباشرة:
        $subSections = SubMenuSection::where('restaurant_id', $resId)
        ->with('items') // تأكد أن علاقة items معرفة في موديل SubMenuSection
        ->get();

        return [
            'restaurant' => $restaurant,
            'subSections' => $subSections
        ];
    }
}
