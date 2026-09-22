<?php

namespace App\Services\Pengajuan;

use App\Models\Pengajuan\Pengajuan;
use App\Models\Pengajuan\PengajuanValidasi;
use App\Models\Pengajuan\StatusPengajuan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengajuanValidasiService
{
    /**
     * Mengambil data pengajuan yang menunggu validasi.
     */
    public function getPengajuanValidasi()
    {
        return Pengajuan::with([
            'pegawai',
            'unit',
            'statuspengajuan',
            'pengajuanjenismedia.jenismedia',
        ])
            ->where('status', 1)
            ->whereHas('statuspengajuan', function ($query) {
                $query->where('key', 'menunggu_validasi');
            })
            ->latest()
            ->get();
    }

    public function getDetailPengajuanValidasi(int $id)
    {
        return Pengajuan::with([
            'pegawai',
            'unit',
            'statuspengajuan',
            'pengajuanjenismedia.jenismedia',
        ])
            ->where('status', 1)
            ->findOrFail($id);
    }

    /**
     * Memproses validasi pengajuan.
     */
    public function validasiPengajuan(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            $pengajuan = Pengajuan::where('status', 1)
                ->findOrFail($id);

            $validator = 'pkrs';
            $keputusan = $data['status'];
            $catatan = $data['catatan'] ?? null;

            if ($validator === 'pkrs') {

                $statusMenunggu = StatusPengajuan::where(
                    'key',
                    'menunggu_validasi'
                )->firstOrFail();

                if ($pengajuan->statuspengajuan_id !== $statusMenunggu->id) {
                    throw new \Exception(
                        'Pengajuan tidak berada pada tahap validasi PKRS.'
                    );
                }

                if ($keputusan === 'approved') {
                    $statusBerikutnya = StatusPengajuan::where(
                        'key',
                        'diproses'
                    )->firstOrFail();
                } else {
                    $statusBerikutnya = StatusPengajuan::where(
                        'key',
                        'ditolak'
                    )->firstOrFail();
                }
            } elseif ($validator === 'it') {

                $statusDiproses = StatusPengajuan::where(
                    'key',
                    'diproses'
                )->firstOrFail();

                if ($pengajuan->statuspengajuan_id !== $statusDiproses->id) {
                    throw new \Exception(
                        'Pengajuan belum berada pada tahap validasi IT.'
                    );
                }

                if ($keputusan === 'approved') {
                    $statusBerikutnya = StatusPengajuan::where(
                        'key',
                        'selesai'
                    )->firstOrFail();
                } else {
                    $statusBerikutnya = StatusPengajuan::where(
                        'key',
                        'revisi'
                    )->firstOrFail();
                }
            } else {
                throw new \Exception('Jenis validator tidak valid.');
            }

            PengajuanValidasi::create([
                'pengajuan_id' => $pengajuan->id,
                'user_id' => Auth::id(),
                'jenis' => $validator,
                'status' => $keputusan,
                'catatan' => $catatan,
                'validated_at' => now(),
            ]);

            $pengajuan->update([
                'statuspengajuan_id' => $statusBerikutnya->id,
            ]);

            $pengajuan->history()->create([
                'statuspengajuan_id' => $statusBerikutnya->id,
                'user_id' => Auth::id(),
                'catatan' => $catatan,
            ]);

            return $pengajuan->fresh([
                'pegawai',
                'unit',
                'statuspengajuan',
                'pengajuanjenismedia.jenismedia',
            ]);
        });
    }
}
