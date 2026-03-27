<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Services\Customer\CustomerOrderService;
use App\Http\Requests\Customer\CheckoutRequest;
use App\Http\Resources\Customer\CustomerOrderListResource;
use App\Http\Resources\Customer\CustomerOrderResource;
use App\Http\Resources\Customer\OrderTrackingResource;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
    protected $orderService;

    public function __construct(CustomerOrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $orders = $this->orderService->getUserOrders($request->user()->id);

        return $this->successResponse([
            'orders' => CustomerOrderListResource::collection($orders),
            'next_cursor' => $orders->nextCursor()?->encode(),
            'prev_cursor' => $orders->previousCursor()?->encode(),
        ]);
    }

public function show(Request $request, int $id)
    {
        try {
            $order = $this->orderService->getOrderDetails($request->user()->id, $id);
            return $this->successResponse(new CustomerOrderResource($order));
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'الطلب غير موجود أو لا تملك صلاحية الوصول إليه.'
            ], 404);
        }
    }

    public function checkout(CheckoutRequest $request)
    {
        try {
            $order = $this->orderService->checkout($request->user()->id, $request->validated());

            return $this->successResponse(
                new CustomerOrderResource($order),
                'تم إنشاء الطلب بنجاح',
                201
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function trackOrder($id)
{
    $order = $this->orderService->getOrderStatusAndDriver($id);

    if (!$order) {
        return $this->errorResponse("الطلب غير موجود", 404);
    }

    return $this->successResponse(new OrderTrackingResource($order));
}

    public function cancel(Request $request, int $id)
    {
        try {
            $order = $this->orderService->cancelOrder($request->user()->id, $id);

            return $this->successResponse(
                new CustomerOrderResource($order),
                'تم إلغاء الطلب بنجاح'
            );
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
