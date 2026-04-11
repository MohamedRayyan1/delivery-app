<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Tracking\OrderTrackingService;
use Exception;

class TrackingController extends Controller
{
    protected $trackingService;

    public function __construct(OrderTrackingService $trackingService)
    {
        $this->trackingService = $trackingService;
    }

    /**
     * API 1: تحديث موقع السائق (Driver App)
     */
    public function updateLocation(Request $request, $orderId)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        try {
            // نستخرج الـ ID بأمان تام من التوكن الخاص بالسائق
            $driverId = $request->user()->driver->id;

            $this->trackingService->updateDriverLocation(
                $driverId,
                $orderId,
                $request->lat,
                $request->lng
            );

            return response()->json([
                'status' => true,
                'message' => 'تم تحديث الإحداثيات بنجاح.'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 403);
        }
    }

    /**
     * API 2: جلب موقع السائق للتتبع (Customer/Restaurant App)
     */
    public function getLocation(Request $request, $orderId, $driverId)
    {
        try {
            $location = $this->trackingService->getDriverLocation($driverId, $orderId);

            return response()->json([
                'status' => true,
                'data' => [
                    'lat' => $location['lat'],
                    'lng' => $location['lng'],
                    'last_update' => $location['updated_at'] // لمعرفة ما إذا كان الخط مقطوعاً
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }
}
