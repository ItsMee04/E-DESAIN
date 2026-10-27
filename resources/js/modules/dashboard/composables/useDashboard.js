import { ref, onMounted } from 'vue';
import { useToast } from '../../../utilities/toast/useToast';
import { dashboardService } from '../services/dashboardService';

export function useDashboard() {
    const { toast } = useToast();

    // State data dashboard
    const dashboard = ref({
        total_pengajuan: 0,
        total_pengajuan_aktif: 0,
        total_pengajuan_selesai: 0,
    });

    const isLoading = ref(false);

    // Mengambil data dashboard dari API
    const fetchData = async () => {
        isLoading.value = true;

        try {
            const response = await dashboardService.getDataDashboard();

            // Response API menggunakan format { status, success, message, data }
            dashboard.value = response?.data ?? {
                total_pengajuan: 0,
                total_pengajuan_aktif: 0,
                total_pengajuan_selesai: 0,
            };
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

    // Memuat data saat composable digunakan
    onMounted(() => {
        fetchData();
    });

    return {
        dashboard,
        isLoading,
        fetchData,
    };
}
