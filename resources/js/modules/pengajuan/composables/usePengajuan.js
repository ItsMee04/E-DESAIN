import { ref, computed, watch, onMounted } from "vue";

import { pengajuanService } from "../services/pengajuanService"
import { unitService } from "../../unit/services/unitService";
import { jenismediaService } from "../../jenismedia/services/jenismediaService";
import { statuspengajuanService } from "../../statuspengajuan/services/statuspengajuanService";

import { useToast } from "../../../utilities/toast/useToast";

export function usePengajuan() {
    const { toast } = useToast();

    const units = ref([]);
    const jenisMedia = ref([]);

    /*
    |--------------------------------------------------------------------------
    | STATIC DATA
    |--------------------------------------------------------------------------
    */
    const items = ref([]);
    const getPengajuan = async () => {
        try {
            isLoading.value = true;

            const response = await pengajuanService.getPengajuan();

            items.value = response.data || response;
        } catch (error) {
            console.error("Gagal mengambil data pengajuan:", error);

            items.value = [];

            toast.error("Gagal mengambil data pengajuan");
        } finally {
            isLoading.value = false;
        }
    };
    /*
    |--------------------------------------------------------------------------
    | STATIC MASTER DATA
    |--------------------------------------------------------------------------
    */
    const statuses = ref([]);
    const getStatusPengajuan = async () => {
        try {
            const response = await statuspengajuanService.getStatusPengajuan();

            statuses.value = response.data || response;
        } catch (error) {
            console.error("Gagal mengambil data status pengajuan:", error);

            statuses.value = [];

            toast.error("Gagal mengambil data status pengajuan");
        }
    };

    /*
    |--------------------------------------------------------------------------
    | STATE
    |--------------------------------------------------------------------------
    */
    const isLoading = ref(false);
    const isSubmitting = ref(false);
    const isDeleting = ref(false);
    const searchQuery = ref("");
    const statusFilter = ref("");

    /*
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    */
    const currentPage = ref(1);
    const itemsPerPage = ref(5);

    /*
    |--------------------------------------------------------------------------
    | FORM
    |--------------------------------------------------------------------------
    */
    const isModalOpen = ref(false);
    const isEditing = ref(false);
    const selectedId = ref(null);

    const form = ref({
        pegawai_nama: "IT RSUWH",
        unit_id: "",
        nama_desain: "",
        jenis_media: [],
        ukuran: "",
        jumlah: "",
        keperluan: "",
    });

    const errors = ref({});

    /*
    |--------------------------------------------------------------------------
    | DETAIL
    |--------------------------------------------------------------------------
    */
    const isDetailModalOpen = ref(false);
    const selectedDetailItem = ref(null);

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    const isDeleteModalOpen = ref(false);
    const selectedDeleteItem = ref(null);

    /*
    |--------------------------------------------------------------------------
    | MASTER DATA - UNIT
    |--------------------------------------------------------------------------
    */
    const getUnits = async () => {
        try {
            const response = await unitService.getUnit();

            units.value = response.data || response;
        } catch (error) {
            console.error("Gagal mengambil data unit:", error);

            units.value = [];

            toast.error("Gagal mengambil data unit");
        }
    };

    /*
    |--------------------------------------------------------------------------
    | MASTER DATA - JENIS MEDIA
    |--------------------------------------------------------------------------
    */
    const getJenisMedia = async () => {
        try {
            const response = await jenismediaService.getJenisMedia();

            jenisMedia.value = response.data || response;
        } catch (error) {
            console.error("Gagal mengambil data jenis media:", error);

            jenisMedia.value = [];

            toast.error("Gagal mengambil data jenis media");
        }
    };

    /*
    |--------------------------------------------------------------------------
    | LOAD MASTER DATA
    |--------------------------------------------------------------------------
    */
    onMounted(() => {
        getUnits();
        getJenisMedia();
        getStatusPengajuan();
        getPengajuan();
    });

    /*
    |--------------------------------------------------------------------------
    | FILTER
    |--------------------------------------------------------------------------
    */
    const filteredItems = computed(() => {
        let data = [...items.value];
        const query = searchQuery.value.trim().toLowerCase();

        if (query) {
            data = data.filter((item) => {
                return (
                    item.nomor?.toLowerCase().includes(query) ||
                    item.nama_desain?.toLowerCase().includes(query) ||
                    item.pegawai?.nama?.toLowerCase().includes(query) ||
                    item.unit?.unit?.toLowerCase().includes(query)
                );
            });
        }

        if (statusFilter.value) {
            data = data.filter((item) => {
                return Number(item.statuspengajuan?.id) === Number(statusFilter.value);
            });
        }

        return data;
    });

    /*
    |--------------------------------------------------------------------------
    | PAGINATION DATA
    |--------------------------------------------------------------------------
    */
    const totalPages = computed(() => {
        return Math.max(
            1,
            Math.ceil(filteredItems.value.length / itemsPerPage.value),
        );
    });

    const paginatedItems = computed(() => {
        const start = (currentPage.value - 1) * itemsPerPage.value;
        const end = start + itemsPerPage.value;

        return filteredItems.value.slice(start, end);
    });

    watch([searchQuery, statusFilter], () => {
        currentPage.value = 1;
    });

    /*
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    */
    const setPage = (page) => {
        if (page < 1 || page > totalPages.value) {
            return;
        }

        currentPage.value = page;
    };

    /*
    |--------------------------------------------------------------------------
    | RESET FORM
    |--------------------------------------------------------------------------
    */
    const resetForm = () => {
        selectedId.value = null;

        form.value = {
            pegawai_nama: "IT RSUWH",
            unit_id: "",
            nama_desain: "",
            jenis_media: [],
            ukuran: "",
            jumlah: "",
            keperluan: "",
        };

        errors.value = {};
    };

    /*
    |--------------------------------------------------------------------------
    | ADD
    |--------------------------------------------------------------------------
    */
    const openAddModal = () => {
        resetForm();
        isEditing.value = false;
        isModalOpen.value = true;
    };

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    const openEditModal = (item) => {
        isEditing.value = true;
        selectedId.value = item.id;

        form.value = {
            pegawai_nama: item.pegawai?.nama ?? "",
            unit_id: item.unit_id ?? "",
            nama_desain: item.nama_desain ?? "",
            jenis_media:
                item.pengajuanjenismedia?.map(
                    (media) => media.jenis_media_id,
                ) ?? [],
            ukuran: item.ukuran ?? "",
            jumlah: item.jumlah ?? "",
            keperluan: item.keperluan ?? "",
        };

        errors.value = {};
        isModalOpen.value = true;
    };

    /*
    |--------------------------------------------------------------------------
    | CLOSE FORM
    |--------------------------------------------------------------------------
    */
    const closeModal = () => {
        if (isSubmitting.value) {
            return;
        }

        isModalOpen.value = false;
        resetForm();
    };

    /*
    |--------------------------------------------------------------------------
    | DETAIL
    |--------------------------------------------------------------------------
    */
    const openDetailModal = (item) => {
        selectedDetailItem.value = item;
        isDetailModalOpen.value = true;
    };

    const closeDetailModal = () => {
        isDetailModalOpen.value = false;
        selectedDetailItem.value = null;
    };

    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */
    const validateUnit = () => {
        if (!form.value.unit_id) {
            errors.value.unit_id = "Unit wajib dipilih";
            return false;
        }

        delete errors.value.unit_id;
        return true;
    };

    const validateNamaDesain = () => {
        if (!form.value.nama_desain?.trim()) {
            errors.value.nama_desain = "Nama desain wajib diisi";
            return false;
        }

        delete errors.value.nama_desain;
        return true;
    };

    const validateJenisMedia = () => {
        if (!form.value.jenis_media || form.value.jenis_media.length === 0) {
            errors.value.jenis_media = "Minimal pilih satu jenis media";
            return false;
        }

        delete errors.value.jenis_media;
        return true;
    };

    const validateUkuran = () => {
        if (!form.value.ukuran?.trim()) {
            errors.value.ukuran = "Ukuran wajib diisi";
            return false;
        }

        delete errors.value.ukuran;
        return true;
    };

    const validateJumlah = () => {
        if (
            form.value.jumlah === "" ||
            form.value.jumlah === null ||
            form.value.jumlah === undefined
        ) {
            errors.value.jumlah = "Jumlah cetak wajib diisi";
            return false;
        }

        if (
            Number.isNaN(Number(form.value.jumlah)) ||
            Number(form.value.jumlah) < 1
        ) {
            errors.value.jumlah = "Jumlah cetak minimal 1";
            return false;
        }

        delete errors.value.jumlah;
        return true;
    };

    const validateKeperluan = () => {
        if (!form.value.keperluan?.trim()) {
            errors.value.keperluan = "Keperluan wajib diisi";
            return false;
        }

        delete errors.value.keperluan;
        return true;
    };

    /*
    |--------------------------------------------------------------------------
    | SAVE STATIC
    |--------------------------------------------------------------------------
    */
    const saveForm = async () => {
        errors.value = {};

        const valid = [
            validateUnit(),
            validateNamaDesain(),
            validateJenisMedia(),
            validateUkuran(),
            validateJumlah(),
            validateKeperluan(),
        ].every(Boolean);

        if (!valid) {
            return;
        }

        const payload = {
            unit_id: Number(form.value.unit_id),
            nama_desain: form.value.nama_desain,
            jenis_media: form.value.jenis_media.map(Number),
            ukuran: form.value.ukuran,
            jumlah: Number(form.value.jumlah),
            keperluan: form.value.keperluan,
        };

        console.log("Payload Pengajuan:", payload);
        console.log("Payload JSON:", JSON.stringify(payload, null, 4));

        isSubmitting.value = true;

        try {
            if (isEditing.value) {
                const response = await pengajuanService.updatePengajuan({
                    id: selectedId.value,
                    ...payload,
                });

                console.log("Response Update Pengajuan:", response);

                toast.success("Data pengajuan berhasil diperbarui");
            } else {
                const response = await pengajuanService.storePengajuan(payload);

                console.log("Response Store Pengajuan:", response);

                toast.success("Pengajuan desain berhasil dibuat");
            }

            await getPengajuan();

            isModalOpen.value = false;
            resetForm();
            currentPage.value = 1;
        } catch (error) {
            console.error("Gagal menyimpan pengajuan:", error);

            const responseErrors = error.response?.data?.errors;

            if (responseErrors) {
                errors.value = Object.keys(responseErrors).reduce((result, key) => {
                    result[key] = responseErrors[key][0];
                    return result;
                }, {});
            } else {
                toast.error(
                    error.response?.data?.message ||
                    "Gagal menyimpan data pengajuan"
                );
            }
        } finally {
            isSubmitting.value = false;
        }
    };

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    const openDeleteModal = (item) => {
        selectedDeleteItem.value = item;
        isDeleteModalOpen.value = true;
    };

    const closeDeleteModal = () => {
        if (isDeleting.value) {
            return;
        }

        isDeleteModalOpen.value = false;
        selectedDeleteItem.value = null;
    };

    const deleteData = async () => {
        if (!selectedDeleteItem.value) {
            return;
        }

        isDeleting.value = true;

        await new Promise((resolve) => setTimeout(resolve, 700));

        items.value = items.value.filter(
            (item) => item.id !== selectedDeleteItem.value.id,
        );

        toast.success("Data pengajuan berhasil dihapus");

        isDeleteModalOpen.value = false;
        selectedDeleteItem.value = null;
        isDeleting.value = false;

        if (currentPage.value > totalPages.value) {
            currentPage.value = totalPages.value;
        }
    };

    /*
    |--------------------------------------------------------------------------
    | REFRESH STATIC
    |--------------------------------------------------------------------------
    */
    const refreshData = async () => {
        isLoading.value = true;

        await new Promise((resolve) => setTimeout(resolve, 500));

        isLoading.value = false;

        toast.success("Data pengajuan berhasil diperbarui");
    };

    /*
    |--------------------------------------------------------------------------
    | RETURN
    |--------------------------------------------------------------------------
    */
    return {
        items,
        statuses,
        units,
        jenisMedia,

        isLoading,
        isSubmitting,
        isDeleting,

        searchQuery,
        statusFilter,

        currentPage,
        totalPages,
        itemsPerPage,
        filteredItems,
        paginatedItems,

        isModalOpen,
        isEditing,
        selectedId,
        form,
        errors,

        isDetailModalOpen,
        selectedDetailItem,

        isDeleteModalOpen,
        selectedDeleteItem,

        openAddModal,
        openEditModal,
        closeModal,

        openDetailModal,
        closeDetailModal,

        openDeleteModal,
        closeDeleteModal,

        saveForm,
        deleteData,

        setPage,
        refreshData,

        validateUnit,
        validateNamaDesain,
        validateJenisMedia,
        validateUkuran,
        validateJumlah,
        validateKeperluan,
    };
}
