<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Services\Driver\DriverHomeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverHomeController extends Controller
{
    public function __construct(protected DriverHomeService $service) {}

    public function toggleStatus(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'is_online' => 'required|boolean'
        ]);

        try {
            $result = $this->service->toggleOnlineStatus(
                Auth::id(),
                $validated['is_online']
            );

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => [
                    'is_online' => $result['is_online']
                ]
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }


    public function home()
    {
        try {
            $data = $this->service->getHomeData(Auth::id());

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
