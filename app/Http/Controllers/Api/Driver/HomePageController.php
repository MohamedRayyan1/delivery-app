<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\Driver\AvailableOrderResource;
use App\Http\Resources\Driver\DriverDeliverySummaryResource;
use App\Services\Driver\HomePageService;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Auth;
use Request;

class HomePageController extends Controller
{
    use ApiResponseTrait;

    protected $service;

    public function __construct(HomePageService $service)
    {
        $this->service = $service;
    }

    public function getAvailableOrders()
    {
        $city = Auth::user()->city;
        $orders = $this->service->getFormattedOrders($city);

        $data = AvailableOrderResource::collection($orders);

        return $this->successResponse($data);
    }


    public function acceptOrder($id)
    {
        if ($this->service->acceptOrder($id, Auth::id())) {
            return $this->successResponse(null, 'تم قبول الطلب بنجاح!');
        }
        return $this->errorResponse('عذراً، الطلب لم يعد متاحاً.', 400);
    }

    public function rejectOrder($id)
    {
        $this->service->rejectOrder($id);
        return $this->successResponse(null, 'تم إخفاء الطلب من قائمتك.');
    }

    public function deliverySummary(Request $request, int $id)
    {

        try {
            $order = $this->service->getDeliverySummary(Auth::user()->driver->id, $id);
        
            return $this->successResponse(new DriverDeliverySummaryResource($order));

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'الطلب غير موجود أو لا تملك صلاحية الوصول إليه: ' . $e->getMessage()
            ], 404);
        }
    }

}
