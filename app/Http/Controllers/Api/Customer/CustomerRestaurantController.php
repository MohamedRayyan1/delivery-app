<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Services\Customer\CustomerRestaurantService;
use App\Http\Resources\Customer\CustomerRestaurantDetailsResource;
use App\Http\Resources\Customer\CustomerRestaurantListResource;
use Illuminate\Http\Request;

class CustomerRestaurantController extends Controller
{
    protected $restaurantService;

    public function __construct(CustomerRestaurantService $restaurantService)
    {
        $this->restaurantService = $restaurantService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'city']);

        $restaurants = $this->restaurantService->getRestaurantsList($filters);

        return $this->successResponse([
            'restaurants' => CustomerRestaurantListResource::collection($restaurants),
            'next_cursor' => $restaurants->nextCursor()?->encode(),
            'prev_cursor' => $restaurants->previousCursor()?->encode(),
        ]);
    }

    public function show($id)
    {
        try {
            $restaurant = $this->restaurantService->getRestaurantFullMenu($id);
            return $this->successResponse(new CustomerRestaurantDetailsResource($restaurant));
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'المطعم غير موجود أو غير متاح حالياً.'
            ], 404);
        }
    }
}
