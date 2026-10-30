<?php

namespace App\Services\Dashboard;

use App\Models\Pengajuan\Pengajuan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboarService
{
    public function getTotalPengajuan()
    {
        $totalpengajuan = Pengajuan::count();

        return $totalpengajuan;
    }

    public function getTotalPengajuanAktif()
    {
        $totalpengajuan = Pengajuan::where('status', 1)->count();

        return $totalpengajuan;
    }

    public function getTotalPengajuanSelesai()
    {
        $totalpengajuan = Pengajuan::where('status', 2)->count();

        return $totalpengajuan;
    }

    public function getTotalPengajuanDibatalkan()
    {
        $totalpengajuan = Pengajuan::where('status', 0)->count();

        return $totalpengajuan;
    }

    public function getPengajuanTerbaru()
    {
        $pengajuan = Pengajuan::with(['pegawai', 'unit', 'statuspengajuan', 'user'])->orderBy('created_at', 'desc')->take(5)->get();

        return $pengajuan;
    }

    public function getStatistikBulanan()
    {
        $startDate = Carbon::now()->startOfMonth()->subMonths(5);
        $endDate = Carbon::now()->endOfMonth();

        $monthlyData = Pengajuan::query()
            ->selectRaw('YEAR(created_at) as tahun, MONTH(created_at) as bulan, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->get()
            ->keyBy(fn($item) => $item->tahun . '-' . $item->bulan);

        $result = [];

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addMonth()) {
            $key = $date->year . '-' . $date->month;

            $result[] = [
                'bulan' => $date->translatedFormat('M'),
                'total' => (int) ($monthlyData->get($key)?->total ?? 0),
            ];
        }

        return $result;
    }

    public function getStatistikStatus()
    {
        return DB::table('statuspengajuan as sp')
            ->leftJoin('pengajuan as p', function ($join) {
                $join->on('p.statuspengajuan_id', '=', 'sp.id')
                    ->whereIn('p.status', [1, 2]);
            })
            ->select('sp.name as label')
            ->selectRaw('COUNT(p.id) as total')
            ->groupBy('sp.id', 'sp.name')
            ->orderBy('sp.id')
            ->get()
            ->map(fn($item) => [
                'label' => $item->label,
                'total' => (int) $item->total,
            ])
            ->values()
            ->all();
    }
}
