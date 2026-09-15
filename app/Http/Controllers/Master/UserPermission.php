<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;
use App\Services\Master\UserPermissionService;

class UserPermission extends Controller
{
    protected UserPermissionService $userPermissionService;

    public function __construct(
        UserPermissionService $userPermissionService
    ) {
        $this->userPermissionService = $userPermissionService;
    }

    public function getPermissions(int $userId)
    {
        try {

            $data = $this->userPermissionService
                ->getUserPermissions($userId);

            return response()->json([
                'status' => '200',
                'success' => true,
                'message' => 'Hak akses berhasil diambil',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'success' => false,
                'message' => 'Gagal mengambil hak akses: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function storePermissions(Request $request)
    {
        try {

            $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'module_ids' => 'array',
                'module_ids.*' => 'integer|exists:module,id',
            ]);

            $data = $this->userPermissionService->savePermissions(
                $request->user_id,
                $request->module_ids ?? []
            );

            return response()->json([
                'status' => '200',
                'success' => true,
                'message' => 'Hak akses pengguna berhasil disimpan',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'success' => false,
                'message' => 'Gagal menyimpan hak akses: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function getPermissionsByUserId(int $userId)
    {
        try {

            $data = $this->userPermissionService
                ->getPermissionsByUserId($userId);

            return response()->json([
                'status' => '200',
                'success' => true,
                'message' => 'Hak akses pengguna berhasil diambil',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'success' => false,
                'message' => 'Gagal mengambil hak akses pengguna: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
}
