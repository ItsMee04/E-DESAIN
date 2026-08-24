<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\Master\JenisKelaminService;
use Illuminate\Http\Request;

class JenisKelaminController extends Controller
{
    protected JenisKelaminService $jeniskelaminService;

    public function __construct(JenisKelaminService $jeniskelaminService)
    {
        $this->jeniskelaminService = $jeniskelaminService;
    }

    public function getJenisKelamin()
    {
        $data = $this->jeniskelaminService->getJenisKelamin();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => 404,
                'success' => false,
                'message' => 'Data jenis kelamin tidak ditemukan',
                'data' => $data
            ], 201);
        }

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Data jenis kelamin berhasil ditemukan',
            'data' => $data
        ], 201);
    }

    public function storeJenisKelamin(Request $request)
    {
        $request->validate([
            'jeniskelamin' => 'required|string|max:255',
        ]);

        $data = $this->jeniskelaminService->createJenisKelamin([
            'jeniskelamin'   => $request->jeniskelamin,
        ]);

        return response()->json([
            'status'    => 201,
            'success'   => true,
            'message'   => 'Data jenis kelamin berhasil disimpan',
            'data'      => $data
        ], 201);
    }

    public function updateJenisKelamin(Request $request)
    {
        $request->validate([
            'jeniskelamin' => 'required|string|max:255',
        ]);

        $data = $this->jeniskelaminService->updateJenisKelamin(
            $request->id,
            [
                'jeniskelamin' => $request->jeniskelamin
            ]
        );

        if (!$data) {
            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Data jenis kelamin tidak ditemukan',
                'data'      => null
            ], 201);
        }

        return response()->json([
            'status'    => 201,
            'success'   => true,
            'message'   => 'Data jenis kelamin berhasil disimpan',
            'data'      => $data
        ], 201);
    }

    public function deleteJenisKelamin(Request $request)
    {
        $deleted = $this->jeniskelaminService->deleteJenisKelamin($request->id);

        if (!$deleted) {
            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Data jenis kelamin tidak ditemukan',
            ], 201);
        }

        return response()->json([
            'status'    => 200,
            'success'   => true,
            'message'   => 'Data jenis kelamin berhasil dihapus',
        ], 201);
    }
}
