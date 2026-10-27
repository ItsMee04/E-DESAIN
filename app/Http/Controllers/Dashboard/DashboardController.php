<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\DashboarService;
// use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected DashboarService $dashboardService;

    public function __construct(DashboarService $dashboarService)
    {
        $this->dashboardService = $dashboarService;
    }

    public function getDataDashboard()
    {
        $totalpengajuan = $this->dashboardService->getTotalPengajuan();
        $totalpengajuanaktif = $this->dashboardService->getTotalPengajuanAktif();

        // Gabungkan kedua variabel ke dalam array data
        $data = [
            'total_pengajuan' => $totalpengajuan,
            'total_pengajuan_aktif' => $totalpengajuanaktif,
        ];

        // Cek jika kedua nilai totalnya adalah 0 (atau sesuaikan logika kosong Anda)
        if ($totalpengajuan === 0 && $totalpengajuanaktif === 0) {
            return response()->json([
                'status' => 404,
                'success' => false,
                'message' => 'Data dashboard e-desain tidak ditemukan',
                'data' => $data
            ], 201); // Catatan: Biasanya status HTTP untuk tidak ditemukan adalah 404, bukan 201
        }

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Data dashboard e-desain berhasil ditemukan',
            'data' => $data
        ], 201);
    }
}
