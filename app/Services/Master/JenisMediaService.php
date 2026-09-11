<?php

namespace App\Services\Master;

use App\Models\Master\JenisMedia;

class JenisMediaService
{
    public function getJenisMedia()
    {
        $data = JenisMedia::where('status', 1)->get();

        return $data;
    }

    public function createJenisMedia(array $data): JenisMedia
    {
        $data = JenisMedia::create([
            'jenismedia' => strtoupper($data['jenismedia']),
        ]);

        return $data;
    }

    // Tambahkan int pada $id agar Intelephense tahu ini adalah angka
    public function updateJenisMedia(int $id, array $data): ?JenisMedia
    {
        $jenismedia = JenisMedia::find($id);

        if (!$jenismedia) {
            return null;
        }

        $jenismedia->update([
            'jenismedia' => strtoupper($data['jenismedia']),
        ]);

        return $jenismedia;
    }

    public function deleteJenisMedia(int $id): bool
    {
        $jenismedia = JenisMedia::find($id);

        if (!$jenismedia) {
            return false;
        }

        $jenismedia->status = 0;
        return $jenismedia->save();
    }
}
