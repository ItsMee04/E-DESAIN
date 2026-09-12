<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\Master\UserService;
// use Illuminate\Http\Request;

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
}
