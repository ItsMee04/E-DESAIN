<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Master\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getUsers()
    {
        $data = $this->userService->getUsers();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => 404,
                'success' => false,
                'message' => 'Data pengguna tidak ditemukan',
                'data' => $data
            ], 201);
        }

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Data pengguna berhasil ditemukan',
            'data' => $data
        ], 201);
    }

    public function updateUser(Request $request)
    {
        // 1. Cek apakah ID dikirim dan User-nya ada
        $user = User::find($request->id);

        if (!$user) {
            return response()->json([
                'status'  => '404',
                'success' => false,
                'message' => 'Data user tidak ditemukan',
                'data'    => []
            ], 200);
        }

        // 2. Tentukan status kelengkapan akun saat ini
        $hasPassword = !empty($user->password);

        // 3. Susun aturan validasi dinamis berdasarkan kondisi akun
        $rules = [];

        if (!$hasPassword) {

            // Kondisi Awal:
            $rules['password'] = 'required|string|min:6';
        } else {

            // Kondisi Lanjutan:
            // Akun sudah lengkap -> Email, Username, Password opsional untuk diubah

            if ($request->filled('password')) {

                // Proteksi:
                // Password baru tidak boleh sama dengan password lama
                if (Hash::check($request->password, $user->password)) {
                    return response()->json([
                        'status'  => '400',
                        'success' => false,
                        'message' => 'Password baru tidak boleh sama dengan password lama',
                        'data'    => []
                    ], 400);
                }

                $rules['password'] = 'required|string|min:6';
            }
        }

        // 4. Jalankan validasi
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password harus terdiri dari setidaknya 6 karakter.',
                'errors' => $validator->errors()
            ], 422);
        }

        // 5. Mapping data yang aman untuk dikirim ke Service
        $payload = [];

        if ($request->filled('password')) {
            $payload['password'] = $request->password;
        }

        try {

            // 6. Eksekusi perubahan ke Service
            $updatedUser = $this->userService->updateUser(
                $user->id,
                $payload
            );

            return response()->json([
                'status'  => '200',
                'success' => true,
                'message' => 'Data user berhasil diupdate',
                'data'    => $updatedUser
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Controller: Delete User
     */
    public function deleteUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validasi ID gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            $result = $this->userService->deleteUser($request->id);

            if (!$result) {
                return response()->json([
                    'status'  => '404',
                    'success' => false,
                    'message' => 'Data user tidak ditemukan'
                ], 200);
            }

            return response()->json([
                'status'  => '200',
                'success' => true,
                'message' => 'User dan pegawai terkait berhasil dinonaktifkan'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
}
