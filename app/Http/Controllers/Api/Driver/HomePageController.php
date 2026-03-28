<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\Driver\AvailableOrderResource;
use App\Services\Driver\HomePageService;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Auth;

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


    public function acceptOrder($requestId)
    {
        if ($this->service->acceptOrder($requestId, Auth::user()->driver->id)) {
            return $this->successResponse(null, 'تم قبول الطلب بنجاح!');
        }
        return $this->errorResponse('عذراً، الطلب لم يعد متاحاً أو سبَقَك إليه سائق آخر.', 400);
    }

    public function rejectOrder($id)
    {
        $this->service->rejectOrder($id);
        return $this->successResponse(null, 'تم إخفاء الطلب من قائمتك.');
    }
}
