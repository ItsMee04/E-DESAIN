<?php

namespace App\Services\Pengajuan;

use App\Models\Pengajuan\StatusPengajuan;

class StatusPengajuanService
{
    public function getStatusPengajuan()
    {
        $data = StatusPengajuan::where('status', 1)->get();

        return $data;
    }

    public function createStatusPengajuan(array $data): StatusPengajuan
    {
        $data = StatusPengajuan::create([
            'name' => strtoupper($data['name']),
            'key' => strtoupper($data['key']),
            'description' => $data['description'],
            'sort_order' => $data['sort_order'] ?? 0
        ]);

        return $data;
    }

    // Tambahkan int pada $id agar Intelephense tahu ini adalah angka
    public function updateStatusPengajuan(int $id, array $data): ?StatusPengajuan
    {
        $statusPengajuan = StatusPengajuan::find($id);

        if (!$statusPengajuan) {
            return null;
        }

        $statusPengajuan->update([
            'name' => strtoupper($data['name']),
            'key' => strtoupper($data['key']),
            'description' => $data['description'],
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return $statusPengajuan;
    }

    public function deleteStatusPengajuan(int $id): bool
    {
        $statusPengajuan = StatusPengajuan::find($id);

        if (!$statusPengajuan) {
            return false;
        }

        $statusPengajuan->status = 0;
        return $statusPengajuan->save();
    }
}
