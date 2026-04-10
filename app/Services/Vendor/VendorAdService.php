<?php

namespace App\Services\Vendor;

use App\Repositories\Eloquent\VendorAdRepository;
use Illuminate\Support\Facades\DB;
use App\Jobs\ProcessImageJob;

class VendorAdService
{
    protected $repository;

    public function __construct(VendorAdRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAds(int $resId)
    {
        return $this->repository->getRestaurantAds($resId);
    }

    public function storeAd(int $resId, array $data)
    {
        return DB::transaction(function () use ($resId, $data) {
            if (request()->hasFile('image')) {
                $data['image'] = request()->file('image')->store('ads', 'public');
            }

            $data['restaurant_id'] = $resId;
            $data['status'] = 'pending';
            $data['cost'] = 0.00;
            $data['is_active'] = false;

            $ad = $this->repository->createAd($data);

            if (array_key_exists('image', $data)) {
                dispatch(new ProcessImageJob( $data['image']))->afterCommit();
            }

            return $ad;
        });
    }

    public function updateAd(int $id, int $resId, array $data)
    {
        $ad = $this->repository->findAdById($id, $resId);

        if (in_array($ad->status, ['approved', 'expired'])) {
            throw new \Exception("Cannot update ad after approval or expiration.");
        }

        return DB::transaction(function () use ($ad, $id, $resId, $data) {
            if (request()->hasFile('image')) {
                $data['image'] = request()->file('image')->store('ads', 'public');
            }

            $this->repository->updateAd($id, $resId, $data);
            $updatedAd = $this->repository->findAdById($id, $resId);

            if (array_key_exists('image', $data)) {
                dispatch(new ProcessImageJob( $data['image']))->afterCommit();
            }

            return $updatedAd;
        });
    }

    public function deleteAd(int $id, int $resId)
    {
        $ad = $this->repository->findAdById($id, $resId);

        if (in_array($ad->status, ['approved', 'expired'])) {
            throw new \Exception("Cannot delete ad after approval or expiration.");
        }

        return DB::transaction(function () use ($id, $resId) {
            return $this->repository->deleteAd($id, $resId);
        });
    }
}
