<template>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
        <!-- Section Header & Top Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-blue-950">
                    Data Pengajuan Desain
                </h3>
                <p class="text-xs text-blue-950/60">
                    Pengelolaan daftar pengajuan desain
                </p>
            </div>

            <div class="flex items-center gap-2.5">
                <!-- Search Box -->
                <div class="relative w-full sm:w-64">
                    <input v-model="searchQuery" type="text" placeholder="Cari pengajuan..."
                        class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 pl-9 text-blue-950 placeholder:text-blue-950/40 focus:outline-none focus:border-[#B20600] focus:bg-white transition" />

                    <Search class="absolute left-3 top-2.5 text-blue-950/40" :size="15" />
                </div>

                <!-- Filter Status -->
                <div ref="statusDropdownRef" class="relative w-40">
                    <!-- Input -->
                    <div class="relative">
                        <input v-model="statusSearch" ref="statusInputRef" type="text" placeholder="Semua Status"
                            class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 pr-9 text-blue-950 placeholder:text-blue-950/40 focus:outline-none focus:border-[#B20600] focus:bg-white transition cursor-pointer"
                            @focus="openStatusDropdown" @input="isStatusOpen = true" />

                        <ChevronDown
                            class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-blue-950/50 pointer-events-none transition-transform duration-200"
                            :class="{ 'rotate-180': isStatusOpen }" />
                    </div>

                    <!-- Dropdown -->
                    <div v-if="isStatusOpen"
                        class="absolute z-50 left-0 right-0 mt-1.5 bg-white border border-blue-950/10 rounded-xl shadow-lg shadow-blue-950/10 overflow-hidden">
                        <!-- Header -->
                        <div class="px-3 py-2 border-b border-blue-950/5">
                            <p class="text-[11px] font-medium text-blue-950/40 uppercase tracking-wide">
                                Pilih status
                            </p>
                        </div>

                        <!-- List -->
                        <div class="max-h-48 overflow-y-auto py-1">

                            <!-- Semua Status -->
                            <button type="button"
                                class="w-full flex items-center justify-between px-3 py-2.5 text-left text-sm transition-colors duration-150"
                                :class="!statusFilter
                                    ? 'bg-[#B20600]/5 text-[#B20600]'
                                    : 'text-blue-950/80 hover:bg-blue-950/[0.03]'
                                    " @mousedown.prevent="selectStatus(null)">
                                <span>Semua Status</span>

                                <span v-if="!statusFilter"
                                    class="w-5 h-5 flex items-center justify-center rounded-full bg-[#B20600] text-white text-[10px]">
                                    ✓
                                </span>
                            </button>

                            <!-- Status dari API -->
                            <button v-for="status in filteredStatuses" :key="status.id" type="button"
                                class="w-full flex items-center justify-between px-3 py-2.5 text-left text-sm transition-colors duration-150"
                                :class="String(statusFilter) === String(status.id)
                                    ? 'bg-[#B20600]/5 text-[#B20600]'
                                    : 'text-blue-950/80 hover:bg-blue-950/[0.03]'
                                    " @mousedown.prevent="selectStatus(status)">
                                <span>{{ status.name }}</span>

                                <span v-if="String(statusFilter) === String(status.id)"
                                    class="w-5 h-5 flex items-center justify-center rounded-full bg-[#B20600] text-white text-[10px]">
                                    ✓
                                </span>
                            </button>

                            <!-- Empty State -->
                            <div v-if="filteredStatuses.length === 0" class="px-3 py-6 text-center">
                                <p class="text-xs text-blue-950/40">
                                    Data status tidak ditemukan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Refresh Data -->
                <button @click="$emit('refresh')" :disabled="isLoading"
                    class="flex items-center justify-center p-2.5 bg-gray-50 hover:bg-gray-100 border border-gray-200 text-blue-950/70 hover:text-blue-950 rounded-xl transition cursor-pointer disabled:opacity-50 shrink-0"
                    title="Refresh Data">
                    <RotateCw :size="16" :class="{ 'animate-spin text-[#B20600]': isLoading }" />
                </button>

                <!-- Tombol Tambah Pengajuan -->
                <button @click="$emit('open-add')"
                    class="flex items-center justify-center gap-2 bg-[#B20600] hover:bg-[#8E0500] text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition cursor-pointer shrink-0 shadow-sm">
                    <Plus :size="16" />

                    <span>Tambah Pengajuan</span>
                </button>
            </div>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-blue-950">
                <thead class="bg-gray-50 text-xs font-semibold text-blue-950/60 uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 rounded-l-xl w-14 text-center">
                            No.
                        </th>
                        <th class="px-4 py-3">Nomor Pengajuan</th>
                        <th class="px-4 py-3">Nama Desain</th>
                        <th class="px-4 py-3">Pengaju</th>
                        <th class="px-4 py-3">Unit</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 rounded-r-xl text-right">Aksi</th>
                    </tr>
                </thead>

                <!-- State 1: Memuat Data -->
                <tbody v-if="isLoading" class="divide-y divide-gray-100">
                    <tr>
                        <td colspan="7" class="text-center py-6">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <div
                                    class="w-6 h-6 border-2 border-[#B20600] border-t-transparent rounded-full animate-spin">
                                </div>
                                <span class="text-xs font-medium text-blue-950/60">
                                    Memuat data...
                                </span>
                            </div>
                        </td>
                    </tr>
                </tbody>

                <!-- State 2: Tidak Ada Data -->
                <tbody v-else-if="paginatedItems.length === 0" class="divide-y divide-gray-100">
                    <tr>
                        <td colspan="7" class="text-center py-6 text-xs text-blue-950/50">
                            Tidak ada data pengajuan yang ditemukan.
                        </td>
                    </tr>
                </tbody>

                <!-- State 3: Menampilkan Data -->
                <tbody v-else class="divide-y divide-gray-100">
                    <tr v-for="(item, index) in paginatedItems" :key="item.id" class="hover:bg-gray-50/50 transition">
                        <!-- Nomor Urut -->
                        <td class="px-4 py-3.5 text-center text-xs font-medium text-blue-950/70">
                            {{ (currentPage - 1) * itemsPerPage + index + 1 }}
                        </td>
                        <!-- Nomor Pengajuan -->
                        <td class="px-4 py-3.5">
                            <span class="font-semibold text-blue-950">
                                {{ item.nomor }}
                            </span>
                        </td>
                        <!-- Nama Desain -->
                        <td class="px-4 py-3.5">
                            <span class="font-medium text-blue-950">
                                {{ item.nama_desain }}
                            </span>
                        </td>
                        <!-- Pengaju -->
                        <td class="px-4 py-3.5 text-xs">
                            {{ item.pegawai?.nama ?? "-" }}
                        </td>
                        <!-- Unit -->
                        <td class="px-4 py-3.5 text-xs">
                            {{ item.unit?.unit ?? "-" }}
                        </td>
                        <!-- Status -->
                        <td class="px-4 py-3.5">
                            <span :class="[
                                getStatusClass(item.statuspengajuan?.key),
                                'px-2.5 py-1 rounded-lg text-xs font-semibold border',
                            ]">
                                {{ item.statuspengajuan?.name ?? "-" }}
                            </span>
                        </td>
                        <!-- Action Buttons -->
                        <td class="px-4 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <!-- Tombol Detail -->
                                <button @click="$emit('open-detail', item)"
                                    class="p-1.5 text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 rounded-lg border border-blue-200/60 transition cursor-pointer"
                                    title="Detail Pengajuan">
                                    <Eye :size="16" />
                                </button>
                                <!-- Tombol Edit -->
                                <button @click="$emit('open-edit', item)"
                                    class="p-1.5 text-amber-600 bg-amber-50 hover:bg-amber-100 hover:text-amber-700 rounded-lg border border-amber-200/60 transition cursor-pointer"
                                    title="Edit Data">
                                    <Pencil :size="16" />
                                </button>
                                <!-- Tombol Hapus -->
                                <button @click="$emit('open-delete', item)"
                                    class="p-1.5 text-rose-600 bg-rose-50 hover:bg-rose-100 hover:text-rose-700 rounded-lg border border-rose-200/60 transition cursor-pointer"
                                    title="Hapus Data">
                                    <Trash2 :size="16" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination Controls -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-3 border-t border-gray-100">
            <div class="text-xs text-blue-950/60">
                Menampilkan
                <span class="font-bold text-blue-950">
                    {{
                        filteredItems.length
                            ? (currentPage - 1) * itemsPerPage + 1
                            : 0
                    }}
                </span>

                sampai
                <span class="font-bold text-blue-950">
                    {{
                        Math.min(
                            currentPage * itemsPerPage,
                            filteredItems.length,
                        )
                    }}
                </span>

                dari
                <span class="font-bold text-blue-950">
                    {{ filteredItems.length }}
                </span>
                data
            </div>

            <div class="flex items-center gap-1.5">
                <button @click="$emit('change-page', currentPage - 1)" :disabled="currentPage === 1"
                    class="p-2 rounded-lg border border-gray-200 text-blue-950 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-gray-50 transition cursor-pointer">
                    <ChevronLeft :size="15" />
                </button>

                <button v-for="page in totalPages" :key="page" @click="$emit('change-page', page)" :class="[
                    page === currentPage
                        ? 'bg-[#B20600] text-white font-bold border-[#B20600]'
                        : 'bg-white text-blue-950 hover:bg-gray-50 border-gray-200',
                    'w-8 h-8 rounded-lg text-xs border flex items-center justify-center transition cursor-pointer',
                ]">
                    {{ page }}
                </button>

                <button @click="$emit('change-page', currentPage + 1)"
                    :disabled="currentPage === totalPages || totalPages === 0"
                    class="p-2 rounded-lg border border-gray-200 text-blue-950 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-gray-50 transition cursor-pointer">
                    <ChevronRight :size="15" />
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import {
    Search,
    Plus,
    RotateCw,
    Eye,
    Pencil,
    Trash2,
    ChevronLeft,
    ChevronRight,
    ChevronDown
} from "lucide-vue-next";

