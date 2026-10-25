<?php

namespace App\Services\Pengajuan;

use App\Models\Pengajuan\Pengajuan;
use App\Models\Pengajuan\PengajuanHistory;
use App\Models\Pengajuan\PengajuanValidasi;
use App\Models\Pengajuan\StatusPengajuan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengajuanValidasiService
{
    /**
     * Mengambil data pengajuan yang menunggu validasi.
     */
    public function getPengajuanValidasiIT()
    {
        return Pengajuan::with([
            'pegawai',
            'unit',
            'statuspengajuan',
            'pengajuanjenismedia.jenismedia',
        ])
            ->where('status', 1)
            ->whereHas('statuspengajuan', function ($query) {
                $query->where('key', 'menunggu_verifikasi_it');
            })
            ->latest()
            ->get();
    }


    /**
     * Memproses validasi pengajuan oleh IT.
     */
    public function validasiPengajuanIT(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $user = Auth::user();

            $pengajuan = Pengajuan::where('status', 1)
                ->findOrFail($id);

            $keputusan = $data['status'];
            $catatan = $data['catatan'] ?? null;

            // Pastikan keputusan valid.
            if (!in_array($keputusan, ['approved', 'rejected'], true)) {
                throw new \Exception(
                    'Keputusan validasi IT tidak valid.'
                );
            }

            // Pastikan pengajuan sedang menunggu verifikasi IT.
            $statusMenungguIT = StatusPengajuan::where(
                'key',
                'menunggu_verifikasi_it'
            )->firstOrFail();

            if ($pengajuan->statuspengajuan_id !== $statusMenungguIT->id) {
                throw new \Exception(
                    'Pengajuan tidak berada pada tahap verifikasi IT.'
                );
            }

            // Tentukan status berikutnya.
            $keyStatusBerikutnya = $keputusan === 'approved'
                ? 'menunggu_acc_pkrs'
                : 'ditolak';

            $statusBerikutnya = StatusPengajuan::where(
                'key',
                $keyStatusBerikutnya
            )->firstOrFail();

            // Perbarui catatan validasi IT yang dibuat saat store.
            $validasi = PengajuanValidasi::where(
                'pengajuan_id',
                $pengajuan->id
            )
                ->where('jenis', 'it')
                ->firstOrFail();

            $validasi->update([
                'user_id' => $user->id,
                'status' => $keputusan,
                'catatan' => $catatan,
                'validated_at' => now(),
            ]);

            // Perbarui status aktif pengajuan.
            $pengajuan->update([
                'statuspengajuan_id' => $statusBerikutnya->id,
            ]);

            // Catat perubahan status dalam history.
            PengajuanHistory::create([
                'pengajuan_id' => $pengajuan->id,
                'statuspengajuan_id' => $statusBerikutnya->id,
                'user_id' => $user->id,
                'catatan' => $catatan
                    ?? (
                        $keputusan === 'approved'
                        ? 'Pengajuan disetujui oleh Tim IT dan menunggu ACC PKRS.'
                        : 'Pengajuan ditolak oleh Tim IT.'
                    ),
            ]);

            return $pengajuan->fresh([
                'pegawai',
                'unit',
                'statuspengajuan',
                'pengajuanjenismedia.jenismedia',
                'pengajuanhistory.statusPengajuan',
                'pengajuanhistory.user',
                'pengajuanvalidasi',
            ]);
        });
    }


    /**
     * Mengambil data pengajuan yang menunggu validasi.
     */
    public function getPengajuanValidasiPKRS()
    {
        return Pengajuan::with([
            'pegawai',
            'unit',
            'statuspengajuan',
            'pengajuanjenismedia.jenismedia',
        ])
            ->where('status', 1)
            ->whereHas('statuspengajuan', function ($query) {
                $query->where('key', 'menunggu_acc_pkrs');
            })
            ->latest()
            ->get();
    }


    /**
     * Memproses validasi pengajuan oleh PKRS.
     */
    public function validasiPengajuan(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $user = Auth::user();

            $pengajuan = Pengajuan::where('status', 1)
                ->findOrFail($id);

            $keputusan = $data['status'];
            $catatan = $data['catatan'] ?? null;

            // Pastikan keputusan valid.
            if (!in_array($keputusan, ['approved', 'rejected'], true)) {
                throw new \Exception(
                    'Keputusan validasi PKRS tidak valid.'
                );
            }

            // Pastikan pengajuan menunggu ACC PKRS.
            $statusMenungguPKRS = StatusPengajuan::where(
                'key',
                'menunggu_acc_pkrs'
            )->firstOrFail();

            if ($pengajuan->statuspengajuan_id !== $statusMenungguPKRS->id) {
                throw new \Exception(
                    'Pengajuan tidak berada pada tahap validasi PKRS.'
                );
            }

            // Tentukan status berikutnya.
            $keyStatusBerikutnya = $keputusan === 'approved'
                ? 'diproses'
                : 'ditolak';

            $statusBerikutnya = StatusPengajuan::where(
                'key',
                $keyStatusBerikutnya
            )->firstOrFail();

            // Simpan atau perbarui catatan validasi PKRS.
            PengajuanValidasi::updateOrCreate(
                [
                    'pengajuan_id' => $pengajuan->id,
                    'jenis' => 'pkrs',
                ],
                [
                    'user_id' => $user->id,
                    'status' => $keputusan,
                    'catatan' => $catatan,
                    'validated_at' => now(),
                ]
            );

            // Perbarui status aktif pengajuan.
            $pengajuan->update([
                'statuspengajuan_id' => $statusBerikutnya->id,
            ]);

            // Catat perubahan status dalam history.
            PengajuanHistory::create([
                'pengajuan_id' => $pengajuan->id,
                'statuspengajuan_id' => $statusBerikutnya->id,
                'user_id' => $user->id,
                'catatan' => $catatan
                    ?? (
                        $keputusan === 'approved'
                        ? 'Pengajuan disetujui PKRS dan masuk tahap pengerjaan.'
                        : 'Pengajuan ditolak oleh PKRS.'
                    ),
            ]);

            // Kembalikan data terbaru beserta relasinya.
            return $pengajuan->fresh([
                'pegawai',
                'unit',
                'statuspengajuan',
                'pengajuanjenismedia.jenismedia',
                'pengajuanhistory.statusPengajuan',
                'pengajuanhistory.user',
                'pengajuanvalidasi',
            ]);
        });
    }

        /**
     * Mengubah status pengajuan menjadi siap diambil.
     */
    public function konfirmasiSiapDiambil(int $id)
    {
        return DB::transaction(function () use ($id) {
            $user = Auth::user();

            // Ambil pengajuan dan kunci selama transaksi.
            $pengajuan = Pengajuan::where('status', 1)
                ->lockForUpdate()
                ->findOrFail($id);

            // Pastikan pengajuan sedang diproses.
            $statusDiproses = StatusPengajuan::where(
                'key',
                'diproses'
            )->firstOrFail();

            if ($pengajuan->statuspengajuan_id !== $statusDiproses->id) {
                throw new \Exception(
                    'Pengajuan tidak berada pada tahap pengerjaan.'
                );
            }

            // Ambil status siap diambil.
            $statusSiapDiambil = StatusPengajuan::where(
                'key',
                'siap_diambil'
            )->firstOrFail();

            // Perbarui status aktif pengajuan.
            $pengajuan->update([
                'statuspengajuan_id' => $statusSiapDiambil->id,
            ]);

            // Catat perubahan status ke history.
            PengajuanHistory::create([
                'pengajuan_id' => $pengajuan->id,
                'statuspengajuan_id' => $statusSiapDiambil->id,
                'user_id' => $user->id,
                'catatan' => 'Desain telah selesai dikerjakan oleh Tim IT dan siap diambil.',
            ]);

            return $pengajuan->fresh([
                'pegawai',
                'unit',
                'statuspengajuan',
                'pengajuanjenismedia.jenismedia',
                'pengajuanhistory.statusPengajuan',
                'pengajuanhistory.user',
            ]);
        });
    }

    /**
     * Mengubah status pengajuan menjadi selesai setelah diambil.
     */
    public function konfirmasiSelesaiDiambil(int $id)
    {
        return DB::transaction(function () use ($id) {
            $user = Auth::user();

            // Ambil pengajuan dan kunci selama transaksi.
            $pengajuan = Pengajuan::where('status', 1)
                ->lockForUpdate()
                ->findOrFail($id);

            // Pastikan pengajuan sedang menunggu pengambilan.
            $statusSiapDiambil = StatusPengajuan::where(
                'key',
                'siap_diambil'
            )->firstOrFail();

            if ($pengajuan->statuspengajuan_id !== $statusSiapDiambil->id) {
                throw new \Exception(
                    'Pengajuan belum berada pada tahap siap diambil.'
                );
            }

            // Ambil status selesai.
            $statusSelesai = StatusPengajuan::where(
                'key',
                'selesai'
            )->firstOrFail();

            // Perbarui status aktif pengajuan.
            $pengajuan->update([
                'statuspengajuan_id' => $statusSelesai->id,
            ]);

            // Catat perubahan status ke history.
            PengajuanHistory::create([
                'pengajuan_id' => $pengajuan->id,
                'statuspengajuan_id' => $statusSelesai->id,
                'user_id' => $user->id,
                'catatan' => 'Desain telah diserahkan oleh Tim IT dan diterima oleh pegawai.',
            ]);

            return $pengajuan->fresh([
                'pegawai',
                'unit',
                'statuspengajuan',
                'pengajuanjenismedia.jenismedia',
                'pengajuanhistory.statusPengajuan',
                'pengajuanhistory.user',
            ]);
        });
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
}
