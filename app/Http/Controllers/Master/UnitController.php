<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\Master\UnitService;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    protected UnitService $unitService;

    public function __construct(UnitService $unitService)
    {
        $this->unitService = $unitService;
    }

    public function getUnit()
    {
        $data = $this->unitService->getUnit();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => 404,
                'success' => false,
                'message' => 'Data unit tidak ditemukan',
                'data' => $data
            ], 201);
        }

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Data unit berhasil ditemukan',
            'data' => $data
        ], 201);
    }

    public function storeUnit(Request $request)
    {
        $request->validate([
            'unit' => 'required|string|max:255',
        ]);

        $unit = $this->unitService->createUnit([
            'unit'   => $request->unit,
        ]);

        return response()->json([
            'status'    => 201,
            'success'   => true,
            'message'   => 'Data unit berhasil disimpan',
            'data'      => $unit
        ], 201);
    }

    public function updateUnit(Request $request)
    {
        $request->validate([
            'unit' => 'required|string|max:255',
        ]);

        $unit = $this->unitService->updateUnit(
            $request->id,
            [
                'unit' => $request->unit
            ]
        );

        if (!$unit) {
            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Data unit tidak ditemukan',
                'data'      => null
            ], 201);
        }

        return response()->json([
            'status'    => 201,
            'success'   => true,
            'message'   => 'Data unit berhasil disimpan',
            'data'      => $unit
        ], 201);
    }

    public function deleteUnit(Request $request)
    {
        $deleted = $this->unitService->deleteUnit($request->id);

        if (!$deleted) {
            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Data unit tidak ditemukan',
            ], 201);
        }

        return response()->json([
            'status'    => 200,
            'success'   => true,
            'message'   => 'Data unit berhasil dihapus',
        ], 201);
    }
}
