<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\Master\AgamaService;
use Illuminate\Http\Request;

class AgamaController extends Controller
{
    protected AgamaService $agamaService;

    public function __construct(AgamaService $agamaService)
    {
        $this->agamaService = $agamaService;
    }

    public function getAgama()
    {
        $data = $this->agamaService->getAgama();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => 404,
                'success' => false,
                'message' => 'Data agama tidak ditemukan',
                'data' => $data
            ], 201);
        }

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Data agama berhasil ditemukan',
            'data' => $data
        ], 201);
    }

    public function storeAgama(Request $request)
    {
        $request->validate([
            'agama' => 'required|string|max:255',
        ]);

        $agama = $this->agamaService->createAgama([
            'agama'   => $request->agama,
        ]);

        return response()->json([
            'status'    => 201,
            'success'   => true,
            'message'   => 'Data agama berhasil disimpan',
            'data'      => $agama
        ], 201);
    }

    public function updateAgama(Request $request)
    {
        $request->validate([
            'agama' => 'required|string|max:255',
        ]);

        $agama = $this->agamaService->updateAgama(
            $request->id,
            [
                'agama' => $request->agama
            ]
        );

        if (!$agama) {
            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Data agama tidak ditemukan',
                'data'      => null
            ], 201);
        }

        return response()->json([
            'status'    => 201,
            'success'   => true,
            'message'   => 'Data agama berhasil disimpan',
            'data'      => $agama
        ], 201);
    }

    public function deleteAgama(Request $request)
    {
        $deleted = $this->agamaService->deleteAgama($request->id);

        if (!$deleted) {
            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Data agama tidak ditemukan',
            ], 201);
        }

        return response()->json([
            'status'    => 200,
            'success'   => true,
            'message'   => 'Data agama berhasil dihapus',
        ], 201);
    }
}
