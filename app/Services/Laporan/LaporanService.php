<?php

namespace App\Services\Laporan;

use App\Models\Pengajuan\Pengajuan;
use Illuminate\Support\Facades\Validator;

class LaporanService
{
    /**
     * Mengambil data laporan berdasarkan periode dan status.
     */
    public function getLaporan(array $filters)
    {
        $this->validateFilter($filters);

        $query = Pengajuan::with([
            'pegawai',
            'unit',
            'statuspengajuan',
            'pengajuanjenismedia.jenismedia',
        ])
            ->whereDate('tanggal', '>=', $filters['tanggal_mulai'])
            ->whereDate('tanggal', '<=', $filters['tanggal_akhir']);

        if (!empty($filters['status_id'])) {
            $query->where('statuspengajuan_id', $filters['status_id']);
        }

        return $query->latest()->get();
    }

    /**
     * Mengambil statistik pengajuan berdasarkan periode.
     * Filter status tidak digunakan untuk statistik.
     */
    public function getStatistik(array $filters): array
    {
        $this->validateFilter($filters);

        $query = Pengajuan::query()
            ->whereDate('tanggal', '>=', $filters['tanggal_mulai'])
            ->whereDate('tanggal', '<=', $filters['tanggal_akhir']);

        $totalPengajuan = (clone $query)->count();

        $dalamProses = (clone $query)
            ->whereHas('statuspengajuan', function ($status) {
                $status->whereIn('key', [
                    'menunggu_verifikasi_it',
                    'menunggu_acc_pkrs',
                    'diproses',
                    'siap_diambil',
                ]);
            })
            ->count();

        $selesai = (clone $query)
            ->whereHas('statuspengajuan', function ($status) {
                $status->where('key', 'selesai');
            })
            ->count();

        $ditolakDibatalkan = (clone $query)
            ->whereHas('statuspengajuan', function ($status) {
                $status->whereIn('key', [
                    'ditolak',
                    'dibatalkan',
                ]);
            })
            ->count();

        return [
            'total_pengajuan' => $totalPengajuan,
            'dalam_proses' => $dalamProses,
            'selesai' => $selesai,
            'ditolak_dibatalkan' => $ditolakDibatalkan,
        ];
    }

    /**
     * Validasi filter laporan.
     */
    private function validateFilter(array $filters): void
    {
        Validator::make($filters, [
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_akhir' => [
                'required',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
            'status_id' => [
                'nullable',
                'integer',
                'exists:statuspengajuan,id',
            ],
        ], [
            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_akhir.required' => 'Tanggal akhir wajib diisi.',
            'tanggal_akhir.after_or_equal' =>
            'Tanggal akhir tidak boleh sebelum tanggal mulai.',
            'status_id.exists' => 'Status pengajuan tidak valid.',
        ])->validate();
    }

    /**
     * Mengambil data laporan untuk export Excel.
     */
    public function getLaporanExport(array $filters)
    {
        return $this->getLaporan($filters);
    }
}
