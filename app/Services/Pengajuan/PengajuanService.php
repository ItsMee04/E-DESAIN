<?php

namespace App\Services\Pengajuan;

use App\Models\Pengajuan\Pengajuan;
use App\Models\Pengajuan\PengajuanHistory;
use App\Models\Pengajuan\PengajuanJenisMedia;
use App\Models\Pengajuan\PengajuanValidasi;
use App\Models\Pengajuan\StatusPengajuan;
use App\Services\Notification\NotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengajuanService
{
    protected NotificationService $notificationService;
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
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
     * Menyimpan pengajuan desain baru.
     */
    public function storePengajuan(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = Auth::user();

            // Pastikan pengguna login dan terhubung dengan data pegawai.
            if (!$user || !$user->pegawai_id) {
                throw new \Exception(
                    'Pengguna tidak terhubung dengan data pegawai.'
                );
            }

            // Ambil status awal pengajuan.
            $statusDiajukan = StatusPengajuan::where('key', 'diajukan')
                ->firstOrFail();

            // Ambil status menunggu konfirmasi IT.
            $statusMenungguIT = StatusPengajuan::where(
                'key',
                'menunggu_verifikasi_it'
            )->firstOrFail();

            // Simpan data utama pengajuan.
            $pengajuan = Pengajuan::create([
                'nomor' => $this->generateNomor(),
                'pegawai_id' => $user->pegawai_id,
                'unit_id' => $data['unit_id'],
                'statuspengajuan_id' => $statusMenungguIT->id,
                'nama_desain' => $data['nama_desain'],
                'ukuran' => $data['ukuran'],
                'jumlah' => $data['jumlah'],
                'keperluan' => $data['keperluan'],
                'user_id' => $user->id,
                'tanggal' => now(),
            ]);

            // Simpan jenis media yang dipilih.
            foreach ($data['jenis_media'] as $jenisMediaId) {
                PengajuanJenisMedia::create([
                    'pengajuan_id' => $pengajuan->id,
                    'jenismedia_id' => $jenisMediaId,
                ]);
            }

            // Catat riwayat ketika pengajuan dibuat.
            PengajuanHistory::create([
                'pengajuan_id' => $pengajuan->id,
                'statuspengajuan_id' => $statusDiajukan->id,
                'user_id' => $user->id,
                'catatan' => 'Pengajuan desain berhasil dibuat.',
            ]);

            // Catat riwayat bahwa pengajuan menunggu konfirmasi IT.
            PengajuanHistory::create([
                'pengajuan_id' => $pengajuan->id,
                'statuspengajuan_id' => $statusMenungguIT->id,
                'user_id' => $user->id,
                'catatan' => 'Pengajuan menunggu konfirmasi Tim IT.',
            ]);

            // Buat catatan validasi awal untuk Tim IT.
            PengajuanValidasi::create([
                'pengajuan_id' => $pengajuan->id,
                'user_id' => null,
                'jenis' => 'it',
                'status' => 'pending',
                'catatan' => null,
                'validated_at' => null,
            ]);

            // Buat log notifikasi global.
            $this->notificationService->createNotification([
                'pengajuan_id' => $pengajuan->id,
                'title' => 'Pengajuan desain baru',
                'message' => "Pengajuan {$pengajuan->nomor} telah dibuat dan menunggu verifikasi Tim IT.",
                'type' => 'pengajuan_dibuat',
            ]);

            // Kembalikan data pengajuan beserta relasinya.
            return $pengajuan->load([
                'pegawai',
                'unit',
                'statuspengajuan',
                'user',
                'pengajuanjenismedia.jenismedia',
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
