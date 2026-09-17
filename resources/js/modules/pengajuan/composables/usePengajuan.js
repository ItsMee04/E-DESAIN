import {
    ref,
    computed,
    watch
} from 'vue';

import { useToast } from '../../../utilities/toast/useToast';


export function usePengajuan() {

    const { toast } = useToast();


    /*
    |--------------------------------------------------------------------------
    | STATIC DATA
    |--------------------------------------------------------------------------
    */

    const items = ref([
        {
            id: 1,
            nomor: 'PD-20260917-0001',

            pegawai_id: 1,

            pegawai: {
                id: 1,
                nama: 'IT RSUWH'
            },

            unit_id: 1,

            unit: {
                id: 1,
                unit: 'Instalasi IT'
            },

            statuspengajuan_id: 2,

            statuspengajuan: {
                id: 2,
                name: 'Menunggu Validasi',
                key: 'menunggu_validasi'
            },

            nama_desain: 'Banner Hari Kesehatan Nasional',

            ukuran: '300 x 100 cm',

            jumlah: 2,

            keperluan:
                'Digunakan untuk media informasi kegiatan Hari Kesehatan Nasional.',

            pengajuanjenismedia: [
                {
                    id: 1,
                    jenis_media_id: 1,
                    jenismedia: {
                        id: 1,
                        jenismedia: 'Banner'
                    }
                },
                {
                    id: 2,
                    jenis_media_id: 2,
                    jenismedia: {
                        id: 2,
                        jenismedia: 'Poster'
                    }
                }
            ],

            pengajuanhistory: [
                {
                    id: 1,

                    statuspengajuan_id: 1,

                    statusPengajuan: {
                        id: 1,
                        name: 'Diajukan',
                        key: 'diajukan'
                    },

                    user: {
                        id: 1,
                        username: 'it',

                        pegawai: {
                            nama: 'IT RSUWH'
                        }
                    },

                    catatan:
                        'Pengajuan desain berhasil dibuat.',

                    created_at:
                        '2026-09-17T08:30:00'
                },

                {
                    id: 2,

                    statuspengajuan_id: 2,

                    statusPengajuan: {
                        id: 2,
                        name: 'Menunggu Validasi',
                        key: 'menunggu_validasi'
                    },

                    user: {
                        id: 1,
                        username: 'it',

                        pegawai: {
                            nama: 'IT RSUWH'
                        }
                    },

                    catatan:
                        'Pengajuan menunggu proses validasi.',

                    created_at:
                        '2026-09-17T09:00:00'
                }
            ]
        },

        {
            id: 2,
            nomor: 'PD-20260917-0002',

            pegawai_id: 2,

            pegawai: {
                id: 2,
                nama: 'Budi Santoso'
            },

            unit_id: 2,

            unit: {
                id: 2,
                unit: 'Keperawatan'
            },

            statuspengajuan_id: 3,

            statuspengajuan: {
                id: 3,
                name: 'Diproses',
                key: 'diproses'
            },

            nama_desain: 'Poster Edukasi Cuci Tangan',

            ukuran: 'A3',

            jumlah: 10,

            keperluan:
                'Media edukasi untuk pasien dan keluarga pasien.',

            pengajuanjenismedia: [
                {
                    id: 3,
                    jenis_media_id: 2,
                    jenismedia: {
                        id: 2,
                        jenismedia: 'Poster'
                    }
                }
            ],

            pengajuanhistory: [
                {
                    id: 3,

                    statuspengajuan_id: 1,

                    statusPengajuan: {
                        id: 1,
                        name: 'Diajukan',
                        key: 'diajukan'
                    },

                    user: {
                        id: 2,
                        username: 'budi',

                        pegawai: {
                            nama: 'Budi Santoso'
                        }
                    },

                    catatan:
                        'Pengajuan desain berhasil dibuat.',

                    created_at:
                        '2026-09-16T10:00:00'
                },

                {
                    id: 4,

                    statuspengajuan_id: 3,

                    statusPengajuan: {
                        id: 3,
                        name: 'Diproses',
                        key: 'diproses'
                    },

                    user: {
                        id: 1,
                        username: 'it',

                        pegawai: {
                            nama: 'IT RSUWH'
                        }
                    },

                    catatan:
                        'Desain sedang dalam proses pengerjaan.',

                    created_at:
                        '2026-09-17T08:00:00'
                }
            ]
        },

        {
            id: 3,
            nomor: 'PD-20260916-0003',

            pegawai_id: 3,

            pegawai: {
                id: 3,
                nama: 'Siti Aminah'
            },

            unit_id: 3,

            unit: {
                id: 3,
                unit: 'Farmasi'
            },

            statuspengajuan_id: 5,

            statuspengajuan: {
                id: 5,
                name: 'Selesai',
                key: 'selesai'
            },

            nama_desain: 'Label Obat Rawat Jalan',

            ukuran: '10 x 15 cm',

            jumlah: 100,

            keperluan:
                'Digunakan sebagai label informasi obat.',

            pengajuanjenismedia: [
                {
                    id: 4,
                    jenis_media_id: 3,
                    jenismedia: {
                        id: 3,
                        jenismedia: 'Label'
                    }
                }
            ],

            pengajuanhistory: [
                {
                    id: 5,

                    statuspengajuan_id: 1,

                    statusPengajuan: {
                        id: 1,
                        name: 'Diajukan',
                        key: 'diajukan'
                    },

                    user: {
                        id: 3,
                        username: 'siti',

                        pegawai: {
                            nama: 'Siti Aminah'
                        }
                    },

                    catatan:
                        'Pengajuan desain berhasil dibuat.',

                    created_at:
                        '2026-09-15T09:00:00'
                },

                {
                    id: 6,

                    statuspengajuan_id: 5,

                    statusPengajuan: {
                        id: 5,
                        name: 'Selesai',
                        key: 'selesai'
                    },

                    user: {
                        id: 1,
                        username: 'it',

                        pegawai: {
                            nama: 'IT RSUWH'
                        }
                    },

                    catatan:
                        'Desain telah selesai dikerjakan.',

                    created_at:
                        '2026-09-16T15:00:00'
                }
            ]
        }
    ]);


    /*
    |--------------------------------------------------------------------------
    | STATIC MASTER DATA
    |--------------------------------------------------------------------------
    */

    const units = ref([
        {
            id: 1,
            unit: 'Instalasi IT'
        },
        {
            id: 2,
            unit: 'Keperawatan'
        },
        {
            id: 3,
            unit: 'Farmasi'
        },
        {
            id: 4,
            unit: 'Marketing'
        },
        {
            id: 5,
            unit: 'Keuangan'
        }
    ]);


    const jenisMedia = ref([
        {
            id: 1,
            jenismedia: 'Banner'
        },
        {
            id: 2,
            jenismedia: 'Poster'
        },
        {
            id: 3,
            jenismedia: 'Label'
        },
        {
            id: 4,
            jenismedia: 'Pamflet'
        },
        {
            id: 5,
            jenismedia: 'Spanduk'
        }
    ]);


    const statuses = ref([
        {
            id: 1,
            name: 'Diajukan',
            key: 'diajukan'
        },
        {
            id: 2,
            name: 'Menunggu Validasi',
            key: 'menunggu_validasi'
        },
        {
            id: 3,
            name: 'Diproses',
            key: 'diproses'
        },
        {
            id: 4,
            name: 'Revisi',
            key: 'revisi'
        },
        {
            id: 5,
            name: 'Selesai',
            key: 'selesai'
        },
        {
            id: 6,
            name: 'Ditolak',
            key: 'ditolak'
        },
        {
            id: 7,
            name: 'Dibatalkan',
            key: 'dibatalkan'
        }
    ]);


    /*
    |--------------------------------------------------------------------------
    | STATE
    |--------------------------------------------------------------------------
    */

    const isLoading = ref(false);

    const isSubmitting = ref(false);

    const isDeleting = ref(false);


    const searchQuery = ref('');

    const statusFilter = ref('');


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
        pegawai_nama: 'IT RSUWH',
        unit_id: '',
        nama_desain: '',
        jenis_media: [],
        ukuran: '',
        jumlah: '',
        keperluan: ''
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
    | FILTER
    |--------------------------------------------------------------------------
    */

    const filteredItems = computed(() => {

        let data = [...items.value];

        const query =
            searchQuery.value
                .trim()
                .toLowerCase();


        if (query) {

            data = data.filter((item) => {

                return (

                    item.nomor
                        ?.toLowerCase()
                        .includes(query)

                    ||

                    item.nama_desain
                        ?.toLowerCase()
                        .includes(query)

                    ||

                    item.pegawai?.nama
                        ?.toLowerCase()
                        .includes(query)

                    ||

                    item.unit?.unit
                        ?.toLowerCase()
                        .includes(query)

                );

            });
        }


        if (statusFilter.value) {

            data = data.filter((item) => {

                return (
                    item.statuspengajuan?.key
                    === statusFilter.value
                );

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
            Math.ceil(
                filteredItems.value.length
                / itemsPerPage.value
            )
        );
    });


    const paginatedItems = computed(() => {

        const start =
            (currentPage.value - 1)
            * itemsPerPage.value;

        const end =
            start + itemsPerPage.value;

        return filteredItems.value.slice(
            start,
            end
        );
    });


    watch(
        [searchQuery, statusFilter],
        () => {

            currentPage.value = 1;

        }
    );


    /*
    |--------------------------------------------------------------------------
    | PAGINATION
    |--------------------------------------------------------------------------
    */

    const setPage = (page) => {

        if (
            page < 1
            || page > totalPages.value
        ) {
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

            pegawai_nama: 'IT RSUWH',

            unit_id: '',

            nama_desain: '',

            jenis_media: [],

            ukuran: '',

            jumlah: '',

            keperluan: ''

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

            pegawai_nama:
                item.pegawai?.nama
                ?? '',

            unit_id:
                item.unit_id
                ?? '',

            nama_desain:
                item.nama_desain
                ?? '',

            jenis_media:
                item.pengajuanjenismedia
                    ?.map(
                        media =>
                            media.jenis_media_id
                    )
                ?? [],

            ukuran:
                item.ukuran
                ?? '',

            jumlah:
                item.jumlah
                ?? '',

            keperluan:
                item.keperluan
                ?? ''
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

            errors.value.unit_id =
                'Unit wajib dipilih';

            return false;
        }

        delete errors.value.unit_id;

        return true;
    };


    const validateNamaDesain = () => {

        if (!form.value.nama_desain?.trim()) {

            errors.value.nama_desain =
                'Nama desain wajib diisi';

            return false;
        }

        delete errors.value.nama_desain;

        return true;
    };


    const validateJenisMedia = () => {

        if (
            !form.value.jenis_media
            || form.value.jenis_media.length === 0
        ) {

            errors.value.jenis_media =
                'Minimal pilih satu jenis media';

            return false;
        }

        delete errors.value.jenis_media;

        return true;
    };


    const validateUkuran = () => {

        if (!form.value.ukuran?.trim()) {

            errors.value.ukuran =
                'Ukuran wajib diisi';

            return false;
        }

        delete errors.value.ukuran;

        return true;
    };


    const validateJumlah = () => {

        if (
            form.value.jumlah === ''
            || form.value.jumlah === null
            || form.value.jumlah === undefined
        ) {

            errors.value.jumlah =
                'Jumlah cetak wajib diisi';

            return false;
        }


        if (
            Number.isNaN(
                Number(form.value.jumlah)
            )
            || Number(form.value.jumlah) < 1
        ) {

            errors.value.jumlah =
                'Jumlah cetak minimal 1';

            return false;
        }


        delete errors.value.jumlah;

        return true;
    };


    const validateKeperluan = () => {

        if (!form.value.keperluan?.trim()) {

            errors.value.keperluan =
                'Keperluan wajib diisi';

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
            validateKeperluan()
        ].every(Boolean);


        if (!valid) {
            return;
        }


        isSubmitting.value = true;


        /*
        | Simulasi proses API
        */

        await new Promise(
            resolve =>
                setTimeout(resolve, 700)
        );


        if (isEditing.value) {

            const index =
                items.value.findIndex(
                    item =>
                        item.id
                        === selectedId.value
                );


            if (index !== -1) {

                const selectedUnit =
                    units.value.find(
                        unit =>
                            unit.id
                            == form.value.unit_id
                    );


                const selectedMedia =
                    form.value.jenis_media.map(
                        mediaId => {

                            const media =
                                jenisMedia.value.find(
                                    item =>
                                        item.id
                                        == mediaId
                                );

                            return {

                                id: Date.now()
                                    + mediaId,

                                jenis_media_id:
                                    mediaId,

                                jenismedia:
                                    media

                            };
                        }
                    );


                items.value[index] = {

                    ...items.value[index],

                    unit_id:
                        Number(form.value.unit_id),

                    unit:
                        selectedUnit,

                    nama_desain:
                        form.value.nama_desain,

                    ukuran:
                        form.value.ukuran,

                    jumlah:
                        Number(form.value.jumlah),

                    keperluan:
                        form.value.keperluan,

                    pengajuanjenismedia:
                        selectedMedia
                };
            }


            toast.success(
                'Data pengajuan berhasil diperbarui'
            );

        } else {

            const selectedUnit =
                units.value.find(
                    unit =>
                        unit.id
                        == form.value.unit_id
                );


            const selectedMedia =
                form.value.jenis_media.map(
                    mediaId => {

                        const media =
                            jenisMedia.value.find(
                                item =>
                                    item.id
                                    == mediaId
                            );

                        return {

                            id: Date.now()
                                + mediaId,

                            jenis_media_id:
                                mediaId,

                            jenismedia:
                                media
                        };
                    }
                );


            const newId =
                Math.max(
                    ...items.value.map(
                        item => item.id
                    )
                ) + 1;


            items.value.unshift({

                id: newId,

                nomor:
                    `PD-20260917-${String(
                        newId
                    ).padStart(4, '0')}`,

                pegawai_id: 1,

                pegawai: {
                    id: 1,
                    nama: 'IT RSUWH'
                },

                unit_id:
                    Number(form.value.unit_id),

                unit:
                    selectedUnit,

                statuspengajuan_id: 1,

                statuspengajuan: {
                    id: 1,
                    name: 'Diajukan',
                    key: 'diajukan'
                },

                nama_desain:
                    form.value.nama_desain,

                ukuran:
                    form.value.ukuran,

                jumlah:
                    Number(form.value.jumlah),

                keperluan:
                    form.value.keperluan,

                pengajuanjenismedia:
                    selectedMedia,

                pengajuanhistory: [

                    {
                        id: Date.now(),

                        statuspengajuan_id: 1,

                        statusPengajuan: {
                            id: 1,
                            name: 'Diajukan',
                            key: 'diajukan'
                        },

                        user: {
                            id: 1,
                            username: 'it',

                            pegawai: {
                                nama: 'IT RSUWH'
                            }
                        },

                        catatan:
                            'Pengajuan desain berhasil dibuat.',

                        created_at:
                            new Date().toISOString()
                    }

                ]
            });


            toast.success(
                'Pengajuan desain berhasil dibuat'
            );
        }


        isSubmitting.value = false;

        isModalOpen.value = false;

        resetForm();

        currentPage.value = 1;
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


        await new Promise(
            resolve =>
                setTimeout(resolve, 700)
        );


        items.value =
            items.value.filter(
                item =>
                    item.id
                    !== selectedDeleteItem.value.id
            );


        toast.success(
            'Data pengajuan berhasil dihapus'
        );


        isDeleteModalOpen.value = false;

        selectedDeleteItem.value = null;

        isDeleting.value = false;


        if (
            currentPage.value
            > totalPages.value
        ) {
            currentPage.value =
                totalPages.value;
        }
    };


    /*
    |--------------------------------------------------------------------------
    | REFRESH STATIC
    |--------------------------------------------------------------------------
    */

    const refreshData = async () => {

        isLoading.value = true;


        await new Promise(
            resolve =>
                setTimeout(resolve, 500)
        );


        isLoading.value = false;

        toast.success(
            'Data pengajuan berhasil diperbarui'
        );
    };


    /*
    |--------------------------------------------------------------------------
    | RETURN
    |--------------------------------------------------------------------------
    */

    return {

        // Data
        items,
        statuses,
        units,
        jenisMedia,

        // Loading
        isLoading,
        isSubmitting,
        isDeleting,

        // Search
        searchQuery,
        statusFilter,

        // Pagination
        currentPage,
        totalPages,
        itemsPerPage,
        filteredItems,
        paginatedItems,

        // Form
        isModalOpen,
        isEditing,
        selectedId,
        form,
        errors,

        // Detail
        isDetailModalOpen,
        selectedDetailItem,

        // Delete
        isDeleteModalOpen,
        selectedDeleteItem,

        // Modal
        openAddModal,
        openEditModal,
        closeModal,

        // Detail
        openDetailModal,
        closeDetailModal,

        // Delete
        openDeleteModal,
        closeDeleteModal,

        // CRUD
        saveForm,
        deleteData,

        // Pagination
        setPage,

        // Refresh
        refreshData,

        // Validation
        validateUnit,
        validateNamaDesain,
        validateJenisMedia,
        validateUkuran,
        validateJumlah,
        validateKeperluan
    };
}
