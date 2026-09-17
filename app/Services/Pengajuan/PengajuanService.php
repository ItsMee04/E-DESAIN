<?php

namespace App\Services\Pengajuan;

use App\Models\Pengajuan\Pengajuan;
use Illuminate\Support\Facades\DB;

class PengajuanService
{
    /**
     * Get all pengajuan.
     */
    public function getPengajuan()
    {
        return Pengajuan::with([
            'pegawai',
            'unit',
            'statuspengajuan',
            'user',
            'pengajuanjenismedia.jenisMedia',
        ])
            ->latest()
            ->get();
    }

    /**
     * Get pengajuan by ID.
     */
    public function getPengajuanById(int $id)
    {
        return Pengajuan::with([
            'pegawai',
            'unit',
            'statuspengajuan',
            'user',
            'pengajuanjenismedia.jenisMedia',
            'pengajuanvalidasi.user',
            'pengajuanhistory.statusPengajuan',
            'pengajuanhistory.user',
        ])->findOrFail($id);
    }

    /**
     * Store pengajuan.
     */
    public function storePengajuan(array $data)
    {
        return DB::transaction(function () use ($data) {

            $pengajuan = Pengajuan::create([
                'nomor' => $this->generateNomor(),
                'pegawai_id' => $data['pegawai_id'],
                'unit_id' => $data['unit_id'],
                'statuspengajuan_id' => $data['statuspengajuan_id'],
                'nama_desain' => $data['nama_desain'],
                'ukuran' => $data['ukuran'],
                'jumlah' => $data['jumlah'],
                'keperluan' => $data['keperluan'],
                'user_id' => $data['user_id'],
            ]);

            return $pengajuan->load([
                'pegawai',
                'unit',
                'statuspengajuan',
                'user',
                'pengajuanjenismedia.jenisMedia',
            ]);
        });
    }

    /**
     * Update pengajuan.
     */
    public function updatePengajuan(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            $pengajuan = Pengajuan::findOrFail($id);

            $pengajuan->update([
                'pegawai_id' => $data['pegawai_id'],
                'unit_id' => $data['unit_id'],
                'statuspengajuan_id' => $data['statuspengajuan_id'],
                'nama_desain' => $data['nama_desain'],
                'ukuran' => $data['ukuran'],
                'jumlah' => $data['jumlah'],
                'keperluan' => $data['keperluan'],
                'user_id' => $data['user_id'],
            ]);

            return $pengajuan->fresh([
                'pegawai',
                'unit',
                'statuspengajuan',
                'user',
                'pengajuanjenismedia.jenisMedia',
            ]);
        });
    }

    /**
     * Delete pengajuan.
     */
    public function deletePengajuan(int $id)
    {
        return DB::transaction(function () use ($id) {

            $pengajuan = Pengajuan::findOrFail($id);

            $pengajuan->delete();

            return true;
        });
    }

    /**
     * Generate nomor pengajuan otomatis.
     */
    private function generateNomor(): string
    {
        $tanggal = now()->format('Ymd');

        $prefix = "PD-{$tanggal}-";

        $lastPengajuan = Pengajuan::where(
            'nomor',
            'like',
            "{$prefix}%"
        )
            ->orderByDesc('id')
            ->first();

        if (!$lastPengajuan) {
            $urutan = 1;
        } else {
            $nomorTerakhir = (int) substr(
                $lastPengajuan->nomor,
                -4
            );

            $urutan = $nomorTerakhir + 1;
        }

        return $prefix . str_pad(
            $urutan,
            4,
            '0',
            STR_PAD_LEFT
        );
    }
}
