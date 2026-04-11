<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Services\Vendor\VendorOrderService;
use App\Http\Requests\Vendor\GetVendorOrdersRequest;
use App\Http\Resources\Vendor\VendorOrderResource;
use Illuminate\Http\Request;
use Exception;

class VendorOrderController extends Controller
{
    protected $orderService;

    public function __construct(VendorOrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(GetVendorOrdersRequest $request)
    {
        $restaurantId = $request->user()->managedRestaurant->id;

        $orders = $this->orderService->getOrdersList($restaurantId, $request->validated());

        return $this->successResponse([
            'orders' => VendorOrderResource::collection($orders),
            'next_cursor' => $orders->nextCursor()?->encode(),
            'prev_cursor' => $orders->previousCursor()?->encode(),
        ]);
    }

    public function acceptOrder(Request $request, int $orderId)
    {
        try {
            $restaurantId = $request->user()->managedRestaurant->id;

            $order = $this->orderService->acceptOrder($restaurantId, $orderId);

            return $this->successResponse([
                'message' => 'Order accepted successfully and is now being prepared.',
                'order' => new VendorOrderResource($order)
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
