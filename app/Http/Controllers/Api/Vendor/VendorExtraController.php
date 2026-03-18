<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Services\Vendor\VendorExtraService;
use App\Http\Requests\Vendor\StoreItemExtraRequest;
use App\Http\Requests\Vendor\UpdateItemExtraRequest;
use Illuminate\Http\Request;

class VendorExtraController extends Controller
{
    protected $extraService;

    public function __construct(VendorExtraService $extraService)
    {
        $this->extraService = $extraService;
    }

public function store(StoreItemExtraRequest $request)
{
    try {
        $user = $request->user();
        $restaurant = $user->managedRestaurant;

        if (!$restaurant) {
            return response()->json(['status' => false, 'message' => 'هذا المستخدم لا يدير أي مطعم'], 403);
        }

        $resId = $restaurant->id;
        $extra = $this->extraService->addExtra($resId, $request->validated());

        return $this->successResponse($extra, 'تم إضافة الإكسترا بنجاح', 201);
    } catch (\Exception $e) {
        return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
    }
}

    public function update(UpdateItemExtraRequest $request, $id)
    {
        try {
            $user = $request->user();
            $restaurant = $user->managedRestaurant;
            $resId = $restaurant->id;
            $this->extraService->updateExtra($id, $resId, $request->validated());

            return $this->successResponse(null, 'تم تحديث الإكسترا بنجاح');
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $user = $request->user();
            $restaurant = $user->managedRestaurant;
            $resId = $restaurant->id;
            $this->extraService->deleteExtra($id, $resId);

            return $this->successResponse(null, 'تم حذف الإكسترا بنجاح');
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
