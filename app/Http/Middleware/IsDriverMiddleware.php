<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsDriverMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // التحقق مما إذا كان المستخدم مسجلاً للدخول وأن دوره هو "سائق"
        if (Auth::check() && Auth::user()->role === 'driver') {

            if (Auth::user()->is_banned) {
                return response()->json([
                    'success' => false,
                    'message' => 'حسابك محظور، يرجى التواصل مع الإدارة.'
                ], 403);
            }

            return $next($request);
        }

        return response()->json([
            'success' => false,
            'message' => 'غير مصرح لك بالوصول. هذا المسار مخصص للسائقين فقط.'
        ], 401);
    }
}
