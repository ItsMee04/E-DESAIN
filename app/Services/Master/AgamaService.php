<?php

namespace App\Services\Master;

use App\Models\Master\Agama;

class AgamaService
{
    public function getAgama()
    {
        $data = Agama::where('status', 1)->get();

        return $data;
    }

    public function createAgama(array $data): Agama
    {
        $data = Agama::create([
            'agama' => strtoupper($data['agama']),
        ]);

        return $data;
    }

    // Tambahkan int pada $id agar Intelephense tahu ini adalah angka
    public function updateAgama(int $id, array $data): ?Agama
    {
        $agama = Agama::find($id);

        if (!$agama) {
            return null;
        }

        $agama->update([
            'agama' => strtoupper($data['agama']),
        ]);

        return $agama;
    }

    public function deleteAgama(int $id): bool
    {
        $agama = Agama::find($id);

        if (!$agama) {
            return false;
        }

        $agama->status = 0;
        return $agama->save();
    }
}
