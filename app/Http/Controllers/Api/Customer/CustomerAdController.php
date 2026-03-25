<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class CustomerAdController extends Controller
{

public function index(): JsonResponse
    {
        try {
            $ads = \App\Models\Ad::where('is_active', true)
                ->with('restaurant') // جلب بيانات المطعم المرتبط بالإعلان
                ->get();
            return response()->json([
                'success' => true,
                'data' => $ads,
                'message' => 'Ads retrieved successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }}
