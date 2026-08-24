<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\Master\ProfesiService;
use Illuminate\Http\Request;

class ProfesiController extends Controller
{
    protected ProfesiService $profesiService;

    public function __construct(ProfesiService $profesiService)
    {
        $this->profesiService = $profesiService;
    }

    public function getProfesi()
    {
        $data = $this->profesiService->getProfesi();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => 404,
                'success' => false,
                'message' => 'Data profesi tidak ditemukan',
                'data' => $data
            ], 201);
        }

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Data profesi berhasil ditemukan',
            'data' => $data
        ], 201);
    }

    public function storeProfesi(Request $request)
    {
        $request->validate([
            'profesi' => 'required|string|max:255',
        ]);

        $profesi = $this->profesiService->createProfesi([
            'profesi'   => $request->profesi,
        ]);

        return response()->json([
            'status'    => 201,
            'success'   => true,
            'message'   => 'Data profesi berhasil disimpan',
            'data'      => $profesi
        ], 201);
    }

    public function updateProfesi(Request $request)
    {
        $request->validate([
            'profesi' => 'required|string|max:255',
        ]);

        $profesi = $this->profesiService->updateProfesi(
            $request->id,
            [
                'profesi' => $request->profesi
            ]
        );

        if (!$profesi) {
            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Data profesi tidak ditemukan',
                'data'      => null
            ], 201);
        }

        return response()->json([
            'status'    => 201,
            'success'   => true,
            'message'   => 'Data profesi berhasil disimpan',
            'data'      => $profesi
        ], 201);
    }

    public function deleteProfesi(Request $request)
    {
        $deleted = $this->profesiService->deleteProfesi($request->id);

        if (!$deleted) {
            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Data profesi tidak ditemukan',
            ], 201);
        }

        return response()->json([
            'status'    => 200,
            'success'   => true,
            'message'   => 'Data profesi berhasil dihapus',
        ], 201);
    }
}
