<?php

namespace App\Repositories\Eloquent;

use App\Models\ItemExtra;
use App\Models\MenuItem;

class VendorExtraRepository
{



    public function checkMenuItemOwnership(int $menuItemId, int $resId): bool
    {
        return MenuItem::where('id', $menuItemId)
            ->whereHas('subSection.section.restaurants', function ($query) use ($resId) {
                $query->where('restaurants.id', $resId);
            })
            ->exists();
    }

    public function createExtra(array $data)
    {
        return ItemExtra::create($data);
    }

    public function updateExtra(int $id, int $resId, array $data)
    {
        return ItemExtra::where('id', $id)->update($data);
    }

    public function deleteExtra(int $id, int $resId)
    {
        return ItemExtra::where('id', $id)->delete();
    }
}