const props = defineProps({
    paginatedItems: {
        type: Array,
        default: () => [],
    },
    filteredItems: {
        type: Array,
        default: () => [],
    },
    isLoading: Boolean,
    currentPage: Number,
    totalPages: Number,
    itemsPerPage: Number,
    statuses: {
        type: Array,
        default: () => [],
    },
});

const searchQuery = defineModel("searchQuery");
const statusFilter = defineModel("statusFilter");

const statusSearch = ref("");
const isStatusOpen = ref(false);
const statusDropdownRef = ref(null);
const statusInputRef = ref(null);

const filteredStatuses = computed(() => {
    const query = statusSearch.value.trim().toLowerCase();

    if (!query) {
        return props.statuses;
    }

    return props.statuses.filter((status) =>
        status.name?.toLowerCase().includes(query)
    );
});

const openStatusDropdown = () => {
    statusSearch.value = "";
    isStatusOpen.value = true;
};

const selectStatus = (status) => {
    if (!status) {
        statusFilter.value = "";
        statusSearch.value = "";
    } else {
        statusFilter.value = status.id;
        statusSearch.value = status.name;
    }

    isStatusOpen.value = false;

    // Lepaskan focus dari input setelah memilih
    statusInputRef.value?.blur();
};

const handleStatusClickOutside = (event) => {
    if (
        statusDropdownRef.value &&
        !statusDropdownRef.value.contains(event.target)
    ) {
        isStatusOpen.value = false;

        if (!statusFilter.value) {
            statusSearch.value = "";
            return;
        }

        const selected = props.statuses.find(
            (status) =>
                String(status.id) === String(statusFilter.value)
        );

        statusSearch.value = selected?.name ?? "";
    }
};

onMounted(() => {
    document.addEventListener("mousedown", handleStatusClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener("mousedown", handleStatusClickOutside);
});

defineEmits([
    "open-add",
    "open-detail",
    "open-edit",
    "open-delete",
    "change-page",
    "refresh",
]);

const getStatusClass = (key) => {
    const statusClasses = {
        diajukan: "bg-blue-50 text-blue-700 border-blue-200",
        menunggu_validasi: "bg-amber-50 text-amber-700 border-amber-200",
        diproses: "bg-indigo-50 text-indigo-700 border-indigo-200",
        revisi: "bg-orange-50 text-orange-700 border-orange-200",
        selesai: "bg-emerald-50 text-emerald-700 border-emerald-200",
        ditolak: "bg-rose-50 text-rose-700 border-rose-200",
        dibatalkan: "bg-gray-50 text-gray-700 border-gray-200",
    };

    return statusClasses[key] ?? "bg-gray-50 text-gray-700 border-gray-200";
};
</script>
