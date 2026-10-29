<script setup>
import { ref, computed } from 'vue'
import {
    FileSpreadsheet,
    FileText,
    Search,
    Files,
    ChevronLeft,
    ChevronRight,
    Download,
} from 'lucide-vue-next'

const props = defineProps({
    items: {
        type: Array,
        default: () => [],
    },
    isLoading: {
        type: Boolean,
        default: false,
    },
})

const searchQuery = ref('')
const currentPage = ref(1)
const itemsPerPage = 10

const filteredItems = computed(() => {
    const query = searchQuery.value.trim().toLowerCase()

    if (!query) return props.items

    return props.items.filter((item) => {
        const searchable = [
            item.nomor,
            item.pegawai?.nama,
            item.unit?.unit,
            item.nama_desain,
            item.statuspengajuan?.name,
        ]

        return searchable.some((value) =>
            String(value ?? '').toLowerCase().includes(query),
        )
    })
})

const totalPages = computed(() =>
    Math.max(1, Math.ceil(filteredItems.value.length / itemsPerPage)),
)

const paginatedItems = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage

    return filteredItems.value.slice(start, start + itemsPerPage)
})

const firstItem = computed(() =>
    filteredItems.value.length
        ? (currentPage.value - 1) * itemsPerPage + 1
        : 0,
)

const lastItem = computed(() =>
    Math.min(
        currentPage.value * itemsPerPage,
        filteredItems.value.length,
    ),
)

const goToPage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page
    }
}

const getStatusClass = (key) => {
    const classes = {
        diajukan: 'bg-blue-50 text-blue-700 border-blue-200',
        menunggu_verifikasi_it: 'bg-amber-50 text-amber-700 border-amber-200',
        menunggu_acc_pkrs: 'bg-amber-50 text-amber-700 border-amber-200',
        diproses: 'bg-indigo-50 text-indigo-700 border-indigo-200',
        siap_diambil: 'bg-violet-50 text-violet-700 border-violet-200',
        selesai: 'bg-emerald-50 text-emerald-700 border-emerald-200',
        ditolak: 'bg-rose-50 text-rose-700 border-rose-200',
        dibatalkan: 'bg-gray-50 text-gray-600 border-gray-200',
    }

    return classes[key] || 'bg-gray-50 text-gray-600 border-gray-200'
}

