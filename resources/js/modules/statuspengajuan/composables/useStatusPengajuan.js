import { ref, computed, watch, onMounted } from 'vue';
import { useToast } from '../../../utilities/toast/useToast';
import { statuspengajuanService } from '../services/statuspengajuanService';

export function useStatusPengajuan() {
    const items = ref([]);
    const isLoading = ref(true);
    const isDeleting = ref(false);
    const searchQuery = ref('');

    // State Paginasi
    const currentPage = ref(1);
    const itemsPerPage = ref(5);

    // State Modal Form (Tambah / Edit)
    const isModalOpen = ref(false);
    const isEditing = ref(false);
    const isSubmitting = ref(false);
    const selectedId = ref(null);
    const form = ref({
        name: '',
        key: '',
        description: '',
        sort_order: 0
    });
    const errors = ref({
        name: '',
        key: '',
        description: '',
        sort_order: ''
    });

    // State Modal Hapus Reusable
    const isDeleteModalOpen = ref(false);
    const selectedDeleteItem = ref(null);

    const { toast } = useToast();

    // Fetch Data
    const fetchData = async () => {
        isLoading.value = true;
        try {
            const response = await statuspengajuanService.getStatusPengajuan();
            // Menyesuaikan jika response membungkus data dalam properti `data`
            items.value = response.data || response;
        } catch (error) {
            console.error('Gagal mengambil data status pengajuan:', error);
        } finally {
            isLoading.value = false;
        }
    };

    const refreshData = async () => {
        await fetchData();
    };

    // Filter & Paginasi
    const filteredItems = computed(() => {
        if (!searchQuery.value) return items.value;
        const q = searchQuery.value.toLowerCase();
        return items.value.filter(
            (item) =>
                (item.name && item.name.toLowerCase().includes(q))
        );
    });

    const totalPages = computed(() => Math.ceil(filteredItems.value.length / itemsPerPage.value) || 1);

    const paginatedItems = computed(() => {
        const start = (currentPage.value - 1) * itemsPerPage.value;
        const end = start + itemsPerPage.value;
        return filteredItems.value.slice(start, end);
    });

    watch(searchQuery, () => {
        currentPage.value = 1;
    });

    const setPage = (page) => {
        if (page < 1 || page > totalPages.value) return;
        currentPage.value = page;
    };

    // Modal Form Handlers
    const openAddModal = () => {
        isEditing.value = false;
        selectedId.value = null;
        form.value = { name: '', key: '', description: '', sort_order: 0 };
        clearErrors();
        isModalOpen.value = true;
    };

    const openEditModal = (item) => {
        isEditing.value = true;
        selectedId.value = item.id;
        form.value = {
            name: item.name,
            key: item.key,
            description: item.description,
            sort_order: item.sort_order
        };
        clearErrors();
        isModalOpen.value = true;
    };

    const closeModal = () => {
        isModalOpen.value = false;
    };

    const validateStatusPengajuan = () => {
        if (!form.value.name?.trim()) {
            errors.value.name = 'Nama status pengajuan wajib diisi.';
            return false;
        }

        if (!form.value.key?.trim()) {
            errors.value.key = 'Key status pengajuan wajib diisi.';
            return false;
        }

        if (!form.value.description?.trim()) {
            errors.value.description = 'Deskripsi status pengajuan wajib diisi.';
            return false;
        }

        if (
            form.value.sort_order === null ||
            form.value.sort_order === '' ||
            !Number.isInteger(Number(form.value.sort_order)) ||
            Number(form.value.sort_order) < 1
        ) {
            errors.value.sort_order = 'Urutan wajib diisi dengan angka bulat lebih dari 0.';
            return false;
        }

        errors.value.name = '';
        errors.value.key = '';
        errors.value.description = '';
        errors.value.sort_order = 0;

        return true;
    };

    const clearErrors = () => {
        errors.value = {
            name: '',
            key: '',
            description: '',
            sort_order: 0,
        };
    };

    const saveForm = async () => {
        // Validasi input kosong
        if (!validateStatusPengajuan()) {
            return;
        }

        isSubmitting.value = true;

        try {
            if (isEditing.value) {
                // Proses Update
                await statuspengajuanService.updateStatusPengajuan({
                    id: selectedId.value,
                    ...form.value
                });
                toast.success('Data status pengajuan berhasil diperbarui!');
            } else {
                // Proses Tambah (Store)
                await statuspengajuanService.storeStatusPengajuan(form.value);
                toast.success('Data status pengajuan berhasil ditambahkan!');
            }

            closeModal();
            await fetchData();
        } catch (error) {
            console.error('Gagal menyimpan data status pengajuan:', error);

            // Ambil pesan error dari response backend jika ada (misal validation Laravel 422)
            const errorMessage = error.response?.data?.message || 'Gagal menyimpan data status pengajuan.';
            toast.error(errorMessage);
        } finally {
            isSubmitting.value = false;
        }
    };

    // Modal Delete Handlers
    const openDeleteModal = (item) => {
        selectedDeleteItem.value = item;
        isDeleteModalOpen.value = true;
    };

    const closeDeleteModal = () => {
        if (isDeleting.value) return;
        isDeleteModalOpen.value = false;
        selectedDeleteItem.value = null;
    };

    const confirmDelete = async () => {
        if (!selectedDeleteItem.value) return;

        isDeleting.value = true;
        try {
            // Mengirim payload { id } sesuai kebutuhan endpoint /delete
            await statuspengajuanService.deleteStatusPengajuan({ id: selectedDeleteItem.value.id });

            // Tutup modal & bersihkan state
            isDeleteModalOpen.value = false;
            selectedDeleteItem.value = null;

            toast.success('Data status pengajuan berhasil dihapus!');

            // Refresh data tabel
            await fetchData();
        } catch (error) {
            console.error('Gagal menghapus data:', error);
        } finally {
            isDeleting.value = false;
        }
    };

    // Panggil fetch data pertama kali saat composable dipasang
    onMounted(() => {
        fetchData();
    });

    return {
        items,
        isLoading,
        isDeleting,
        searchQuery,
        currentPage,
        itemsPerPage,
        totalPages,
        paginatedItems,
        filteredItems,
        setPage,
        isModalOpen,
        isEditing,
        isSubmitting,
        validateStatusPengajuan,
        form,
        errors,
        openAddModal,
        openEditModal,
        closeModal,
        saveForm,
        fetchData,
        refreshData,
        isDeleteModalOpen,
        selectedDeleteItem,
        openDeleteModal,
        closeDeleteModal,
        confirmDelete
    };
}
