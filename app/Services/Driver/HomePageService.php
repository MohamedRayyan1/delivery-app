<?php

namespace App\Services\Driver;

use App\Events\Driver\OrderAccepted;
use App\Models\DeliveryRequest;
use App\Models\Order;
use App\Repositories\Eloquent\HomePageRepository;
use App\Services\Driver\GeoapifyDistanceService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HomePageService
{
    protected $repository;
    protected $distanceService;     // ← أضف هذا

    public function __construct(
        HomePageRepository $repository,
        GeoapifyDistanceService $distanceService   // ← Dependency Injection
    ) {
        $this->repository = $repository;
        $this->distanceService = $distanceService;
    }

    // داخل HomePageService.php

    public function getFormattedOrders(string $city)
    {
        $user = Auth::user();
        $driverId = $user->id;
        $driverLat = $user->driver->current_lat;
        $driverLng = $user->driver->current_lng;

        $vehicleType = $user->driver->vehicle_type;
        $rejectedIds = Cache::get("driver_{$driverId}_rejected_requests", []);

        $lastUpdate = DeliveryRequest::max('updated_at') ?: 'initial';
        $cacheKey = "driver_reqs_{$city}_{$driverId}_{$lastUpdate}";

        return Cache::remember($cacheKey, now()->addMinutes(1), function () use ($city, $vehicleType, $rejectedIds, $driverLat, $driverLng) {
            // جلب الطلبات المتاحة في المدينة
            $requests = $this->repository->getAvailableDeliveryRequestsByCity($city, $vehicleType, $rejectedIds);

            // تصفية الطلبات بناءً على مسافة السائق عن المطعم (5 كم)
            return $requests->filter(function ($request) use ($driverLat, $driverLng) {
                $restaurant = $request->order->restaurant;

                // حساب المسافة بين السائق والمطعم
                $distanceToRest = $this->distanceService->calculateDistance(
                    $driverLat,
                    $driverLng,
                    $restaurant->lat,
                    $restaurant->lng
                );

                // الشرط: يظهر فقط إذا كانت المسافة للمطعم <= 5 كم
                return $distanceToRest['distance_km'] <= 5.0;
            })->map(function ($request) {
                // حساب المسافة الإجمالية للطلب (من المطعم للعميل) لعرضها في التطبيق
                $order = $request->order;
                $distData = $this->distanceService->calculateDistance(
                    $order->restaurant->lat,
                    $order->restaurant->lng,
                    $order->address->lat,
                    $order->address->lng
                );

                $request->distance_km = $distData['distance_km'];
                $request->duration_minutes = $distData['duration_minutes'];
                $request->driver_profit = $request->offered_delivery_fee;

                return $request;
            });
        });
    }

    public function acceptOrder(int $orderId, int $driverId)
    {
        if ($this->repository->acceptDeliveryRequest($orderId, $driverId)) {
            $city = Auth::user()->city;
            event(new OrderAccepted($orderId, $city));

            // مسح الكاش العام للمدينة
            \Cache::forget("driver_orders_{$city}_*");
            return true;
        }
        return false;
    }

    public function rejectOrder(int $orderId)
    {
        $driverId = Auth::id();
        $cacheKey = "driver_{$driverId}_rejected_orders";

        // جلب القائمة الحالية وإضافة الـ ID الجديد
        $rejectedIds = Cache::get($cacheKey, []);
        if (!in_array($orderId, $rejectedIds)) {
            $rejectedIds[] = $orderId;
            // تخزينها لمدة 24 ساعة (عمر افتراضي كافٍ لطلب طعام)
            Cache::put($cacheKey, $rejectedIds, now()->addDay());
        }

        // مسح كاش القائمة لهذا السائق ليختفي الطلب فوراً
        Cache::forget("driver_orders_" . Auth::user()->city . "_{$driverId}_*");

        return true;
    }
}
