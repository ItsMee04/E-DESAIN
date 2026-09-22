<?php

namespace App\Http\Controllers\Pengajuan;

use App\Http\Controllers\Controller;
use App\Services\Pengajuan\PengajuanValidasiService;
use Illuminate\Http\Request;

class PengajuanValidasiController extends Controller
{
    protected PengajuanValidasiService $pengajuanValidasiService;

    public function __construct(
        PengajuanValidasiService $pengajuanValidasiService
    ) {
        $this->pengajuanValidasiService = $pengajuanValidasiService;
    }

    /**
     * Menampilkan data pengajuan yang menunggu validasi.
     */
    public function getPengajuanValidasi()
    {
        $data = $this->pengajuanValidasiService->getPengajuanValidasi();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => 404,
                'success' => false,
                'message' => 'Data pengajuan validasi tidak ditemukan',
                'data' => $data
            ], 201);
        }

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Data pengajuan validasi berhasil ditemukan',
            'data' => $data
        ], 201);
    }

    public function getDetailPengajuanValidasi(int $id)
    {
        $data = $this->pengajuanValidasiService
            ->getDetailPengajuanValidasi($id);

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Detail pengajuan validasi berhasil ditemukan',
            'data' => $data
        ], 201);
    }

    public function validasiPengajuan(Request $request, int $id)
    {
        $data = $this->pengajuanValidasiService
            ->validasiPengajuan($id, $request->all());

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Pengajuan berhasil divalidasi',
            'data' => $data
        ], 201);
    }
}
