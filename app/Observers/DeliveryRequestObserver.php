<?php

namespace App\Observers;

use App\Jobs\NotifyNearbyDriversJob;
use App\Models\DeliveryRequest;
use App\Services\Driver\GeoapifyDistanceService;

class DeliveryRequestObserver
{
    protected $distanceService;

    public function __construct(GeoapifyDistanceService $distanceService)
    {
        $this->distanceService = $distanceService;
    }

    public function created(DeliveryRequest $deliveryRequest): void
    {
        // إرسال المهمة للطابور الخلفي فوراً
        NotifyNearbyDriversJob::dispatch($deliveryRequest);
    }
}
