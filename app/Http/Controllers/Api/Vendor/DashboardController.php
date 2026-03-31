<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Services\Vendor\RestaurantDashboardService;
use App\Traits\ApiResponseTrait;
use App\Http\Resources\Vendor\DashboardOverviewResource;
use App\Http\Resources\Vendor\TopSellingItemResource;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    use ApiResponseTrait;

    protected $dashboardService;

    public function __construct(RestaurantDashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * جلب البيانات الأساسية للوحة التحكم
     */
    public function overview()
    {
        try {

            $restaurantId = Auth::user()->managedRestaurant->id;

            $data = $this->dashboardService->getOverviewData($restaurantId);
            $resource = new DashboardOverviewResource($data);

            return $this->successResponse($resource, 'تم جلب بيانات لوحة التحكم بنجاح');
        } catch (\Exception $e) {
            // تسجيل الخطأ في السجلات للرجوع إليه لاحقاً
            \Log::error('Dashboard Overview Error: ' . $e->getMessage());

            return $this->errorResponse('حدث خطأ أثناء جلب البيانات، يرجى المحاولة لاحقاً', 500);
        }
    }

    public function allTopSellingItems()
    {
        try {
            $restaurantId = Auth::user()->managedRestaurant->id;

            $items = $this->dashboardService->getAllTopSellingItems($restaurantId);
            // إرجاع البيانات باستخدام الـ Resource المخصص للأصناف
            return $this->successResponse(
                TopSellingItemResource::collection($items),
                'تم جلب قائمة الأصناف الأكثر طلباً بالكامل بنجاح'
            );
        } catch (\Exception $e) {
            // تسجيل الخطأ للمتابعة التقنية
            \Log::error('All Top Selling Items Error: ' . $e->getMessage());

            return $this->errorResponse('حدث خطأ أثناء معالجة طلبك، يرجى المحاولة لاحقاً', 500);
        }
    }
}
