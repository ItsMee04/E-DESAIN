<?php

namespace App\Services\Master;

use App\Models\Master\Profesi;

class ProfesiService
{
    public function getProfesi()
    {
        $data = Profesi::where('status', 1)->get();

        return $data;
    }

    public function createProfesi(array $data): Profesi
    {
        $data = Profesi::create([
            'profesi' => strtoupper($data['profesi']),
        ]);

        return $data;
    }

    // Tambahkan int pada $id agar Intelephense tahu ini adalah angka
    public function updateProfesi(int $id, array $data): ?Profesi
    {
        $profesi = Profesi::find($id);

        if (!$profesi) {
            return null;
        }

        $profesi->update([
            'profesi' => strtoupper($data['profesi']),
        ]);

        return $profesi;
    }

    public function deleteProfesi(int $id): bool
    {
        $profesi = Profesi::find($id);

        if (!$profesi) {
            return false;
        }

        $profesi->status = 0;
        return $profesi->save();
    }
}
