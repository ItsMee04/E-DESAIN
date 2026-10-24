import { ref, computed, watch, onMounted } from 'vue';
import { useToast } from '../../../utilities/toast/useToast';

// Service API
import { pegawaiService } from '../services/pegawaiService';
import { profesiService } from '../../profesi/services/profesiService';
import { unitService } from '../../unit/services/unitService';

export function usePegawai() {
    const items = ref([]);
    const isLoading = ref(true);
    const isDeleting = ref(false);
    const searchQuery = ref('');

    const profesiOptions = ref([]);
    const unitOptions = ref([]);

    // State Paginasi
    const currentPage = ref(1);
    const itemsPerPage = ref(25);

    // State Modal Form (Tambah / Edit)
    const isModalOpen = ref(false);
    const isEditing = ref(false);
    const isSubmitting = ref(false);
    const selectedId = ref(null);
    const form = ref({
        nip: '',
        nama: '',
        unit_id: null,
        profesi_id: null,
        kontak: '',
        image: null
    });
    const errors = ref({
        nama: '',
        nip: '',
        unit_id: null,
        profesi_id: null,
        kontak: '',
        image: null
    });
    const createEmptyForm = () => ({
        nip: '',
        unit_id: null,
        profesi_id: null,
        kontak: '',
        image: null
    });

    // State Modal Hapus Reusable
    const isDeleteModalOpen = ref(false);
    const selectedDeleteItem = ref(null);
    const imagePreview = ref('');

    const { toast } = useToast();

    // Fetch Data
    const fetchData = async () => {
        isLoading.value = true;
        try {
            const response = await pegawaiService.getPegawai();
            // Menyesuaikan jika response membungkus data dalam properti `data`
            items.value = response.data || response;
        } catch (error) {
            console.error('Gagal mengambil data pegawai:', error);
        } finally {
            isLoading.value = false;
        }
    };

    const fetchUnitOptions = async () => {
        try {
            const response = await unitService.getUnit();
            unitOptions.value = response.data || response;
        } catch (error) {
            console.error('Gagal mengambil data unit:', error);
        }
    };

    const fetchProfesiOptions = async () => {
        try {
            const response = await profesiService.getProfesi();
            profesiOptions.value = response.data || response;
        } catch (error) {
            console.error('Gagal mengambil data profesi:', error);
        }
    };

    const refreshData = async () => {
        await fetchData();
    };

    const handleImageChange = (file) => {
        if (!file) {
            return;
        }

        // Simpan file ke form
        form.value.image = file;

        // Hapus preview sebelumnya jika berupa blob URL
        if (imagePreview.value?.startsWith('blob:')) {
            URL.revokeObjectURL(imagePreview.value);
        }

        // Buat preview gambar
        imagePreview.value = URL.createObjectURL(file);
    };

    // Filter & Paginasi
    const filteredItems = computed(() => {
        if (!searchQuery.value) return items.value;
        const q = searchQuery.value.toLowerCase();
        return items.value.filter(
            (item) =>
                (item.nama && item.nama.toLowerCase().includes(q)) ||
                (item.nip && item.nip.toLowerCase().includes(q))
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
    const openAddModal = async () => {
        isEditing.value = false;
        selectedId.value = null;

        form.value = createEmptyForm();

        imagePreview.value = '';

        clearErrors();

        await Promise.all([
            fetchUnitOptions(),
            fetchProfesiOptions(),
        ]);

        isModalOpen.value = true;
    };

    const openEditModal = async (item) => {
        isEditing.value = true;
        selectedId.value = item.id;

        form.value = {
            nip: item.nip ?? '',
            nama: item.nama ?? '',
            unit_id: item.unit_id ?? null,
            profesi_id: item.profesi_id ?? null,
            kontak: item.kontak ?? '',
            image: null
        };

        // Tampilkan foto lama
        imagePreview.value = item.image
            ? `/storage/pegawai/image/${item.image}`
            : '';

        clearErrors();

        await Promise.all([
            fetchUnitOptions(),
            fetchProfesiOptions(),
        ]);

        isModalOpen.value = true;
    };

    const closeModal = () => {
        isModalOpen.value = false;
        clearErrors();
    };

    const validatePegawai = (field = null) => {
        let isValid = true;

        // NIP
        if (!field || field === 'nip') {
            if (!form.value.nip?.trim()) {
                errors.value.nip = 'NIP wajib diisi.';
                isValid = false;
            } else {
                errors.value.nip = '';
            }
        }

        // Nama
        if (!field || field === 'nama') {
            if (!form.value.nama?.trim()) {
                errors.value.nama = 'Nama pegawai wajib diisi.';
                isValid = false;
            } else {
                errors.value.nama = '';
            }
        }

        // Unit
        if (!field || field === 'unit_id') {
            if (!form.value.unit_id) {
                errors.value.unit_id = 'Unit wajib dipilih.';
                isValid = false;
            } else {
                errors.value.unit_id = '';
            }
        }

        // Profesi
        if (!field || field === 'profesi_id') {
            if (!form.value.profesi_id) {
                errors.value.profesi_id = 'Profesi wajib dipilih.';
                isValid = false;
            } else {
                errors.value.profesi_id = '';
            }
        }

        return isValid;
    };

    const clearErrors = () => {
        errors.value = {
            nip: '',
            nama: '',
            unit_id: '',
            profesi_id: '',
            kontak: '',
            image: ''
        };
    };

    const saveForm = async () => {
        // Validasi input kosong
        if (!validatePegawai()) {
            return;
        }

        isSubmitting.value = true;

        try {
            const payload = new FormData();

            // ID hanya diperlukan saat edit
            if (isEditing.value) {
                payload.append('id', selectedId.value);
            }

            payload.append('nip', form.value.nip);
            payload.append('nama', form.value.nama);
            payload.append(
                'unit_id',
                form.value.unit_id
            );
            payload.append(
                'profesi_id',
                form.value.profesi_id
            );
            payload.append(
                'kontak',
                form.value.kontak ?? ''
            );

            // Hanya kirim image jika user memilih file baru
            if (form.value.image instanceof File) {
                payload.append('image', form.value.image);
            }

            if (isEditing.value) {
                // Update
                await pegawaiService.updatePegawai(payload);

                toast.success(
                    'Data pegawai berhasil diperbarui!'
                );
            } else {
                // Store
                await pegawaiService.storePegawai(payload);

                toast.success(
                    'Data pegawai berhasil ditambahkan!'
                );
            }

            closeModal();
            await fetchData();

        } catch (error) {
            console.error(
                'Gagal menyimpan data pegawai:',
                error
            );

            const errorMessage =
                error.response?.data?.message ||
                'Gagal menyimpan data pegawai.';

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
            await pegawaiService.deletePegawai({ id: selectedDeleteItem.value.id });

            // Tutup modal & bersihkan state
            isDeleteModalOpen.value = false;
            selectedDeleteItem.value = null;

            toast.success('Data pegawai berhasil dihapus!');

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
        validatePegawai,
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
        confirmDelete,

        profesiOptions,
        unitOptions,
        fetchProfesiOptions,
        fetchUnitOptions,
        imagePreview,
        handleImageChange,
    };
}
