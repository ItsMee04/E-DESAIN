<?php

namespace App\Services\Master;

use App\Models\Master\UserPermission;

class UserPermissionService
{
    public function getUserPermissions(int $userId)
    {
        $data = UserPermission::where('user_id', $userId)
            ->with('module')
            ->get();

        return $data;
    }

    public function savePermissions(
        int $userId,
        array $moduleIds
    ): bool {
        UserPermission::where('user_id', $userId)->delete();

        foreach ($moduleIds as $moduleId) {

            UserPermission::create([
                'user_id' => $userId,
                'module_id' => $moduleId,
            ]);
        }

        return true;
    }

    public function getPermissionsByUserId(int $userId)
    {
        return UserPermission::where('user_id', $userId)
            ->pluck('module_id')
            ->toArray();
    }
}
