<?php

namespace App\Services\Driver;

use App\Events\Driver\OrderAccepted;
use App\Models\DeliveryRequest;
use App\Repositories\Eloquent\HomePageRepository;
use App\Services\Driver\GeoapifyDistanceService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HomePageService
{
    protected $repository;
    protected $distanceService;

    public function __construct(
        HomePageRepository $repository,
        GeoapifyDistanceService $distanceService
    ) {
        $this->repository = $repository;
        $this->distanceService = $distanceService;
    }
    public function getFormattedOrders(string $city)
    {
        $user = Auth::user();
        $driverId = $user->id;

        // جلب موقع السائق اللحظي ونوع مركبته
        $driverLat = $user->driver->current_lat;
        $driverLng = $user->driver->current_lng;
        $vehicleType = $user->driver->vehicle_type;

        // جلب المعرفات التي رفضها السائق لعدم تكرارها
        $rejectedIds = Cache::get("driver_{$driverId}_rejected_requests", []);

        // 1. جلب الطلبات الخام من قاعدة البيانات (فلترة أولية بالمدينة ونوع المركبة)
        $requests = $this->repository->getAvailableDeliveryRequestsByCity($city, $vehicleType, $rejectedIds);

        // 2. تطبيق الفلترة الجغرافية (نطاق 5 كم)
        return $requests->filter(function ($request) use ($driverLat, $driverLng) {
            $restaurant = $request->order->restaurant;

            // استدعاء الـ API لحساب المسافة من السائق إلى المطعم
            $distanceToRest = $this->distanceService->calculateDistance(
                $driverLat,
                $driverLng,
                $restaurant->lat,
                $restaurant->lng
            );

            // الشرط الصحيح: المسافة يجب أن تكون أصغر من أو تساوي 5 كم
            return $distanceToRest['distance_km'] <= 5.0;
        })->map(function ($request) {
            // 3. تجهيز بيانات العرض للطلبات التي نجحت في الفلترة
            $order = $request->order;

            // حساب المسافة من المطعم إلى منزل العميل (لعرضها للسائق)
            $distData = $this->distanceService->calculateDistance(
                $order->restaurant->lat,
                $order->restaurant->lng,
                $order->address->lat,
                $order->address->lng
            );

            // إغناء كائن الطلب بالبيانات المحسوبة
            $request->distance_km = $distData['distance_km'];
            $request->duration_minutes = $distData['duration_minutes'];
            $request->driver_profit = $request->offered_delivery_fee;

            return $request;
        })->values(); // إعادة ترتيب مفاتيح المصفوفة بعد الحذف (Filter)
    }

    public function acceptOrder(int $requestId, int $driverId): bool
    {
        $accepted = $this->repository->acceptDeliveryRequest($requestId, $driverId);

        if (!$accepted) {
            return false;
        }
        $deliveryRequest = DeliveryRequest::find($requestId);

        $city = Auth::user()->city;
        event(new OrderAccepted($deliveryRequest->order_id, $city));

        // تنظيف الكاش
        \Cache::forget("driver_orders_{$city}_*");

        return true;
    }

    public function getDeliverySummary(int $driverId, int $orderId)
    {
        $order = $this->repository->getDeliveredOrderSummary($driverId, $orderId);

        if ($order->status !== 'delivered') {
            throw new Exception('لا يمكن عرض ملخص التسليم لطلب لم يكتمل بعد.');
        }

        return $order;
    }

    public function rejectOrder(int $requestId)
    {
        $driverId = Auth::id();
        $cacheKey = "driver_{$driverId}_rejected_requests";

        $rejectedIds = Cache::get($cacheKey, []);

        if (!in_array($requestId, $rejectedIds)) {
            $rejectedIds[] = $requestId;
            // تخزين لمدة 6 ساعات مثلاً (عمر الطلب)
            Cache::put($cacheKey, $rejectedIds, now()->addHours(6));
        }

        return true;
    }


    // HomePageService.php

    public function pickupOrder(int $requestId, int $driverId, $imageFile)
    {
        return \DB::transaction(function () use ($requestId, $driverId, $imageFile) {

            // 1. رفع الصورة الأصلية في مسارها
            $path = $imageFile->store('delivery/invoices', 'public');

            // 2. تحديث قاعدة البيانات عبر الـ Repository
            $deliveryRequest = $this->repository->markAsPickedUp($requestId, $driverId, $path);

            if (!$deliveryRequest) {
                \Storage::disk('public')->delete($path); // حذف الصورة إذا فشل التحديث
                throw new \Exception('عذراً، لا يمكن استلام هذا الطلب حالياً.');
            }

            // 3. إرسال المهمة للـ Queue لمعالجة الصورة
            dispatch(new \App\Jobs\ProcessImageJob($deliveryRequest, 'invoice_image', $path));

            return $deliveryRequest;
        });
    }


    // HomePageService.php

    public function deliverOrder(int $requestId, int $driverId, string $confirmationCode)
    {
        // 1. جلب الطلب المرتبط بطلب التوصيل للتحقق من الكود
        $deliveryRequest = DeliveryRequest::with('order')->find($requestId);

        if (!$deliveryRequest || $deliveryRequest->driver_id !== $driverId) {
            throw new \Exception('عذراً، هذا الطلب غير تابع لك.');
        }

        if ($deliveryRequest->status !== 'picked_up') {
            throw new \Exception('لا يمكن توصيل طلب لم يتم استلامه من المطعم بعد.');
        }

        // 2. التحقق من كود التأكيد
        if ($deliveryRequest->order->delivery_confirmation_code !== $confirmationCode) {
            throw new \Exception('كود التحقق غير صحيح، يرجى التأكد من الزبون.');
        }

        // 3. تنفيذ التحديث في قاعدة البيانات
        $updatedRequest = $this->repository->markAsDelivered($requestId, $driverId);

        if (!$updatedRequest) {
            throw new \Exception('حدث خطأ أثناء تحديث حالة الطلب.');
        }

        return $updatedRequest;
    }
}
