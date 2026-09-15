<?php

namespace App\Http\Middleware;

use App\Models\Master\Module;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        string $moduleKey
    ): Response {
        $user = $request->user();

        // User belum login
        if (!$user) {
            return response()->json([
                'status' => '401',
                'success' => false,
                'message' => 'Unauthenticated',
                'data' => []
            ], 401);
        }

        // Cari module berdasarkan key
        $module = Module::where('key', $moduleKey)
            ->where('status', 1)
            ->first();

        // Module tidak ditemukan
        if (!$module) {
            return response()->json([
                'status' => '404',
                'success' => false,
                'message' => 'Module tidak ditemukan',
                'data' => []
            ], 404);
        }

        // Cek apakah user memiliki permission
        $hasPermission = $user->permissions()
            ->where('module_id', $module->id)
            ->exists();

        // User tidak memiliki akses
        if (!$hasPermission) {
            return response()->json([
                'status' => '403',
                'success' => false,
                'message' => 'Anda tidak memiliki hak akses ke module ini',
                'data' => []
            ], 403);
        }

        return $next($request);
    }
}
