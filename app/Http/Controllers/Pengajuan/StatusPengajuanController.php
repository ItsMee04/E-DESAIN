<?php

namespace App\Http\Controllers\Pengajuan;

use App\Http\Controllers\Controller;
use App\Services\Pengajuan\StatusPengajuanService;
use Illuminate\Http\Request;

class StatusPengajuanController extends Controller
{
    protected StatusPengajuanService $statusPengajuanService;

    public function __construct(StatusPengajuanService $statusPengajuanService)
    {
        $this->statusPengajuanService = $statusPengajuanService;
    }

    public function getStatusPengajuan()
    {
        $data = $this->statusPengajuanService->getStatusPengajuan();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => 404,
                'success' => false,
                'message' => 'Data status pengajuan tidak ditemukan',
                'data' => $data
            ], 201);
        }

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Data status pengajuan berhasil ditemukan',
            'data' => $data
        ], 201);
    }

    public function storeStatusPengajuan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer'
        ]);

        $statusPengajuan = $this->statusPengajuanService->createStatusPengajuan([
            'name' => $request->name,
            'key' => $request->key,
            'description' => $request->description,
            'sort_order' => $request->sort_order
        ]);

        return response()->json([
            'status'    => 201,
            'success'   => true,
            'message'   => 'Data status pengajuan berhasil disimpan',
            'data'      => $statusPengajuan
        ], 201);
    }

    public function updateStatusPengajuan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer'
        ]);

        $statusPengajuan = $this->statusPengajuanService->updateStatusPengajuan(
            $request->id,
            [
                'name' => $request->name,
                'key' => $request->key,
                'description' => $request->description,
                'sort_order' => $request->sort_order
            ]
        );

        if (!$statusPengajuan) {
            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Data status pengajuan tidak ditemukan',
                'data'      => null
            ], 201);
        }

        return response()->json([
            'status'    => 201,
            'success'   => true,
            'message'   => 'Data status pengajuan berhasil disimpan',
            'data'      => $statusPengajuan
        ], 201);
    }

    public function deleteStatusPengajuan(Request $request)
    {
        $deleted = $this->statusPengajuanService->deleteStatusPengajuan($request->id);

        if (!$deleted) {
            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Data status pengajuan tidak ditemukan',
            ], 201);
        }

        return response()->json([
            'status'    => 200,
            'success'   => true,
            'message'   => 'Data status pengajuan berhasil dihapus',
        ], 201);
    }
}
