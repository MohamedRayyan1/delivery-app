<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminAdService;
use App\Http\Requests\Admin\AdminQuoteAdRequest;
use App\Http\Resources\Vendor\VendorAdResource;

class AdminAdController extends Controller
{
    protected $adService;

    public function __construct(AdminAdService $adService)
    {
        $this->adService = $adService;
    }

    public function index()
    {
        $ads = $this->adService->getPendingAds();
        return $this->successResponse(VendorAdResource::collection($ads));
    }

    public function quote(AdminQuoteAdRequest $request, $id)
    {
        try {
            $result = $this->adService->setQuote($id, $request->validated()['cost']);
            return $this->successResponse(new VendorAdResource($result['ad']), $result['message']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function approve($id)
    {
        try {
            $ad = $this->adService->approveAndActivate($id);
            return $this->successResponse(new VendorAdResource($ad), 'Ad approved successfully.');
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function reject($id)
    {
        try {
            $ad = $this->adService->rejectAd($id);
            return $this->successResponse(new VendorAdResource($ad), 'Ad rejected successfully.');
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
