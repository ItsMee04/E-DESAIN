<?php

namespace App\Http\Controllers\Laporan;

use App\Exports\LaporanExport;
use App\Http\Controllers\Controller;
use App\Services\Laporan\LaporanService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class LaporanController extends Controller
{
    protected LaporanService $laporanService;

    public function __construct(LaporanService $laporanService)
    {
        $this->laporanService = $laporanService;
    }

    /**
     * Mengambil data laporan dan statistik.
     */
    public function index(Request $request)
    {
        try {
            $filters = $request->only([
                'tanggal_mulai',
                'tanggal_akhir',
                'status_id',
            ]);

            $laporan = $this->laporanService->getLaporan($filters);

            $statistik = $this->laporanService->getStatistik($filters);

            return response()->json([
                'status' => 200,
                'success' => true,
                'message' => 'Laporan pengajuan berhasil ditemukan.',
                'data' => [
                    'laporan' => $laporan,
                    'statistik' => $statistik,
                ],
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 422,
                'success' => false,
                'message' => 'Filter laporan tidak valid.',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'status' => 500,
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil laporan pengajuan.',
            ], 500);
        }
    }

    /**
     * Mengunduh laporan pengajuan dalam format Excel.
     */
    public function exportExcel(Request $request)
    {
        try {
            $filters = $request->only([
                'tanggal_mulai',
                'tanggal_akhir',
                'status_id',
            ]);

            $laporan = $this->laporanService->getLaporanExport($filters);

            $namaFile = 'laporan-pengajuan-'
                . ($filters['tanggal_mulai'] ?? 'semua')
                . '-'
                . ($filters['tanggal_akhir'] ?? 'semua')
                . '.xlsx';

            return Excel::download(
                new LaporanExport($laporan),
                $namaFile
            );
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 422,
                'success' => false,
                'message' => 'Filter laporan tidak valid.',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'status' => 500,
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengekspor laporan.',
            ], 500);
        }
    }


    public function exportPdf(Request $request)
    {
        try {
            $filters = $request->only([
                'tanggal_mulai',
                'tanggal_akhir',
                'status_id',
            ]);

            $laporan = $this->laporanService->getLaporanExport($filters);


            $pdf = Pdf::loadView('laporan.pdf', [
                'laporan' => $laporan,
                'filters' => $filters,
                'tanggalCetak' => now(),
            ])->setPaper('a4', 'landscape');

            $pdf->render();

            $pdf->getCanvas()->page_text(
                750,
                570,
                'Halaman {PAGE_NUM} dari {PAGE_COUNT}',
                null,
                8,
                [107, 114, 128]
            );


            $namaFile = 'laporan-pengajuan-'
                . ($filters['tanggal_mulai'] ?? 'semua')
                . '-'
                . ($filters['tanggal_akhir'] ?? 'semua')
                . '.pdf';

            return $pdf->download($namaFile);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 422,
                'success' => false,
                'message' => 'Filter laporan tidak valid.',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'status' => 500,
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengekspor laporan PDF.',
            ], 500);
        }
    }
}
