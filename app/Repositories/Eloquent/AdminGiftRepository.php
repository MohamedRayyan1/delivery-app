<?php

namespace App\Repositories\Eloquent;

use App\Models\Gift;

class AdminGiftRepository
{
    public function getAllGifts()
    {
        return Gift::orderBy('points', 'asc')->get();
    }

    public function findGiftById(int $id)
    {
        return Gift::findOrFail($id);
    }

    public function createGift(array $data)
    {
        return Gift::create($data);
    }

    public function updateGift(int $id, array $data)
    {
        $gift = $this->findGiftById($id);
        $gift->update($data);
        return $gift;
    }

    public function deleteGift(int $id)
    {
        $gift = $this->findGiftById($id);
        return $gift->delete();
    }
}
