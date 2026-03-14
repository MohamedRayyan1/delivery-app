<?php

namespace App\Repositories\Eloquent;

use App\Models\MenuSection;
use App\Repositories\Contracts\MenuSectionRepositoryInterface;
use Illuminate\Support\Collection;

class MenuSectionRepository implements MenuSectionRepositoryInterface
{
    public function all(): Collection
    {
        return MenuSection::orderBy('id', 'asc')->get();
    }

    public function findById(int $id)
    {
        return MenuSection::findOrFail($id);
    }
}

