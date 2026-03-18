<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Services\Customer\CustomerFavoriteService;
use App\Http\Resources\Customer\CustomerFavoriteRestaurantResource;
use App\Http\Resources\Customer\CustomerFavoriteItemResource;
use Illuminate\Http\Request;

class CustomerFavoriteController extends Controller
{
    protected $favoriteService;

    public function __construct(CustomerFavoriteService $favoriteService)
    {
        $this->favoriteService = $favoriteService;
    }

    public function index(Request $request)
    {
        $favorites = $this->favoriteService->getUserFavorites($request->user()->id);

        return $this->successResponse([
            'restaurants' => CustomerFavoriteRestaurantResource::collection($favorites['restaurants']),
            'items' => CustomerFavoriteItemResource::collection($favorites['items']),
        ]);
    }

    public function toggleRestaurant(Request $request, int $id)
    {
        try {
            $result = $this->favoriteService->toggleRestaurant($request->user()->id, $id);
            return $this->successResponse(['is_favorite' => $result['is_favorite']], $result['message']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function toggleItem(Request $request, int $id)
    {
        try {
            $result = $this->favoriteService->toggleItem($request->user()->id, $id);
            return $this->successResponse(['is_favorite' => $result['is_favorite']], $result['message']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
