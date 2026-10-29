import { ref, computed, onMounted } from 'vue';
import { useToast } from '../../../utilities/toast/useToast';
import { dashboardService } from '../services/dashboardService';

export function useDashboard() {
    const { toast } = useToast();

    // Statistik kartu dashboard
    const dashboard = ref({
        total_pengajuan: 0,
        total_pengajuan_aktif: 0,
        total_pengajuan_selesai: 0,
        total_pengajuan_dibatalkan: 0,
    });

    // Data pengajuan terbaru
    const recentRequests = ref([]);

    // Data grafik dashboard
    const monthlyData = ref([]);
    const statusData = ref([]);

    // Loading state
    const isLoading = ref(false);
    const isLoadingRequests = ref(false);
    const isLoadingStatistics = ref(false);

    // Mengambil statistik kartu dashboard
    const fetchData = async () => {
        isLoading.value = true;

        try {
            const response = await dashboardService.getDataDashboard();
            dashboard.value = response?.data ?? dashboard.value;
        } catch (error) {
            console.error('Gagal mengambil data dashboard:', error);

            toast.error(
                error.response?.data?.message ||
                'Gagal mengambil data dashboard.'
            );
        } finally {
            isLoading.value = false;
        }
    };

    // Mengambil lima pengajuan terbaru
    const fetchRecentRequests = async () => {
        isLoadingRequests.value = true;

        try {
            const response = await dashboardService.getPengajuanTerbaru();

            recentRequests.value = Array.isArray(response?.data)
                ? response.data
                : [];
        } catch (error) {
            console.error('Gagal mengambil pengajuan terbaru:', error);

            toast.error(
                error.response?.data?.message ||
                'Gagal mengambil pengajuan terbaru.'
            );
        } finally {
            isLoadingRequests.value = false;
        }
    };

    // Mengambil statistik grafik bulanan dan donat
    const fetchStatistikDashboard = async () => {
        isLoadingStatistics.value = true;

        const results = await Promise.allSettled([
            dashboardService.getStatistikBulanan(),
            dashboardService.getStatistikStatus(),
        ]);

        const [bulanan, status] = results;

        if (bulanan.status === 'fulfilled') {
            monthlyData.value = Array.isArray(bulanan.value?.data)
                ? bulanan.value.data
                : [];
        } else {
            monthlyData.value = [];
            console.error(
                'Gagal mengambil statistik bulanan:',
                bulanan.reason
            );
        }

        if (status.status === 'fulfilled') {
            statusData.value = Array.isArray(status.value?.data)
                ? status.value.data
                : [];
        } else {
            statusData.value = [];
            console.error(
                'Gagal mengambil statistik status:',
                status.reason
            );
        }

        if (
            bulanan.status === 'rejected' ||
            status.status === 'rejected'
        ) {
            toast.error('Sebagian data grafik dashboard gagal dimuat.');
        }

        isLoadingStatistics.value = false;
    };

    // Menyesuaikan struktur API dengan kebutuhan tabel
    const requests = computed(() =>
        recentRequests.value.slice(0, 5).map((item) => ({
            ...item,
            unit: item.unit?.unit ?? '-',
            status: item.statuspengajuan?.name ?? '-',
            status_key: item.statuspengajuan?.key ?? '',
        }))
    );

    // Muat seluruh data saat composable digunakan
    onMounted(() => {
        fetchData();
        fetchRecentRequests();
        fetchStatistikDashboard();
    });

    return {
        dashboard,
        isLoading,
        fetchData,

        recentRequests,
        requests,
        isLoadingRequests,
        fetchRecentRequests,

        monthlyData,
        statusData,
        isLoadingStatistics,
        fetchStatistikDashboard,
    };
}
