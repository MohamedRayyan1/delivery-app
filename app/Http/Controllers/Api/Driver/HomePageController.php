<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Http\Resources\Driver\AvailableOrderResource;
use App\Http\Resources\Driver\DriverDeliverySummaryResource;
use App\Services\Driver\HomePageService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
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
        $driverId = Auth::user()->driver->id;

        $accepted = $this->service->acceptOrder($requestId, $driverId);

        if ($accepted) {
            return $this->successResponse(null, 'تم قبول الطلب بنجاح!');
        }

        return $this->errorResponse(
            'عذراً، الطلب لم يعد متاحاً أو سبَقَك إليه سائق آخر.',
            400
        );
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


    // HomePageController.php

    public function pickupOrder(Request $request, $id)
    {
        // 1. التصحيح: استدعاء validate من كائن $request وليس الـ Facade
        $request->validate([
            'invoice_image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $driverId = Auth::user()->driver->id;

        try {
            // 2. تمرير الملف مباشرة للخدمة
            $deliveryRequest = $this->service->pickupOrder($id, $driverId, $request->file('invoice_image'));

            // 3. استخدام successResponse كما في الكود الخاص بك
            return $this->successResponse([
                'id' => $deliveryRequest->id,
                'status' => $deliveryRequest->status,
                'invoice_image' => asset('storage/' . $deliveryRequest->invoice_image)
            ], 'تم استلام الطلب بنجاح، بالتوفيق في رحلتك!');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }



    public function deliverOrder(Request $request, $id)
    {
        // التأكد من إرسال كود التحقق
        $request->validate([
            'confirmation_code' => 'required|string|min:4',
        ]);

        $driverId = Auth::user()->driver->id;

        try {
            $deliveryRequest = $this->service->deliverOrder(
                $id,
                $driverId,
                $request->confirmation_code
            );

            return $this->successResponse([
                'id' => $deliveryRequest->id,
                'status' => $deliveryRequest->status, // Delivered
                'delivered_at' => now()->toDateTimeString()
            ], 'تم توصيل الطلب بنجاح! شكراً لجهودك.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
