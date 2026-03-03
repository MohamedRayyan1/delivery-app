<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRestaurantRequest;
use App\Http\Requests\Admin\UpdateRestaurantRequest;
use App\Http\Resources\Admin\AdminRestaurantResource;
use App\Services\AdminRestaurantService;

class RestaurantController extends Controller
{
    protected $service;

    public function __construct(AdminRestaurantService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $restaurants = $this->service->listRestaurants();
        return $this->successResponse(AdminRestaurantResource::collection($restaurants));
    }

    public function store(StoreRestaurantRequest $request)
    {
        $restaurant = $this->service->storeRestaurant($request->validated());
        return $this->successResponse(new AdminRestaurantResource($restaurant), 'تم الإضافة بنجاح', 201);
    }

    public function show($id)
    {
        $restaurant = $this->service->getRestaurant($id);
        return $this->successResponse(new AdminRestaurantResource($restaurant));
    }

    public function update(UpdateRestaurantRequest $request, $id)
    {
        $restaurant = $this->service->updateRestaurant($id, $request->validated());
        return $this->successResponse(new AdminRestaurantResource($restaurant), 'تم التحديث بنجاح');
    }

    public function destroy($id)
    {
        $this->service->deleteRestaurant($id);
        return $this->successResponse(null, 'تم الحذف بنجاح');
    }
}
