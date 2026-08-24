<?php

namespace App\Services\Master;

use App\Models\Master\JenisKelamin;

class JenisKelaminService
{
    public function getJenisKelamin()
    {
        $data = JenisKelamin::where('status', 1)->get();

        return $data;
    }

    public function createJenisKelamin(array $data): JenisKelamin
    {
        $data = JenisKelamin::create([
            'jeniskelamin' => strtoupper($data['jeniskelamin']),
        ]);

        return $data;
    }

    // Tambahkan int pada $id agar Intelephense tahu ini adalah angka
    public function updateJenisKelamin(int $id, array $data): ?JenisKelamin
    {
        $jeniskelamin = JenisKelamin::find($id);

        if (!$jeniskelamin) {
            return null;
        }

        $jeniskelamin->update([
            'jeniskelamin' => strtoupper($data['jeniskelamin']),
        ]);

        return $jeniskelamin;
    }

    public function deleteJenisKelamin(int $id): bool
    {
        $jeniskelamin = JenisKelamin::find($id);

        if (!$jeniskelamin) {
            return false;
        }

        $jeniskelamin->status = 0;
        return $jeniskelamin->save();
    }
}
