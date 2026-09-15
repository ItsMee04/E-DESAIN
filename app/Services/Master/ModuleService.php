<?php

namespace App\Services\Master;

use App\Models\Master\Module;

class ModuleService
{
    public function getModules()
    {
        $data = Module::where('status', 1)
            ->with('children')
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();

        return $data;
    }

    public function createModule(array $data): Module
    {
        $module = Module::create([
            'parent_id' => $data['parent_id'] ?? null,
            'name' => $data['name'],
            'key' => $data['key'],
            'route' => $data['route'] ?? null,
            'icon' => $data['icon'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'status' => $data['status'] ?? 1,
        ]);

        return $module;
    }

    public function updateModule(int $id, array $data): ?Module
    {
        $module = Module::find($id);

        if (!$module) {
            return null;
        }

        $module->update([
            'parent_id' => $data['parent_id'] ?? null,
            'name' => $data['name'],
            'key' => $data['key'],
            'route' => $data['route'] ?? null,
            'icon' => $data['icon'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return $module;
    }

    public function deleteModule(int $id): bool
    {
        $module = Module::find($id);

        if (!$module) {
            return false;
        }

        $module->status = 0;

        return $module->save();
    }
}
