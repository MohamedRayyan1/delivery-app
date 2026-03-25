<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminCouponService;
use App\Http\Requests\Admin\StoreCouponRequest;
use App\Http\Requests\Admin\UpdateCouponRequest;
use App\Http\Resources\Admin\AdminCouponResource;

class AdminCouponController extends Controller
{
    protected $couponService;

    public function __construct(AdminCouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    public function index()
    {
        $coupons = $this->couponService->listCoupons();
        return $this->successResponse(AdminCouponResource::collection($coupons));
    }

    public function store(StoreCouponRequest $request)
    {
        $coupon = $this->couponService->storeCoupon($request->validated());
        return $this->successResponse(new AdminCouponResource($coupon), 'تم إنشاء الكوبون بنجاح', 201);
    }

    public function update(UpdateCouponRequest $request, int $id)
    {
        $coupon = $this->couponService->updateCoupon($id, $request->validated());
        return $this->successResponse(new AdminCouponResource($coupon), 'تم تحديث الكوبون بنجاح');
    }

    public function destroy(int $id)
    {
        $this->couponService->deleteCoupon($id);
        return $this->successResponse(null, 'تم حذف الكوبون بنجاح');
    }
}
