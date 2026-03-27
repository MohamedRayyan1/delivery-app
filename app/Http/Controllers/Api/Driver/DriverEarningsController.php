<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Services\Driver\DriverEarningsService;
use App\Http\Requests\Driver\GetEarningsRequest;
use App\Http\Resources\Driver\DriverEarningsResource;
use App\Http\Resources\Driver\DriverTransactionResource;
use Illuminate\Http\Request;

class DriverEarningsController extends Controller
{
    protected $earningsService;

    public function __construct(DriverEarningsService $earningsService)
    {
        $this->earningsService = $earningsService;
    }

    public function index(GetEarningsRequest $request)
    {
        $validated = $request->validated();
        $data = $this->earningsService->getEarningsDashboard(
            $request->user()->driver->id,
            $validated['start_date'] ?? null,
            $validated['end_date'] ?? null
        );

        return $this->successResponse(new DriverEarningsResource($data));
    }

    public function transactions(Request $request)
    {
        $perPage = $request->query('per_page', 15);
        $transactions = $this->earningsService->getTransactionsHistory($request->user()->driver->id, $perPage);

        return $this->successResponse([
            'transactions' => DriverTransactionResource::collection($transactions),
            'next_cursor' => $transactions->nextCursor()?->encode(),
            'prev_cursor' => $transactions->previousCursor()?->encode(),
        ]);
    }
}
