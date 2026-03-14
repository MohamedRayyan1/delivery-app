<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserNotBanned
{
    /**
     * منع المستخدمين المحظورين من استخدام أي endpoint محمي.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user && $user->is_banned) {
            return response()->json([
                'status' => false,
                'message' => 'تم حظر هذا الحساب. يرجى التواصل مع الدعم.',
            ], 403);
        }

        return $next($request);
    }
}
