<?php

namespace App\Http\Middleware;

use App\Models\Restaurant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRestaurantOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
        $restaurant = Restaurant::where('manager_user_id', $request->user()->id)->first();

        if (!$restaurant) {
            return response()->json([
                'status' => false,
                'message' => 'ليس لديك مطعم مسجل لإدارته'
            ], 403);
        }

        // دمج بيانات المطعم في الطلب لاستخدامها لاحقاً في الـ Service
        $request->merge(['my_restaurant_id' => $restaurant->id]);

        return $next($request);
    }
}
