<template>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
        <!-- Section Header & Top Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-blue-950">Data Profesi</h3>
                <p class="text-xs text-blue-950/60">Pengelolaan daftar profesi tenaga medis & non-medis</p>
            </div>

            <div class="flex items-center gap-2.5">
                <!-- Search Box -->
                <div class="relative w-full sm:w-64">
                    <input v-model="searchQuery" type="text" placeholder="Cari profesi..."
                        class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 pl-9 text-blue-950 placeholder:text-blue-950/40 focus:outline-none focus:border-[#B20600] focus:bg-white transition" />
                    <Search class="absolute left-3 top-2.5 text-blue-950/40" :size="15" />
                </div>

                <!-- Tombol Refresh Data -->
                <button @click="$emit('refresh')" :disabled="isLoading"
                    class="flex items-center justify-center p-2.5 bg-gray-50 hover:bg-gray-100 border border-gray-200 text-blue-950/70 hover:text-blue-950 rounded-xl transition cursor-pointer disabled:opacity-50 shrink-0"
                    title="Refresh Data">
                    <RotateCw :size="16" :class="{ 'animate-spin text-[#B20600]': isLoading }" />
                </button>

                <!-- Tombol Tambah Profesi -->
                <button @click="$emit('open-add')"
                    class="flex items-center justify-center gap-2 bg-[#B20600] hover:bg-[#8E0500] text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition cursor-pointer shrink-0 shadow-sm">
                    <Plus :size="16" />
                    <span>Tambah Profesi</span>
                </button>
            </div>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-blue-950">
                <thead class="bg-gray-50 text-xs font-semibold text-blue-950/60 uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 rounded-l-xl w-14 text-center">No.</th>
                        <th class="px-4 py-3">Nama Profesi</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 rounded-r-xl text-right">Aksi</th>
                    </tr>
                </thead>

                <!-- State 1: Memuat Data (Loading) -->
                <tbody v-if="isLoading" class="divide-y divide-gray-100">
                    <tr>
                        <td colspan="4" class="text-center py-6">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <div
                                    class="w-6 h-6 border-2 border-[#B20600] border-t-transparent rounded-full animate-spin">
                                </div>
                                <span class="text-xs font-medium text-blue-950/60">Memuat data...</span>
                            </div>
                        </td>
                    </tr>
                </tbody>

                <!-- State 2: Tidak Ada Data -->
                <tbody v-else-if="paginatedItems.length === 0" class="divide-y divide-gray-100">
                    <tr>
                        <td colspan="4" class="text-center py-6 text-xs text-blue-950/50">
                            Tidak ada data profesi yang ditemukan.
                        </td>
                    </tr>
                </tbody>

                <!-- State 3: Menampilkan Data -->
                <tbody v-else class="divide-y divide-gray-100">
                    <tr v-for="(item, index) in paginatedItems" :key="item.id" class="hover:bg-gray-50/50 transition">
                        <!-- Nomor Urut Dinamis Berdasarkan Halaman -->
                        <td class="px-4 py-3.5 text-center text-xs font-medium text-blue-950/70">
                            {{ (currentPage - 1) * itemsPerPage + index + 1 }}
                        </td>
                        <td class="px-4 py-3.5 font-medium text-blue-950">{{ item.profesi }}</td>
                        <td class="px-4 py-3.5">
                            <span :class="[
                                item.status === 1
                                    ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                    : 'bg-rose-50 text-rose-700 border-rose-200',
                                'px-2.5 py-1 rounded-lg text-xs font-semibold border'
                            ]">
                                {{ item.status === 1 ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </td>
                        <!-- Action Buttons: Edit & Delete -->
                        <td class="px-4 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-1.5">
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
                Menampilkan <span class="font-bold text-blue-950">{{ filteredItems.length ? (currentPage - 1) *
                    itemsPerPage + 1 : 0 }}</span>
                sampai <span class="font-bold text-blue-950">{{ Math.min(currentPage * itemsPerPage,
                    filteredItems.length) }}</span>
                dari <span class="font-bold text-blue-950">{{ filteredItems.length }}</span> data
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
                    'w-8 h-8 rounded-lg text-xs border flex items-center justify-center transition cursor-pointer'
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
import { Search, Plus, RotateCw, Pencil, Trash2, ChevronLeft, ChevronRight } from 'lucide-vue-next';

defineProps({
    paginatedItems: Array,
    filteredItems: Array,
    isLoading: Boolean,
    currentPage: Number,
    totalPages: Number,
    itemsPerPage: Number
});

const searchQuery = defineModel('searchQuery');

defineEmits(['open-add', 'open-edit', 'open-delete', 'change-page', 'refresh']);
</script>
