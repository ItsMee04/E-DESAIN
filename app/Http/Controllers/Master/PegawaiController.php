<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\Master\PegawaiService;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    protected PegawaiService $pegawaiService;

    public function __construct(PegawaiService $pegawaiService)
    {
        $this->pegawaiService = $pegawaiService;
    }

    public function getPegawai()
    {
        $data = $this->pegawaiService->getPegawaiAktif();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => 404,
                'success' => false,
                'message' => 'Data pegawai tidak ditemukan',
                'data' => $data
            ], 200);
        }

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Data pegawai berhasil ditemukan',
            'data' => $data
        ], 200);
    }

    public function storePegawai(Request $request)
    {
        $request->validate([
            'nip'               => 'required|string',
            'nama'              => 'required|string',
            'tempat'            => 'required|string',
            'tanggal'           => 'required|string',
            'jeniskelamin_id'   => 'required|exists:jeniskelamin,id',
            'alamat'            => 'required|string',
            'agama_id'          => 'required|exists:agama,id',
            'profesi_id'        => 'required|exists:profesi,id',
            'kontak'            => 'nullable|string|digits_between:10,13',
            'image'             => 'nullable|file|mimes:jpeg,png,jpg|max:1024'
        ]);

        $data = $this->pegawaiService->createPegawai([
            'nip'               => $request->nip,
            'nama'              => strtoupper($request->nama),
            'tempat'            => strtoupper($request->tempat),
            'tanggal'           => strtoupper($request->tanggal),
            'jeniskelamin_id'   => $request->jeniskelamin_id,
            'alamat'            => strtoupper($request->alamat),
            'agama_id'          => $request->agama_id,
            'profesi_id'        => $request->profesi_id,
            'kontak'            => $request->kontak,

        ], $request->file('image'));

        return response()->json([
            'status'    => 200,
            'success'   => true,
            'message'   => 'Pegawai berhasil ditambahkan',
            'data'      => $data
        ], 200);
    }

    public function updatePegawai(Request $request)
    {
        // 1. Ambil data hasil validasi langsung ke dalam variabel
        $validatedData = $request->validate([
            'id'                => 'required|integer|exists:pegawai,id', // Validasi id agar pasti aman
            'nip'               => 'required|string',
            'nama'              => 'required|string',
            'tempat'            => 'required|string',
            'tanggal'           => 'required|string',
            'jeniskelamin_id'   => 'required|exists:jeniskelamin,id',
            'alamat'            => 'required|string',
            'agama_id'          => 'required|exists:agama,id',
            'profesi_id'        => 'required|exists:profesi,id',
            'kontak'            => 'nullable|string|digits_between:10,13',
            'image'             => 'nullable|file|mimes:jpeg,png,jpg|max:1024'
        ]);

        // Ambil id dari data yang sudah tervalidasi
        $id = $validatedData['id'];

        try {
            // 2. Ambil file gambar (jika ada)
            $imageFile = $request->file('image');

            // 3. Kirim HANYA data yang tervalidasi ($validatedData), bukan $request->all()
            $pegawai = $this->pegawaiService->updatePegawai($id, $validatedData, $imageFile);

            if (!$pegawai) {
                return response()->json([
                    'status'    => '404',
                    'success'   => false,
                    'message'   => 'Data pegawai tidak ditemukan'
                ], 200); // Konsisten dengan format response Anda
            }

            return response()->json([
                'status'  => '200',
                'success' => true,
                'message' => 'Data pegawai berhasil diperbarui',
                'data'    => $pegawai
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deletePegawai(Request $request)
    {
        // 1. Validasi ID yang dikirim
        $validatedData = $request->validate([
            'id' => 'required|integer|exists:pegawai,id'
        ]);

        try {
            // 2. Eksekusi service delete
            $result = $this->pegawaiService->deletePegawai($validatedData['id']);

            if (!$result) {
                return response()->json([
                    'status'  => '404',
                    'success' => false,
                    'message' => 'Data pegawai tidak ditemukan'
                ], 200);
            }

            return response()->json([
                'status'  => '200',
                'success' => true,
                'message' => 'Pegawai dan akun pengguna berhasil dinonaktifkan'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
}
