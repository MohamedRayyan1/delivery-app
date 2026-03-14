<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\StoreVendorAdRequest;
use App\Http\Requests\Vendor\UpdateVendorAdRequest;
use App\Http\Resources\Vendor\VendorAdResource;
use App\Services\Vendor\VendorAdService;
use Illuminate\Http\Request;

class VendorAdController extends Controller
{
    protected $adService;

    public function __construct(VendorAdService $adService)
    {
        $this->adService = $adService;
    }

    public function index(Request $request)
    {
        $ads = $this->adService->getAds($request->my_restaurant_id);
        return $this->successResponse(VendorAdResource::collection($ads));
    }

    public function store(StoreVendorAdRequest $request)
    {
        $ad = $this->adService->storeAd($request->my_restaurant_id, $request->validated());
        return $this->successResponse(new VendorAdResource($ad), 'Ad created successfully', 201);
    }

    public function update(UpdateVendorAdRequest $request, $id)
    {
        try {
            $ad = $this->adService->updateAd($id, $request->my_restaurant_id, $request->validated());
            return $this->successResponse(new VendorAdResource($ad), 'Ad updated successfully');
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $this->adService->deleteAd($id, $request->my_restaurant_id);
            return $this->successResponse(null, 'Ad deleted successfully');
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
