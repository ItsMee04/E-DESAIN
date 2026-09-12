<?php

namespace App\Services\Master;

use App\Models\User;

class UserService
{
    public function getUsers()
    {
        $data = User::with(['pegawai'])->where('status', 1)->get();

        return $data;
    }

    public function createUser(array $data): User
    {
        $data = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        return $data;
    }

    // Tambahkan int pada $id agar Intelephense tahu ini adalah angka
    public function updateUser(int $id, array $data): ?User
    {
        $user = User::find($id);

        if (!$user) {
            return null;
        }

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        return $user;
    }

    public function deleteUser(int $id): bool
    {
        $user = User::find($id);

        if (!$user) {
            return false;
        }

        $user->status = 0;
        return $user->save();
    }
}
