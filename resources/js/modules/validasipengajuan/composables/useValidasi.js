import { ref, computed, watch, onMounted } from "vue";
import { useToast } from "../../../utilities/toast/useToast";
import { pengajuanValidasiService } from "../services/validasiService";

export function useValidasi() {
    const items = ref([]);
    const isLoading = ref(true);
    const isSubmitting = ref(false);

    // Search & Filter
    const searchQuery = ref("");
    const statusFilter = ref("");

    // State Paginasi
    const currentPage = ref(1);
    const itemsPerPage = ref(10);

    // State Modal Detail
    const isModalOpen = ref(false);
    const selectedItem = ref(null);

    // Form Validasi
    const form = ref({
        status: "",
        catatan: "",
    });

    const errors = ref({
        status: "",
        catatan: "",
    });

    const { toast } = useToast();

    // =========================
    // Fetch Data
    // =========================

    const fetchData = async () => {
        isLoading.value = true;

        try {
            const response =
                await pengajuanValidasiService.getPengajuanValidasi();

            items.value = response.data || response;
        } catch (error) {
            console.error("Gagal mengambil data pengajuan validasi:", error);
        } finally {
            isLoading.value = false;
        }
    };

    const refreshData = async () => {
        await fetchData();
    };

    // =========================
    // Filter & Paginasi
    // =========================

    const filteredItems = computed(() => {
        const q = searchQuery.value.toLowerCase().trim();

        return items.value.filter((item) => {
            const matchesSearch =
                !q ||
                item.nomor?.toLowerCase().includes(q) ||
                item.nama_desain?.toLowerCase().includes(q) ||
                item.pegawai?.nama?.toLowerCase().includes(q) ||
                item.unit?.unit?.toLowerCase().includes(q);

            const matchesStatus =
                !statusFilter.value ||
                item.statuspengajuan?.key === statusFilter.value;

            return matchesSearch && matchesStatus;
        });
    });

    const totalPages = computed(
        () => Math.ceil(filteredItems.value.length / itemsPerPage.value) || 1,
    );

    const paginatedItems = computed(() => {
        const start = (currentPage.value - 1) * itemsPerPage.value;
        const end = start + itemsPerPage.value;
        return filteredItems.value.slice(start, end);
    });

    watch([searchQuery, statusFilter], () => {
        currentPage.value = 1;
    });

    const setPage = (page) => {
        if (page < 1 || page > totalPages.value) {
            return;
        }

        currentPage.value = page;
    };

    // =========================
    // Modal Detail
    // =========================

    const openDetailModal = async (item) => {
        isLoading.value = true;

        try {
            const response =
                await pengajuanValidasiService.getDetailPengajuanValidasi(
                    item.id,
                );

            selectedItem.value = response.data || response;

            form.value = {
                status: "",
                catatan: "",
            };

            clearErrors();

            isModalOpen.value = true;
        } catch (error) {
            console.error("Gagal mengambil detail pengajuan:", error);

            const errorMessage =
                error.response?.data?.message ||
                "Gagal mengambil detail pengajuan.";

            toast.error(errorMessage);
        } finally {
            isLoading.value = false;
        }
    };

    const closeModal = () => {
        if (isSubmitting.value) {
            return;
        }

        isModalOpen.value = false;
        selectedItem.value = null;

        form.value = {
            status: "",
            catatan: "",
        };

        clearErrors();
    };

    // =========================
    // Validasi Form
    // =========================

    const validateForm = () => {
        clearErrors();

        let valid = true;

        if (!form.value.status) {
            errors.value.status = "Keputusan validasi wajib dipilih.";

            valid = false;
        }

        if (form.value.status === "rejected" && !form.value.catatan?.trim()) {
            errors.value.catatan =
                "Catatan wajib diisi jika pengajuan tidak disetujui.";

            valid = false;
        }

        return valid;
    };

    const clearErrors = () => {
        errors.value = {
            status: "",
            catatan: "",
        };
    };

    // =========================
    // Proses Validasi
    // =========================

    const validasiPengajuan = async () => {
        if (!selectedItem.value) {
            return;
        }

        if (!validateForm()) {
            return;
        }

        isSubmitting.value = true;

        try {
            await pengajuanValidasiService.validasiPengajuan(
                selectedItem.value.id,
                {
                    status: form.value.status,
                    catatan: form.value.catatan,
                },
            );

            if (form.value.status === "approved") {
                toast.success("Pengajuan berhasil disetujui!");
            } else {
                toast.success("Pengajuan berhasil ditolak!");
            }

            closeModal();

            await fetchData();
        } catch (error) {
            console.error("Gagal memproses validasi pengajuan:", error);

            const errorMessage =
                error.response?.data?.message ||
                "Gagal memproses validasi pengajuan.";

            toast.error(errorMessage);
        } finally {
            isSubmitting.value = false;
        }
    };

    // =========================
    // Panggil Fetch Data
    // =========================

    onMounted(() => {
        fetchData();
    });

    return {
        // Data
        items,
        isLoading,
        isSubmitting,

        // Search & Filter
        searchQuery,
        statusFilter,

        // Pagination
        currentPage,
        itemsPerPage,
        totalPages,
        paginatedItems,
        filteredItems,
        setPage,

        // Modal Detail
        isModalOpen,
        selectedItem,
        openDetailModal,
        closeModal,

        // Form Validasi
        form,
        errors,
        validateForm,
        clearErrors,
        validasiPengajuan,

        // Data
        fetchData,
        refreshData,
    };
}
