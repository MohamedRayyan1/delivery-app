<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Services\Vendor\RestaurantReportService;
use Illuminate\Support\Facades\Auth;

class RestaurantReportController extends Controller
{
    public function __construct(private RestaurantReportService $service) {}

    public function cards()
    {
        $restaurantId = Auth::user()->managedRestaurant->id;

        return response()->json(
            $this->service->getCards($restaurantId)
        );
    }

    public function monthlyGrowth()
    {
        $restaurantId = Auth::user()->managedRestaurant->id;

        return response()->json(
            app(RestaurantReportService::class)
                ->getMonthlyGrowth($restaurantId)
        );
    }

    public function downloadPdf()
    {
        $restaurantId = Auth::user()->managedRestaurant->id;

        return app(RestaurantReportService::class)
            ->generatePdf($restaurantId);
    }
}
