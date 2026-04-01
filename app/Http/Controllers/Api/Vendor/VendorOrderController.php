<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Services\Vendor\VendorOrderService;
use App\Http\Requests\Vendor\GetVendorOrdersRequest;
use App\Http\Resources\Vendor\VendorOrderResource;

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
}
