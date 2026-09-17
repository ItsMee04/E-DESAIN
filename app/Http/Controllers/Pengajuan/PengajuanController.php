<?php

namespace App\Http\Controllers\Pengajuan;

use App\Http\Controllers\Controller;
use App\Services\Pengajuan\PengajuanService;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    protected PengajuanService $pengajuanService;

    public function __construct(PengajuanService $pengajuanService)
    {
        $this->pengajuanService = $pengajuanService;
    }

    public function getPengajuan()
    {
        $data = $this->pengajuanService->getPengajuan();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => 404,
                'success' => false,
                'message' => 'Data pengajuan tidak ditemukan',
                'data' => $data
            ], 201);
        }

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Data pengajuan berhasil ditemukan',
            'data' => $data
        ], 201);
    }

    public function getPengajuanById(Request $request)
    {
        $data = $this->pengajuanService->getPengajuanById(
            $request->id
        );

        if (!$data) {
            return response()->json([
                'status' => 404,
                'success' => false,
                'message' => 'Data pengajuan tidak ditemukan',
                'data' => null
            ], 201);
        }

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Data pengajuan berhasil ditemukan',
            'data' => $data
        ], 201);
    }

    public function storePengajuan(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|integer|exists:unit,id',
            'nama_desain' => 'required|string|max:255',
            'ukuran' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'keperluan' => 'required|string',
            'jenis_media' => 'required|array|min:1',
            'jenis_media.*' => 'required|integer|exists:jenismedia,id'
        ]);

        $pengajuan = $this->pengajuanService->storePengajuan([
            'unit_id' => $request->unit_id,
            'nama_desain' => $request->nama_desain,
            'ukuran' => $request->ukuran,
            'jumlah' => $request->jumlah,
            'keperluan' => $request->keperluan,
            'jenis_media' => $request->jenis_media
        ]);

        return response()->json([
            'status' => 201,
            'success' => true,
            'message' => 'Data pengajuan berhasil disimpan',
            'data' => $pengajuan
        ], 201);
    }

    public function updatePengajuan(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:pengajuan,id',
            'unit_id' => 'required|integer|exists:unit,id',
            'nama_desain' => 'required|string|max:255',
            'ukuran' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'keperluan' => 'required|string',
            'jenis_media' => 'required|array|min:1',
            'jenis_media.*' => 'required|integer|exists:jenismedia,id'
        ]);

        $pengajuan = $this->pengajuanService->updatePengajuan(
            $request->id,
            [
                'unit_id' => $request->unit_id,
                'nama_desain' => $request->nama_desain,
                'ukuran' => $request->ukuran,
                'jumlah' => $request->jumlah,
                'keperluan' => $request->keperluan,
                'jenis_media' => $request->jenis_media
            ]
        );

        if (!$pengajuan) {
            return response()->json([
                'status' => 404,
                'success' => false,
                'message' => 'Data pengajuan tidak ditemukan',
                'data' => null
            ], 201);
        }

        return response()->json([
            'status' => 201,
            'success' => true,
            'message' => 'Data pengajuan berhasil diperbarui',
            'data' => $pengajuan
        ], 201);
    }

    public function deletePengajuan(Request $request)
    {
        $deleted = $this->pengajuanService->deletePengajuan(
            $request->id
        );

        if (!$deleted) {
            return response()->json([
                'status' => 404,
                'success' => false,
                'message' => 'Data pengajuan tidak ditemukan',
            ], 201);
        }

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Data pengajuan berhasil dihapus',
        ], 201);
    }
}
