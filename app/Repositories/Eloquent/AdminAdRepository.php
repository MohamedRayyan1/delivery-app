<?php

namespace App\Repositories\Eloquent;

use App\Models\Ad;

class AdminAdRepository
{
    public function findAdById(int $id)
    {
        return Ad::findOrFail($id);
    }

    public function updateAd(int $id, array $data)
    {
        $ad = $this->findAdById($id);
        $ad->update($data);
        return $ad;
    }

    public function getPendingAds()
    {
        return Ad::whereIn('status', ['pending', 'waiting_payment'])
            ->orderBy('created_at', 'asc')
            ->get();
    }
}
