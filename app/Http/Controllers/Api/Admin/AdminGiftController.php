<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminGiftService;
use App\Http\Requests\Admin\StoreGiftRequest;
use App\Http\Requests\Admin\UpdateGiftRequest;
use App\Http\Resources\Admin\AdminGiftResource;

class AdminGiftController extends Controller
{
    protected $giftService;

    public function __construct(AdminGiftService $giftService)
    {
        $this->giftService = $giftService;
    }

    public function index()
    {
        $gifts = $this->giftService->listGifts();
        return $this->successResponse(AdminGiftResource::collection($gifts));
    }

    public function store(StoreGiftRequest $request)
    {
        $gift = $this->giftService->storeGift($request->validated());
        return $this->successResponse(new AdminGiftResource($gift), 'تم إضافة الهدية بنجاح', 201);
    }

    public function update(StoreGiftRequest $request, int $id)
    {
        $gift = $this->giftService->updateGift($id, $request->validated());
        return $this->successResponse(new AdminGiftResource($gift), 'تم تحديث الهدية بنجاح');
    }

    public function destroy(int $id)
    {
        $this->giftService->deleteGift($id);
        return $this->successResponse(null, 'تم حذف الهدية بنجاح');
    }
}
