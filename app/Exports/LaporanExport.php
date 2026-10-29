<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected Collection $laporan;

    public function __construct(Collection $laporan)
    {
        $this->laporan = $laporan;
    }

    public function collection(): Collection
    {
        return $this->laporan;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor Pengajuan',
            'Tanggal Pengajuan',
            'Nama Pengaju',
            'Unit',
            'Nama Desain',
            'Jenis Media',
            'Ukuran',
            'Jumlah Cetak',
            'Keperluan',
            'Status',
        ];
    }

    public function map($pengajuan): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $pengajuan->nomor ?? '-',
            $pengajuan->tanggal
                ? \Illuminate\Support\Carbon::parse($pengajuan->tanggal)->format('d-m-Y')
                : '-',
            $pengajuan->pegawai?->nama ?? '-',
            $pengajuan->unit?->unit ?? '-',
            $pengajuan->nama_desain ?? '-',
            $pengajuan->pengajuanjenismedia
                ->map(fn($media) => $media->jenismedia?->jenismedia)
                ->filter()
                ->implode(', ') ?: '-',
            $pengajuan->ukuran ?? '-',
            $pengajuan->jumlah ?? 0,
            $pengajuan->keperluan ?? '-',
            $pengajuan->statuspengajuan?->name ?? '-',
        ];
    }
}
