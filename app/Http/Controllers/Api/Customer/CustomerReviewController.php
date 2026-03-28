<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Services\Customer\CustomerReviewService;
use App\Http\Requests\Customer\StoreReviewRequest;
use App\Http\Resources\Customer\CustomerReviewResource;
use Illuminate\Http\Request;

class CustomerReviewController extends Controller
{
    protected $reviewService;

    public function __construct(CustomerReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function index(Request $request)
    {
        $reviews = $this->reviewService->getUserReviews($request->user()->id);

        return $this->successResponse([
            'reviews' => CustomerReviewResource::collection($reviews),
            'next_cursor' => $reviews->nextCursor()?->encode(),
            'prev_cursor' => $reviews->previousCursor()?->encode(),
        ]);
    }

    public function store(StoreReviewRequest $request, int $orderId)
    {
        try {
            $review = $this->reviewService->reviewOrder($request->user()->id, $orderId, $request->validated());
            $review->load(['restaurant:id,name,logo', 'driver.user:id,name']);

            return $this->successResponse(new CustomerReviewResource($review), 'تم حفظ التقييم بنجاح', 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function destroy(Request $request, int $id)
    {
        try {
            $this->reviewService->deleteReview($request->user()->id, $id);
            return $this->successResponse(null, 'تم حذف التقييم بنجاح');
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
