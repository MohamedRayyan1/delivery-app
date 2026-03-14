<?php

namespace App\Services\Admin;

use App\Models\Ad;
use App\Repositories\Eloquent\AdminAdRepository;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class AdminAdService
{
    protected $repository;

    public function __construct(AdminAdRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getPendingAds()
    {
        return $this->repository->getPendingAds();
    }

    public function setQuote(int $adId, float $cost)
    {
        $ad = $this->repository->findAdById($adId);

        if ($ad->status !== 'pending') {
            throw new \Exception('Ad is not in a pending state for quoting.');
        }

        $requestedStart = Carbon::parse($ad->start_date);
        $requestedEnd = Carbon::parse($ad->end_date);
        $durationInDays = $requestedStart->diffInDays($requestedEnd);

        $availableStartDate = $this->findNextAvailableDate($requestedStart);


        $dataToUpdate = [
            'cost' => $cost,
            'status' => 'waiting_payment'
        ];

        $message = 'تم حفظ العرض. في انتظار تأكيد الدفع لعرض الإعلان في الموعد المحدد.';

        if ($availableStartDate->notEqualTo($requestedStart)) {
            $dataToUpdate['start_date'] = $availableStartDate;
            $dataToUpdate['end_date'] = $availableStartDate->copy()->addDays($durationInDays);
            $message = "تم ضبط الإعلان بنجاح. نظراً لمحدودية السعة، تم ترحيل تاريخ البدء إلى: " . $availableStartDate->toDateString() . " اذا كان مناسبا لكم يرجع دفع المبلغ ليتم تثبيت الموعد;";
        }

        $updatedAd = $this->repository->updateAd($adId, $dataToUpdate);

        return [
            'ad' => $updatedAd,
            'message' => $message
        ];
    }

    public function approveAndActivate(int $adId)
    {
        $ad = $this->repository->findAdById($adId);

        if ($ad->status !== 'waiting_payment') {
            throw new \Exception('Ad is not awaiting payment approval.');
        }

        $updatedAd = $this->repository->updateAd($adId, [
            'status' => 'approved',
            'is_active' => false
        ]);

        Cache::forget('active_ads');

        return $updatedAd;
    }

    public function rejectAd(int $adId)
    {
        return $this->repository->updateAd($adId, [
            'status' => 'rejected',
            'is_active' => false
        ]);
    }

    private function findNextAvailableDate(Carbon $startDate): Carbon
    {
        $dateToTest = $startDate->copy();

        while (true) {
            $overlappingAdsCount = Ad::whereIn('status', ['approved'])
                ->where('start_date', '<=', $dateToTest)
                ->where('end_date', '>=', $dateToTest)
                ->count();

            if ($overlappingAdsCount < 3) {
                return $dateToTest;
            }

            $nextExpiringAd = Ad::whereIn('status', ['approved'])
                ->where('start_date', '<=', $dateToTest)
                ->where('end_date', '>=', $dateToTest)
                ->orderBy('end_date', 'asc')
                ->first();

            $dateToTest = Carbon::parse($nextExpiringAd->end_date)->addDay()->startOfDay();
        }
    }
}
