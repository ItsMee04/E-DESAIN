<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Services\Authentication\AuthenticationService;
use Illuminate\Http\Request;

class AuthenticationController extends Controller
{
    protected AuthenticationService $authService;

    public function __construct(
        AuthenticationService $authService
    ) {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            $data = $this->authService->login([
                'username' => $request->username,
                'password' => $request->password,
            ]);

            if (!$data) {
                return response()->json([
                    'status' => '401',
                    'success' => false,
                    'message' => 'Username atau password salah',
                    'data' => []
                ], 401);
            }

            return response()->json([
                'status' => '200',
                'success' => true,
                'message' => 'Login berhasil',
                'data' => [
                    'user' => $data['user'],
                    'token' => $data['token'],
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'success' => false,
                'message' => 'Gagal melakukan login: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $result = $this->authService->logout(
                $request->user()
            );

            if (!$result) {
                return response()->json([
                    'status' => '400',
                    'success' => false,
                    'message' => 'Token tidak ditemukan',
                    'data' => []
                ], 400);
            }

            return response()->json([
                'status' => '200',
                'success' => true,
                'message' => 'Logout berhasil',
                'data' => []
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'success' => false,
                'message' => 'Gagal melakukan logout: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function me(Request $request)
    {
        try {
            $user = $this->authService->getAuthenticatedUser(
                $request->user()
            );

            return response()->json([
                'status' => '200',
                'success' => true,
                'message' => 'Data pengguna berhasil diambil',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'success' => false,
                'message' => 'Gagal mengambil data pengguna: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
}
