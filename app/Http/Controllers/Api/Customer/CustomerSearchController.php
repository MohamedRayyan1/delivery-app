<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Services\Customer\CustomerSearchService;
use App\Http\Resources\Customer\CustomerSearchRestaurantResource;
use Illuminate\Http\Request;

class CustomerSearchController extends Controller
{
    protected $searchService;

    public function __construct(CustomerSearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function searchMeals(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string|min:2',
        ]);

        $results = $this->searchService->searchMeals($request->keyword);

        return $this->successResponse([
            'restaurants' => CustomerSearchRestaurantResource::collection($results),
            'next_cursor' => $results->nextCursor()?->encode(),
            'prev_cursor' => $results->previousCursor()?->encode(),
        ]);
    }
}
