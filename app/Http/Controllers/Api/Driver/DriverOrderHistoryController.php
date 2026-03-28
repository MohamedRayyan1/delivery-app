<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Services\Driver\DriverOrderHistoryService;
use App\Http\Requests\Driver\GetDriverOrdersHistoryRequest;
use App\Http\Resources\Driver\DriverOrderHistoryResource;

class DriverOrderHistoryController extends Controller
{
    protected $historyService;

    public function __construct(DriverOrderHistoryService $historyService)
    {
        $this->historyService = $historyService;
    }

    public function index(GetDriverOrdersHistoryRequest $request)
    {
        $orders = $this->historyService->getHistory(
            $request->user()->driver->id,
            $request->validated()
        );

        return $this->successResponse([
            'orders' => DriverOrderHistoryResource::collection($orders),
            'next_cursor' => $orders->nextCursor()?->encode(),
            'prev_cursor' => $orders->previousCursor()?->encode(),
        ]);
    }
}
