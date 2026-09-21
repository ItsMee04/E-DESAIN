<?php

namespace App\Services\Pengajuan;

use App\Models\Pengajuan\Pengajuan;
use App\Models\Pengajuan\PengajuanHistory;
use App\Models\Pengajuan\PengajuanJenisMedia;
use App\Models\Pengajuan\PengajuanValidasi;
use App\Models\Pengajuan\StatusPengajuan;
use Illuminate\Support\Facades\Auth;
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
            'pengajuanhistory.statusPengajuan',
            'pengajuanhistory.user.pegawai',
        ])
            ->where('status', 1)
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
            $user = Auth::user();

            $statusDiajukan = StatusPengajuan::where('key', 'diajukan')
                ->firstOrFail();

            $statusMenungguValidasi = StatusPengajuan::where('key', 'menunggu_validasi')
                ->firstOrFail();

            $pengajuan = Pengajuan::create([
                'nomor' => $this->generateNomor(),
                'pegawai_id' => $user->pegawai_id,
                'unit_id' => $data['unit_id'],
                'statuspengajuan_id' => $statusMenungguValidasi->id,
                'nama_desain' => $data['nama_desain'],
                'ukuran' => $data['ukuran'],
                'jumlah' => $data['jumlah'],
                'keperluan' => $data['keperluan'],
                'user_id' => $user->id,
            ]);

            foreach ($data['jenis_media'] as $jenisMediaId) {
                PengajuanJenisMedia::create([
                    'pengajuan_id' => $pengajuan->id,
                    'jenismedia_id' => $jenisMediaId,
                ]);
            }

            PengajuanHistory::create([
                'pengajuan_id' => $pengajuan->id,
                'statuspengajuan_id' => $statusDiajukan->id,
                'user_id' => $user->id,
                'catatan' => 'Pengajuan desain berhasil dibuat.',
            ]);

            PengajuanHistory::create([
                'pengajuan_id' => $pengajuan->id,
                'statuspengajuan_id' => $statusMenungguValidasi->id,
                'user_id' => $user->id,
                'catatan' => 'Pengajuan menunggu proses validasi.',
            ]);

            PengajuanValidasi::create([
                'pengajuan_id' => $pengajuan->id,
                'user_id' => null,
                'jenis' => 'validasi',
                'status' => 'pending',
                'catatan' => null,
                'validated_at' => null,
            ]);

            return $pengajuan->load([
                'pegawai',
                'unit',
                'statuspengajuan',
                'user',
                'pengajuanjenismedia.jenisMedia',
                'pengajuanhistory.statusPengajuan',
                'pengajuanhistory.user',
                'pengajuanvalidasi',
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
                'unit_id' => $data['unit_id'],
                'nama_desain' => $data['nama_desain'],
                'ukuran' => $data['ukuran'],
                'jumlah' => $data['jumlah'],
                'keperluan' => $data['keperluan'],
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

            $pengajuan->update([
                'status' => 0,
            ]);

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
