<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        if ($request->user()) {
            return response()->json([
                'status' => '409',
                'success' => false,
                'message' => 'Pengguna sudah login',
                'data' => []
            ], 409);
        }

        return $next($request);
    }
}
