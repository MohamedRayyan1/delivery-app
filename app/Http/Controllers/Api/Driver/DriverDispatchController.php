<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DriverDispatchService;

class DriverDispatchController extends Controller
{
    protected $service;

    public function __construct(DriverDispatchService $service)
    {
        $this->service = $service;
    }

    /**
     * API لحساب المسافة بين المطعم وعنوان الزبون
     * الحالات:
     * - address_id → عنوان محفوظ
     * - lat/lng → موقع حالي
     */
    public function calculateDistance(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'address_id'    => 'nullable|exists:user_addresses,id',
            'lat'           => 'nullable|numeric',
            'lng'           => 'nullable|numeric',
        ]);

        $distance = $this->service->calculateDistanceToCustomer(
            $request->restaurant_id,
            $request->address_id,
            $request->lat,
            $request->lng
        );

        return response()->json([
            'success' => true,
            'distance_km' => $distance
        ]);
    }
}
