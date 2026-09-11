import { ref, computed, watch, onMounted } from 'vue';
import { useToast } from '../../../utilities/toast/useToast';
import { jeniskelaminService } from '../services/jeniskelaminService';

export function useJenisKelamin() {
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
        jeniskelamin: '',
    });
    const errors = ref({
        jeniskelamin: ''
    });

    // State Modal Hapus Reusable
    const isDeleteModalOpen = ref(false);
    const selectedDeleteItem = ref(null);

    const { toast } = useToast();

    // Fetch Data
    const fetchData = async () => {
        isLoading.value = true;
        try {
            const response = await jeniskelaminService.getJenisKelamin();
            // Menyesuaikan jika response membungkus data dalam properti `data`
            items.value = response.data || response;
        } catch (error) {
            console.error('Gagal mengambil data jenis kelamin:', error);
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
                (item.jeniskelamin && item.jeniskelamin.toLowerCase().includes(q))
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
        form.value = { jeniskelamin: '' };
        clearErrors();
        isModalOpen.value = true;
    };

    const openEditModal = (item) => {
        isEditing.value = true;
        selectedId.value = item.id;
        form.value = {
            jeniskelamin: item.jeniskelamin
        };
        clearErrors();
        isModalOpen.value = true;
    };

    const closeModal = () => {
        isModalOpen.value = false;
    };

    const validateJenisKelamin = () => {
        if (!form.value.jeniskelamin?.trim()) {
            errors.value.jeniskelamin = 'Nama jenis kelamin wajib diisi.';
            return false;
        }

        errors.value.jeniskelamin = '';

        return true;
    };

    const clearErrors = () => {
        errors.value = {
            jeniskelamin: ''
        };
    };

    const saveForm = async () => {
        // Validasi input kosong
        if (!validateJenisKelamin()) {
            return;
        }

        isSubmitting.value = true;

        try {
            if (isEditing.value) {
                // Proses Update
                await jeniskelaminService.updateJenisKelamin({
                    id: selectedId.value,
                    ...form.value
                });
                toast.success('Data jenis kelamin berhasil diperbarui!');
            } else {
                // Proses Tambah (Store)
                await jeniskelaminService.storeJenisKelamin(form.value);
                toast.success('Data jenis kelamin berhasil ditambahkan!');
            }

            closeModal();
            await fetchData();
        } catch (error) {
            console.error('Gagal menyimpan data jenis kelamin:', error);

            // Ambil pesan error dari response backend jika ada (misal validation Laravel 422)
            const errorMessage = error.response?.data?.message || 'Gagal menyimpan data jenis kelamin.';
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
            await jeniskelaminService.deleteJenisKelamin({ id: selectedDeleteItem.value.id });

            // Tutup modal & bersihkan state
            isDeleteModalOpen.value = false;
            selectedDeleteItem.value = null;

            toast.success('Data jenis kelamin berhasil dihapus!');

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
        validateJenisKelamin,
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
