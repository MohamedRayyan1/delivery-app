<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Services\Driver\DriverStatusService;
use App\Http\Requests\Driver\ToggleOnlineStatusRequest;

class DriverStatusController extends Controller
{
    protected $statusService;

    public function __construct(DriverStatusService $statusService)
    {
        $this->statusService = $statusService;
    }

    public function toggleOnline(ToggleOnlineStatusRequest $request)
    {
        try {
            $isOnline = $this->statusService->toggleOnlineStatus(
                $request->user()->id,
                $request->validated('is_online')
            );

            $message = $isOnline
                ? 'أنت الآن متصل ومستعد لاستقبال الطلبات.'
                : 'أنت الآن غير متصل.';

            return $this->successResponse(['is_online' => $isOnline], $message);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 400); // 400 Bad Request لأن الخطأ غالباً بسبب حالة الحساب
        }
    }
}
