<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Address\AddressRequest;
use App\Http\Resources\AddressResource;
use App\Services\AddressService;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    private AddressService $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    public function index(Request $request)
    {
        $addresses = $this->addressService->getUserAddresses($request->user()->id);

        return $this->successResponse(AddressResource::collection($addresses));
    }

    public function store(AddressRequest $request)
    {
        $address = $this->addressService->createAddress($request->user()->id, $request->validated());

        return $this->successResponse(new AddressResource($address), 'تمت الإضافة بنجاح', 201);
    }

    public function update(AddressRequest $request, $id)
    {
        $address = $this->addressService->updateAddress($id, $request->user()->id, $request->validated());

        if (!$address) {
            return $this->errorResponse('العنوان غير موجود', 404);
        }

        return $this->successResponse(new AddressResource($address), 'تم التعديل بنجاح');
    }

    public function destroy(Request $request, $id)
    {
        $deleted = $this->addressService->deleteAddress($id, $request->user()->id);

        if (!$deleted) {
            return $this->errorResponse('العنوان غير موجود', 404);
        }

        return $this->successResponse(null, 'تم الحذف بنجاح');
    }

    public function setDefault(Request $request, $id)
    {
        $address = $this->addressService->setDefault($id, $request->user()->id);

        if (!$address) {
            return $this->errorResponse('العنوان غير موجود', 404);
        }

        return $this->successResponse(new AddressResource($address), 'تم تعيين العنوان كافتراضي');
    }
}
