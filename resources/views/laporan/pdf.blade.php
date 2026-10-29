<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <title>Laporan Pengajuan Desain</title>

    <style>
        @page {
            size: A4 landscape;
            margin: 25px 25px 40px 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #1f2937;
        }

        .header {
            width: 100%;
            border-bottom: 2px solid #B20600;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }

        .header td {
            vertical-align: middle;
        }

        .logo {
            width: 65px;
            height: auto;
        }

        .hospital-name {
            font-size: 16px;
            font-weight: bold;
            color: #B20600;
            margin-bottom: 4px;
        }

        .report-title {
            font-size: 13px;
            font-weight: bold;
            text-align: center;
            margin: 14px 0 5px;
            text-transform: uppercase;
        }

        .report-period {
            text-align: center;
            margin-bottom: 12px;
            font-size: 9px;
        }

        .metadata {
            width: 100%;
            margin-bottom: 10px;
        }

        .metadata td {
            padding: 2px 0;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        table.data th,
        table.data td {
            border: 1px solid #9ca3af;
            padding: 5px 4px;
            vertical-align: top;
            word-wrap: break-word;
        }

        table.data th {
            background-color: #B20600;
            color: #ffffff;
            text-align: center;
            font-size: 8px;
        }

        table.data td {
            font-size: 7.5px;
        }

        table.data tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .center {
            text-align: center;
        }

        .footer-note {
            margin-top: 10px;
            font-size: 8px;
            color: #4b5563;
        }

        .page-number {
            position: fixed;
            bottom: -25px;
            left: 0;
            right: 0;
            text-align: right;
            font-size: 8px;
            color: #6b7280;
        }

        .no-data {
            text-align: center;
            padding: 15px;
        }
    </style>
</head>

<body>

    <table class="header">
        <tr>
            <td style="width: 80px;">
                <img class="logo" src="{{ public_path('assets/img/logo.png') }}" alt="Logo RSU Wiradadi Husada">
            </td>

            <td>
                <div class="hospital-name">
                    RSU WIRADADI HUSADA
                </div>

                <div>
                    E-Desain &mdash; Sistem Pengajuan Desain
                </div>
            </td>
        </tr>
    </table>

    <div class="report-title">
        Laporan Pengajuan Desain
    </div>

    <div class="report-period">
        Periode:
        @if (!empty($filters['tanggal_mulai']) || !empty($filters['tanggal_akhir']))
            {{ !empty($filters['tanggal_mulai'])
                ? \Carbon\Carbon::parse($filters['tanggal_mulai'])->format('d/m/Y')
                : 'Awal' }}
            s.d.
            {{ !empty($filters['tanggal_akhir'])
                ? \Carbon\Carbon::parse($filters['tanggal_akhir'])->format('d/m/Y')
                : 'Sekarang' }}
        @else
            Semua tanggal
        @endif
    </div>

    <table class="metadata">
        <tr>
            <td style="width: 100px;">Tanggal cetak</td>
            <td style="width: 10px;">:</td>
            <td>{{ $tanggalCetak->format('d/m/Y H:i') }}</td>
        </tr>

        <tr>
            <td>Jumlah pengajuan</td>
            <td>:</td>
            <td>{{ $laporan->count() }} data</td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th style="width: 3%;">No.</th>
                <th style="width: 10%;">Nomor Pengajuan</th>
                <th style="width: 7%;">Tanggal</th>
                <th style="width: 9%;">Pemohon</th>
                <th style="width: 9%;">Unit</th>
                <th style="width: 12%;">Nama Desain</th>
                <th style="width: 11%;">Jenis Media</th>
                <th style="width: 8%;">Ukuran</th>
                <th style="width: 5%;">Jumlah</th>
                <th style="width: 14%;">Keperluan</th>
                <th style="width: 12%;">Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($laporan as $item)
                <tr>
                    <td class="center">{{ $loop->iteration }}</td>

                    <td>{{ $item->nomor ?? '-' }}</td>

                    <td>
                        {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') : '-' }}
                    </td>

                    <td>{{ $item->pegawai->nama ?? '-' }}</td>

                    <td>{{ $item->unit->unit ?? ($item->unit->nama ?? '-') }}</td>

                    <td>{{ $item->nama_desain ?? '-' }}</td>

                    <td>
                        @forelse ($item->pengajuanjenismedia as $media)
                            {{ $media->jenismedia->nama ?? ($media->jenismedia->name ?? '-') }}
                            @if (!$loop->last)
                                ,
                            @endif
                        @empty
                            -
                        @endforelse
                    </td>

                    <td>{{ $item->ukuran ?? '-' }}</td>

                    <td class="center">{{ $item->jumlah ?? '-' }}</td>

                    <td>{{ $item->keperluan ?? '-' }}</td>

                    <td>
                        {{ $item->statuspengajuan->name ?? ($item->statuspengajuan->nama ?? '-') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="no-data">
                        Tidak ada data pengajuan untuk filter yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-note">
        Dokumen ini dihasilkan secara otomatis oleh sistem E-Desain.
    </div>

</body>

</html>
