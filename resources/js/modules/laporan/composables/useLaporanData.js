
import { ref } from 'vue'
import { laporanService } from '../services/laporanService.js'

export const useLaporanData = () => {
    const laporan = ref([])

    const statistik = ref({
        totalPengajuan: 0,
        dalamProses: 0,
        selesai: 0,
        ditolakDibatalkan: 0,
    })

    const isLoading = ref(false)
    const hasLoaded = ref(false)
    const filtersAktif = ref({})

    const getLaporan = async (filters = {}) => {
        isLoading.value = true
        filtersAktif.value = { ...filters }

        try {
            const response = await laporanService.getFilteredData(filters)
            const result = response.data

            laporan.value = result?.laporan ?? []

            statistik.value = {
                totalPengajuan:
                    result?.statistik?.total_pengajuan ?? 0,
                dalamProses:
                    result?.statistik?.dalam_proses ?? 0,
                selesai:
                    result?.statistik?.selesai ?? 0,
                ditolakDibatalkan:
                    result?.statistik?.ditolak_dibatalkan ?? 0,
            }

            hasLoaded.value = true
        } finally {
            isLoading.value = false
        }
    }

    const resetLaporan = () => {
        laporan.value = []
        filtersAktif.value = {}

        statistik.value = {
            totalPengajuan: 0,
            dalamProses: 0,
            selesai: 0,
            ditolakDibatalkan: 0,
        }

        hasLoaded.value = false
    }

    const exportExcel = async () => {
        try {
            const filters = { ...filtersAktif.value }

            const response = await laporanService.exportExcel(filters)

            const url = window.URL.createObjectURL(response.data)
            const link = document.createElement('a')

            link.href = url
            link.download = `laporan-pengajuan-${filters.tanggal_mulai ?? 'semua'}-${filters.tanggal_akhir ?? 'semua'}.xlsx`

            document.body.appendChild(link)
            link.click()
            link.remove()

            window.URL.revokeObjectURL(url)
        } catch (error) {
            console.error('Gagal export Excel:', error)
            toast.error('Gagal mengekspor laporan ke Excel.')
        }
    }

    const exportPdf = async () => {
        try {
            const filters = { ...filtersAktif.value }

            const response = await laporanService.exportPdf(filters);

            const blob = new Blob([response.data], {
                type: 'application/pdf',
            });

            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');

            link.href = url;
            link.download = 'laporan-pengajuan.pdf';

            document.body.appendChild(link);
            link.click();
            link.remove();

            window.URL.revokeObjectURL(url);
        } catch (error) {
            console.error('Gagal export PDF:', error)
            toast.error('Gagal mengekspor laporan ke PDF.')
        }
    }

    return {
        laporan,
        statistik,
        isLoading,
        hasLoaded,
        getLaporan,
        resetLaporan,
        exportExcel,
        exportPdf,
        filtersAktif,
    }
}
