<template>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
        <!-- Section Header & Top Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-blue-950">
                    Data Validasi Pengajuan
                </h3>

                <p class="text-xs text-blue-950/60">
                    Daftar pengajuan desain yang menunggu proses validasi
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
                <select v-model="statusFilter"
                    class="text-xs bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-blue-950 focus:outline-none focus:border-[#B20600] focus:bg-white transition cursor-pointer">
                    <option value="">Semua Status</option>

                    <option value="menunggu_validasi">Menunggu Validasi</option>
                </select>

                <!-- Tombol Refresh Data -->
                <button @click="$emit('refresh')" :disabled="isLoading"
                    class="flex items-center justify-center p-2.5 bg-gray-50 hover:bg-gray-100 border border-gray-200 text-blue-950/70 hover:text-blue-950 rounded-xl transition cursor-pointer disabled:opacity-50 shrink-0"
                    title="Refresh Data">
                    <RotateCw :size="16" :class="{
                        'animate-spin text-[#B20600]': isLoading,
                    }" />
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

                        <th class="px-4 py-3">No. Pengajuan</th>

                        <th class="px-4 py-3">Nama Desain</th>

                        <th class="px-4 py-3">Pemohon</th>

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
                            Tidak ada data pengajuan yang perlu divalidasi.
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
                        <td class="px-4 py-3.5 text-xs font-semibold text-blue-950 whitespace-nowrap">
                            {{ item.nomor }}
                        </td>

                        <!-- Nama Desain -->
                        <td class="px-4 py-3.5 text-xs font-medium text-blue-950">
                            {{ item.nama_desain }}
                        </td>

                        <!-- Pemohon -->
                        <td class="px-4 py-3.5 text-xs text-blue-950/80">
                            {{ item.pegawai?.nama || "-" }}
                        </td>

                        <!-- Unit -->
                        <td class="px-4 py-3.5 text-xs text-blue-950/80">
                            {{ item.unit?.unit || "-" }}
                        </td>

                        <!-- Status -->
                        <td class="px-4 py-3.5">
                            <span :class="[
                                getStatusClass(item.statuspengajuan?.key),
                                'px-2.5 py-1 rounded-lg text-xs font-semibold border',
                            ]">
                                {{ item.statuspengajuan?.name || "-" }}
                            </span>
                        </td>

                        <!-- Action -->
                        <td class="px-4 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <!-- Detail / Validasi -->
                                <button @click="$emit('open-detail', item)"
                                    class="p-1.5 text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 rounded-lg border border-blue-200/60 transition cursor-pointer"
                                    title="Detail & Validasi">
                                    <Eye :size="16" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination Controls -->
        <Pagination :current-page="currentPage" :total-pages="totalPages" :total-items="filteredItems.length"
            :items-per-page="itemsPerPage" @change-page="$emit('change-page', $event)" />
    </div>
</template>

<script setup>
import { Search, RotateCw, Eye } from "lucide-vue-next";
import Pagination from "../../../utilities/common/Pagination.vue";

defineProps({
    paginatedItems: Array,
    filteredItems: Array,
    isLoading: Boolean,
    currentPage: Number,
    totalPages: Number,
    itemsPerPage: Number,
});

const searchQuery = defineModel("searchQuery");
const statusFilter = defineModel("statusFilter");

defineEmits(["open-detail", "change-page", "refresh"]);

const getStatusClass = (key) => {
    const statusClasses = {
        diajukan: "bg-blue-50 text-blue-700 border-blue-200",
        menunggu_validasi: "bg-amber-50 text-amber-700 border-amber-200",
        menunggu_validasi_pkrs: "bg-amber-50 text-amber-700 border-amber-200",
        diproses: "bg-indigo-50 text-indigo-700 border-indigo-200",
        revisi: "bg-orange-50 text-orange-700 border-orange-200",
        tidak_acc: "bg-rose-50 text-rose-700 border-rose-200",
        selesai: "bg-emerald-50 text-emerald-700 border-emerald-200",
    };
    return statusClasses[key] || "bg-gray-50 text-gray-700 border-gray-200";
};
</script>
