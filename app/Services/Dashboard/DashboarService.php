<?php

namespace App\Services\Dashboard;

use App\Models\Pengajuan\Pengajuan;

class DashboarService
{
    public function getTotalPengajuan()
    {
        $totalpengajuan = Pengajuan::count();

        return $totalpengajuan;
    }

    public function getTotalPengajuanAktif()
    {
        $totalpengajuan = Pengajuan::where('status', 1)->count();

        return $totalpengajuan;
    }

    public function getTotalPengajuanSelesai()
    {
        $totalpengajuan = Pengajuan::where('status', 2)->count();

        return $totalpengajuan;
    }

    public function getTotalPengajuanDibatalkan()
    {
        $totalpengajuan = Pengajuan::where('status', 0)->count();

        return $totalpengajuan;
    }
}
