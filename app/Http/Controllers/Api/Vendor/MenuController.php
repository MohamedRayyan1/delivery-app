<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\StoreMenuSectionRequest;
use App\Http\Requests\Vendor\StoreSubSectionRequest;
use App\Http\Requests\Vendor\StoreMenuItemRequest;
use App\Http\Resources\Customer\CustomerSectionItemResource;
use App\Services\Vendor\VendorMenuService;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    protected $menuService;

    public function __construct(VendorMenuService $menuService) {
        $this->menuService = $menuService;
    }

    // --- Sections ---
    public function storeSection(StoreMenuSectionRequest $request) {
        $section = $this->menuService->addSection( $request->validated());
        return $this->successResponse($section, 'تمت الإضافة بنجاح', 201);
    }

    public function updateSection(StoreMenuSectionRequest $request, $id) {
        $section=$this->menuService->updateSection($id,  $request->validated());
        return $this->successResponse($section, 'تم التحديث بنجاح');
    }

    public function destroySection(Request $request, $id) {
        $this->menuService->deleteSection($id);
        return $this->successResponse(null, 'تم الحذف بنجاح');
    }

    // --- Sub Sections ---
    public function storeSubSection(StoreSubSectionRequest $request) {
        $sub = $this->menuService->addSubSection($request->my_restaurant_id, $request->validated());
        return $this->successResponse($sub, 'تمت الإضافة بنجاح', 201);
    }

    public function updateSubSection(StoreSubSectionRequest $request, $id) {
        $SubSection=$this->menuService->updateSubSection($id, $request->my_restaurant_id, $request->validated());
        return $this->successResponse($SubSection, 'تم التحديث بنجاح');
    }

    public function destroySubSection(Request $request, $id) {
        $this->menuService->deleteSubSection($id, $request->my_restaurant_id);
        return $this->successResponse(null, 'تم الحذف بنجاح');
    }

    // --- Items ---

    public function showItem(int $id)
    {
        $item = $this->menuService->getItemDetails($id);

        if (!$item) {
            return $this->errorResponse("الوجبة غير موجودة", 404);
        }

        return $this->successResponse([
            'Item' => new CustomerSectionItemResource($item)
        ]);
    }

    public function storeItem(StoreMenuItemRequest $request) {
        $item = $this->menuService->addItem($request->my_restaurant_id, $request->validated());
        return $this->successResponse($item, 'تمت الإضافة بنجاح', 201);
    }

    public function updateItem(StoreMenuItemRequest $request, $id) {
        $item=$this->menuService->updateItem($id, $request->my_restaurant_id, $request->validated());
        return $this->successResponse($item, 'تم التحديث بنجاح');
    }

    public function destroyItem(Request $request, $id) {
        $this->menuService->deleteItem($id, $request->my_restaurant_id);
        return $this->successResponse(null, 'تم الحذف بنجاح');
    }


    public function index($restaurant_id)
    {
        $menu = $this->menuService->getFullMenu($restaurant_id);
        return $this->successResponse($menu);
    }

    }

