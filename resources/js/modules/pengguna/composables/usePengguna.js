import { ref, computed, watch, onMounted } from 'vue';
import { useToast } from '../../../utilities/toast/useToast';
import { penggunaService } from '../services/penggunaService';

export function usePengguna() {
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
        pegawai: '',
        username: '',
        email: '',
        password: '',
    });
    const errors = ref({
        pegawai: '',
        username: '',
        email: '',
        password: ''
    });

    // State Modal Hapus Reusable
    const isDeleteModalOpen = ref(false);
    const selectedDeleteItem = ref(null);

    const { toast } = useToast();

    // Fetch Data
    const fetchData = async () => {
        isLoading.value = true;
        try {
            const response = await penggunaService.getPengguna();
            // Menyesuaikan jika response membungkus data dalam properti `data`
            items.value = response.data || response;
        } catch (error) {
            console.error('Gagal mengambil data pengguna:', error);
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
                (item.username && item.username.toLowerCase().includes(q))
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
        form.value = { username: '', email: '', password: '', pegawai: '' };
        clearErrors();
        isModalOpen.value = true;
    };

    const openEditModal = (item) => {
        isEditing.value = true;
        selectedId.value = item.id;
        form.value = {
            username: item.username,
            email: item.email,
            password: item.password,
            pegawai: item.pegawai?.nama
        };
        clearErrors();
        isModalOpen.value = true;
    };

    const closeModal = () => {
        isModalOpen.value = false;
    };

    const validatePengguna = (field = null) => {
        let isValid = true;

        // Nama
        if (!field || field === 'email') {
            if (!form.value.email?.trim()) {
                errors.value.email = 'Email wajib diisi.';
                isValid = false;
            } else {
                errors.value.email = '';
            }
        }

        // Tempat
        if (!field || field === 'username') {
            if (!form.value.username?.trim()) {
                errors.value.username = 'Username wajib diisi.';
                isValid = false;
            } else {
                errors.value.username = '';
            }
        }

        // Tanggal
        if (!field || field === 'password') {
            if (!form.value.password?.trim()) {
                errors.value.password = 'Password wajib diisi.';
                isValid = false;
            } else {
                errors.value.password = '';
            }
        }

        return isValid;
    };

    const clearErrors = () => {
        errors.value = {
            username: '',
            email: '',
            password: '',
            pegawai: ''
        };
    };

    const saveForm = async () => {
        // Validasi input kosong
        if (!validatePengguna()) {
            return;
        }

        isSubmitting.value = true;

        try {
            if (isEditing.value) {
                // Proses Update
                await penggunaService.updatePengguna({
                    id: selectedId.value,
                    ...form.value
                });
                toast.success('Data pengguna berhasil diperbarui!');
            } else {
                // Proses Tambah (Store)
                await penggunaService.storePengguna(form.value);
                toast.success('Data pengguna berhasil ditambahkan!');
            }

            closeModal();
            await fetchData();
        } catch (error) {
            console.error('Gagal menyimpan data pengguna:', error);

            // Ambil pesan error dari response backend jika ada (misal validation Laravel 422)
            const errorMessage = error.response?.data?.message || 'Gagal menyimpan data pengguna.';
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
            await penggunaService.deletePengguna({ id: selectedDeleteItem.value.id });

            // Tutup modal & bersihkan state
            isDeleteModalOpen.value = false;
            selectedDeleteItem.value = null;

            toast.success('Data pengguna berhasil dihapus!');

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
        validatePengguna,
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
