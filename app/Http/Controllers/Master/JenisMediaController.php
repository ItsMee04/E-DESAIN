<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\Master\JenisMediaService;
use Illuminate\Http\Request;

class JenisMediaController extends Controller
{
    protected JenisMediaService $jenisMediaService;

    public function __construct(JenisMediaService $jenisMediaService)
    {
        $this->jenisMediaService = $jenisMediaService;
    }

    public function getJenisMedia()
    {
        $data = $this->jenisMediaService->getJenisMedia();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => 404,
                'success' => false,
                'message' => 'Data jenis media tidak ditemukan',
                'data' => $data
            ], 201);
        }

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Data jenis media berhasil ditemukan',
            'data' => $data
        ], 201);
    }

    public function storeJenisMedia(Request $request)
    {
        $request->validate([
            'jenismedia' => 'required|string|max:255',
        ]);

        $jenismedia = $this->jenisMediaService->createJenisMedia([
            'jenismedia'   => $request->jenismedia,
        ]);

        return response()->json([
            'status'    => 201,
            'success'   => true,
            'message'   => 'Data jenis media berhasil disimpan',
            'data'      => $jenismedia
        ], 201);
    }

    public function updateJenisMedia(Request $request)
    {
        $request->validate([
            'jenismedia' => 'required|string|max:255',
        ]);

        $jenismedia = $this->jenisMediaService->updateJenisMedia(
            $request->id,
            [
                'jenismedia' => $request->jenismedia
            ]
        );

        if (!$jenismedia) {
            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Data jenis media tidak ditemukan',
                'data'      => null
            ], 201);
        }

        return response()->json([
            'status'    => 201,
            'success'   => true,
            'message'   => 'Data jenis media berhasil disimpan',
            'data'      => $jenismedia
        ], 201);
    }

    public function deleteJenisMedia(Request $request)
    {
        $deleted = $this->jenisMediaService->deleteJenisMedia($request->id);

        if (!$deleted) {
            return response()->json([
                'status'    => 404,
                'success'   => false,
                'message'   => 'Data jenis media tidak ditemukan',
            ], 201);
        }

        return response()->json([
            'status'    => 200,
            'success'   => true,
            'message'   => 'Data jenis media berhasil dihapus',
        ], 201);
    }
}
