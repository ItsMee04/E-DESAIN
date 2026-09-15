<?php

namespace App\Services\Authentication;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthenticationService
{
    /**
     * Proses login pengguna.
     */
    public function login(array $data): ?array
    {
        $user = User::where('username', $data['username'])
            ->first();

        // User tidak ditemukan
        if (!$user) {
            return null;
        }

        // User tidak aktif
        if ((int) $user->status !== 1) {
            return null;
        }

        // Password tidak sesuai
        if (!Hash::check($data['password'], $user->password)) {
            return null;
        }

        // Buat token Sanctum
        $token = $user->createToken(
            'e-desain-token'
        )->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Logout pengguna dari token yang sedang digunakan.
     */
    public function logout(User $user): bool
    {
        $currentToken = $user->currentAccessToken();

        if (!$currentToken) {
            return false;
        }

        $user->tokens()
            ->where('id', $currentToken->id)
            ->delete();

        return true;
    }

    /**
     * Mengambil data pengguna yang sedang login.
     */
    public function getAuthenticatedUser(User $user): User
    {
        return $user;
    }
}
