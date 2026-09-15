<?php

namespace App\Services\Master;

use App\Models\Master\Unit;

class UnitService
{
    public function getUnit()
    {
        $data = Unit::where('status', 1)->get();

        return $data;
    }

    public function createUnit(array $data): Unit
    {
        $data = Unit::create([
            'unit' => strtoupper($data['unit']),
        ]);

        return $data;
    }

    // Tambahkan int pada $id agar Intelephense tahu ini adalah angka
    public function updateUnit(int $id, array $data): ?Unit
    {
        $unit = Unit::find($id);

        if (!$unit) {
            return null;
        }

        $unit->update([
            'unit' => strtoupper($data['unit']),
        ]);

        return $unit;
    }

    public function deleteUnit(int $id): bool
    {
        $unit = Unit::find($id);

        if (!$unit) {
            return false;
        }

        $unit->status = 0;
        return $unit->save();
    }
}
