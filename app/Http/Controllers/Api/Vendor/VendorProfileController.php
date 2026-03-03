<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateRestaurantRequest;
use App\Http\Requests\Vendor\UpdateVendorProfileRequest;
use App\Http\Resources\Admin\AdminRestaurantResource;
use App\Services\Vendor\VendorProfileService;
use Illuminate\Http\Request;

class VendorProfileController extends Controller
{
    protected $profileService;

    public function __construct(VendorProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function show(Request $request)
    {
        // استخدام my_restaurant_id الممرر من الميدلوير
        $profile = $this->profileService->getProfile($request->user()->id);
        return $this->successResponse($profile);
    }

    public function update(UpdateRestaurantRequest $request)
    {
        $profile = $this->profileService->updateProfile(
            $request->my_restaurant_id,
            $request->validated()
        );
        return $this->successResponse(new AdminRestaurantResource($profile), 'تم تحديث ملف المطعم بنجاح');
    }
}