const formatTanggal = (tanggal) => {
    if (!tanggal) return '-'

    const date = new Date(tanggal)

    if (Number.isNaN(date.getTime())) return '-'

    return new Intl.DateTimeFormat('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    }).format(date)
}

const exportExcel = () => {
    // Hubungkan ke endpoint ekspor Excel Laravel.
    emit('export-excel')
}

const exportPdf = () => {
    // Hubungkan ke endpoint ekspor PDF Laravel.
    emit('export-pdf')
}

const emit = defineEmits(['export-excel', 'export-pdf'])

const formatJumlah = (jumlah) => {
    if (jumlah === null || jumlah === undefined || jumlah === '') return '-'

    return Number(jumlah).toLocaleString('id-ID')
}
</script>

<template>
    <div class="bg-white rounded-2xl border border-gray-100
               shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center
                   lg:justify-between gap-4 p-4 sm:p-6">
            <div class="flex items-start gap-3">
                <div class="flex items-center justify-center w-10 h-10
                           rounded-xl bg-red-50 text-[#B20600] shrink-0">
                    <Files :size="20" />
                </div>

                <div class="space-y-1">
                    <h2 class="text-sm sm:text-base font-bold text-blue-950">
                        Data Laporan
                    </h2>

                    <p class="text-xs text-blue-950/60">
                        Rekapitulasi data pengajuan desain sesuai filter
                    </p>
                </div>
            </div>

            <!-- Export -->
            <div class="flex flex-col sm:flex-row gap-2">
                <button type="button" @click="exportExcel" :disabled="isLoading" class="inline-flex items-center justify-center gap-2
                           px-4 py-2.5 rounded-xl text-xs font-semibold
                           text-emerald-700 bg-emerald-50
                           border border-emerald-100
                           hover:bg-emerald-100 transition cursor-pointer
                           disabled:opacity-50">
                    <FileSpreadsheet :size="16" />
                    Ekspor Excel
                    <Download :size="14" />
                </button>

                <button type="button" @click="exportPdf" :disabled="isLoading" class="inline-flex items-center justify-center gap-2
                           px-4 py-2.5 rounded-xl text-xs font-semibold
                           text-[#B20600] bg-red-50
                           border border-red-100
                           hover:bg-red-100 transition cursor-pointer
                           disabled:opacity-50">
                    <FileText :size="16" />
                    Ekspor PDF
                    <Download :size="14" />
                </button>
            </div>
        </div>

        <!-- Search & Count -->
        <div class="flex flex-col sm:flex-row sm:items-center
                   sm:justify-between gap-3 px-4 sm:px-6 pb-4">
            <div class="relative w-full sm:w-72">
                <Search :size="16" class="absolute left-3 top-1/2 -translate-y-1/2
                           text-blue-950/40" />

                <input v-model="searchQuery" @input="currentPage = 1" type="text" placeholder="Cari data laporan..."
                    class="w-full text-xs bg-gray-50 border border-gray-200
                           rounded-xl pl-9 pr-3.5 py-2.5 text-blue-950
                           placeholder:text-blue-950/40
                           focus:outline-none focus:border-[#B20600]
                           focus:bg-white transition" />
            </div>

            <p class="text-xs text-blue-950/60">
                Total
                <span class="font-semibold text-blue-950">
                    {{ filteredItems.length }}
                </span>
                data laporan
            </p>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1050px] text-left">
                <thead>
                    <tr class="bg-gray-50 border-y border-gray-100">
                        <th class="px-5 py-3.5 text-xs font-semibold text-blue-950/60">
                            No.
                        </th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-blue-950/60">
                            Nomor Pengajuan
                        </th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-blue-950/60">
                            Tanggal
                        </th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-blue-950/60">
                            Pengaju
                        </th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-blue-950/60">
                            Unit
                        </th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-blue-950/60">
                            Nama Desain
                        </th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-blue-950/60">
                            Jenis Media
                        </th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-blue-950/60">
                            Jumlah
                        </th>
                        <th class="px-5 py-3.5 text-xs font-semibold text-blue-950/60">
                            Status
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50">
                    <tr v-if="isLoading">
                        <td colspan="9" class="px-5 py-12 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-8 h-8 border-2 border-gray-200
                                           border-t-[#B20600] rounded-full animate-spin"></div>

                                <p class="text-xs text-blue-950/50">
                                    Memuat data laporan...
                                </p>
                            </div>
                        </td>
                    </tr>

                    <tr v-else-if="paginatedItems.length === 0">
                        <td colspan="9" class="px-5 py-12 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <Files :size="30" class="text-gray-300" />

                                <p class="text-sm font-semibold text-blue-950">
                                    Data laporan tidak ditemukan
                                </p>

                                <p class="text-xs text-blue-950/50">
                                    Coba ubah kata kunci atau filter laporan.
                                </p>
                            </div>
                        </td>
                    </tr>

                    <tr v-for="(item, index) in paginatedItems" v-else :key="item.id"
                        class="hover:bg-gray-50/70 transition">
                        <td class="px-5 py-4 text-xs text-blue-950/60">
                            {{ firstItem + index }}
                        </td>

                        <td class="px-5 py-4 text-xs font-semibold text-blue-950 whitespace-nowrap">
                            {{ item.nomor || '-' }}
                        </td>

                        <td class="px-5 py-4 text-xs text-blue-950/70 whitespace-nowrap">
                            {{ formatTanggal(item.created_at) }}
                        </td>

                        <td class="px-5 py-4 text-xs text-blue-950">
                            {{ item.pegawai?.nama || '-' }}
                        </td>

                        <td class="px-5 py-4 text-xs text-blue-950/70">
                            {{ item.unit?.unit || '-' }}
                        </td>

                        <td class="px-5 py-4 text-xs font-medium text-blue-950">
                            {{ item.nama_desain || '-' }}
                        </td>

                        <td class="px-5 py-4 text-xs text-blue-950/70">
                            <div v-if="item.pengajuanjenismedia?.length" class="flex flex-wrap gap-1.5 max-w-48">
                                <span v-for="media in item.pengajuanjenismedia" :key="media.id" class="inline-flex px-2 py-1 rounded-md
                                           bg-gray-100 text-gray-600 text-[10px]">
                                    {{ media.jenismedia?.name || media.jenismedia?.nama || '-' }}
                                </span>
                            </div>

                            <span v-else>-</span>
                        </td>

                        <td class="px-5 py-4 text-xs text-blue-950/70">
                            {{ formatJumlah(item.jumlah) }}
                        </td>

                        <td class="px-5 py-4">
                            <span :class="[
                                'inline-flex items-center px-2.5 py-1 rounded-full border text-[10px] font-semibold whitespace-nowrap',
                                getStatusClass(item.statuspengajuan?.key),
                            ]">
                                {{ item.statuspengajuan?.name || '-' }}
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex flex-col sm:flex-row sm:items-center
                   sm:justify-between gap-3 px-4 sm:px-6 py-4
                   border-t border-gray-100">
            <p class="text-xs text-blue-950/60">
                Menampilkan
                <span class="font-semibold text-blue-950">
                    {{ firstItem }}–{{ lastItem }}
                </span>
                dari
                <span class="font-semibold text-blue-950">
                    {{ filteredItems.length }}
                </span>
                data
            </p>

            <div class="flex items-center gap-1.5 self-end sm:self-auto">
                <button type="button" @click="goToPage(currentPage - 1)" :disabled="currentPage === 1" class="flex items-center justify-center w-9 h-9
                           rounded-lg border border-gray-200
                           text-blue-950/60 hover:bg-gray-50
                           disabled:opacity-40 disabled:cursor-not-allowed
                           transition cursor-pointer" aria-label="Halaman sebelumnya">
                    <ChevronLeft :size="16" />
                </button>

                <button v-for="page in totalPages" :key="page" type="button" @click="goToPage(page)" :class="[
                    'flex items-center justify-center min-w-9 h-9 px-2 rounded-lg border text-xs font-semibold transition cursor-pointer',
                    currentPage === page
                        ? 'bg-[#B20600] border-[#B20600] text-white'
                        : 'border-gray-200 text-blue-950/70 hover:bg-gray-50',
                ]">
                    {{ page }}
                </button>

                <button type="button" @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages" class="flex items-center justify-center w-9 h-9
                           rounded-lg border border-gray-200
                           text-blue-950/60 hover:bg-gray-50
                           disabled:opacity-40 disabled:cursor-not-allowed
                           transition cursor-pointer" aria-label="Halaman berikutnya">
                    <ChevronRight :size="16" />
                </button>
            </div>
        </div>
    </div>
</template>
