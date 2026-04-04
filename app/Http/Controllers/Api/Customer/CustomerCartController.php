<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Services\Customer\CustomerCartService;
use App\Http\Requests\Customer\StoreCartItemRequest;
use App\Http\Resources\Customer\CustomerCartResource;
use Illuminate\Http\Request;

class CustomerCartController extends Controller
{
    protected $cartService;

    public function __construct(CustomerCartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(Request $request)
    {
        $cart = $this->cartService->getCart($request->user()->id);

        if (!$cart) {
            return $this->successResponse(null, 'السلة فارغة');
        }

        return $this->successResponse(new CustomerCartResource($cart));
    }

    public function store(StoreCartItemRequest $request)
    {
        try {
            $cart = $this->cartService->addItem($request->user()->id, $request->validated());
            return $this->successResponse(new CustomerCartResource($cart), 'تمت إضافة الوجبة للسلة بنجاح');
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function decrement(Request $request, int $id)
    {
        try {
            $cart = $this->cartService->decrementItemQuantity($request->user()->id, $id);

            if (!$cart) {
                return $this->successResponse(null, 'تم حذف الوجبة، السلة فارغة الآن');
            }

            return $this->successResponse(new CustomerCartResource($cart), 'تم إنقاص الكمية بنجاح');
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function destroy(Request $request, int $id)
    {
        try {
            $cart = $this->cartService->removeItem($request->user()->id, $id);

            if (!$cart) {
                return $this->successResponse(null, 'تم حذف الوجبة، السلة فارغة الآن');
            }

            return $this->successResponse(new CustomerCartResource($cart), 'تم حذف الوجبة بنجاح');
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'حدث خطأ أثناء الحذف'], 400);
        }
    }

    public function clear(Request $request)
    {
        $this->cartService->clearCart($request->user()->id);
        return $this->successResponse(null, 'تم تفريغ السلة بنجاح');
    }
}
