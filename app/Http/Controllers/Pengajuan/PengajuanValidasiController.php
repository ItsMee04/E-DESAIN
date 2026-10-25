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
        $data = $this->pengajuanValidasiService->getPengajuanValidasiPKRS();

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

    /**
     * Menampilkan data pengajuan yang menunggu validasi.
     */
    public function getPengajuanValidasiIT()
    {
        $data = $this->pengajuanValidasiService->getPengajuanValidasiIT();

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

    public function validasiPengajuanIT(Request $request, int $id)
    {
        $data = $this->pengajuanValidasiService
            ->validasiPengajuanIT($id, $request->all());

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Pengajuan berhasil divalidasi',
            'data' => $data
        ], 201);
    }

    /**
     * Konfirmasi desain siap diambil.
     */
    public function konfirmasiSiapDiambil(int $id)
    {
        $data = $this->pengajuanValidasiService
            ->konfirmasiSiapDiambil($id);

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Pengajuan berhasil diubah menjadi siap diambil',
            'data' => $data,
        ], 200);
    }

    /**
     * Konfirmasi desain sudah diambil oleh pegawai.
     */
    public function konfirmasiSelesaiDiambil(int $id)
    {
        $data = $this->pengajuanValidasiService
            ->konfirmasiSelesaiDiambil($id);

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Pengajuan berhasil diselesaikan',
            'data' => $data,
        ], 200);
    }
}
