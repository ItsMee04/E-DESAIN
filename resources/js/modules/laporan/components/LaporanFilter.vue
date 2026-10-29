<template>
    <div class="space-y-5">
        <!-- Judul dan Deskripsi -->
        <div class="space-y-1">
            <div class="flex items-center gap-2.5">
                <div class="flex items-center justify-center w-10 h-10
                           rounded-xl bg-red-50 text-[#B20600] shrink-0">
                    <FileText :size="21" />
                </div>

                <h1 class="text-xl sm:text-2xl font-bold text-blue-950">
                    Laporan Pengajuan Desain
                </h1>
            </div>

            <p class="text-xs sm:text-sm text-blue-950/60">
                Ringkasan dan rekapitulasi pengajuan desain IT
            </p>
        </div>

        <!-- Form Filter -->
        <div class="bg-white rounded-2xl border border-gray-100
                   shadow-sm p-4 sm:p-6 space-y-5">
            <!-- Header Filter -->
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10
                           rounded-xl bg-gray-50 text-blue-950 shrink-0">
                    <Filter :size="18" />
                </div>

                <div class="space-y-0.5">
                    <h2 class="text-sm font-bold text-blue-950">
                        Filter Laporan
                    </h2>

                    <p class="text-xs text-blue-950/60">
                        Tentukan periode dan status pengajuan
                    </p>
                </div>
            </div>

            <!-- Input Filter -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Tanggal Mulai -->
                <div class="space-y-1.5 min-w-0">
                    <label class="text-xs font-semibold text-blue-950">
                        Dari Tanggal
                    </label>

                    <DatePicker v-model="tanggalMulai" placeholder="Pilih tanggal mulai"
                        :max-date="tanggalAkhir || undefined" />
                </div>

                <!-- Tanggal Akhir -->
                <div class="space-y-1.5 min-w-0">
                    <label class="text-xs font-semibold text-blue-950">
                        Sampai Tanggal
                    </label>

                    <DatePicker v-model="tanggalAkhir" placeholder="Pilih tanggal akhir"
                        :min-date="tanggalMulai || undefined" />
                </div>

                <!-- Status -->
                <div class="space-y-1.5 min-w-0">
                    <label class="text-xs font-semibold text-blue-950">
                        Status Pengajuan
                    </label>

                    <SearchableDropdown v-model="statusFilter" :options="props.statuses" label-key="name" value-key="id"
                        placeholder="Semua Status" dropdown-title="Pilih status" :allow-all="true"
                        all-label="Semua Status" empty-text="Data status tidak ditemukan." />
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex flex-col-reverse sm:flex-row
                       sm:items-center sm:justify-end gap-2.5 pt-1">
                <button type="button" @click="resetFilter" :disabled="isLoading" class="inline-flex items-center justify-center gap-2
                           w-full sm:w-auto px-4 py-2.5
                           text-xs font-semibold text-blue-950/70
                           bg-white border border-gray-200 rounded-xl
                           hover:bg-gray-50 transition cursor-pointer
                           disabled:opacity-50 disabled:cursor-not-allowed">
                    <RotateCcw :size="15" />
                    Reset
                </button>

                <button type="button" @click="terapkanFilter" :disabled="isLoading" class="inline-flex items-center justify-center gap-2
                           w-full sm:w-auto px-4 py-2.5
                           text-xs font-semibold text-white
                           bg-[#B20600] hover:bg-[#8E0500]
                           rounded-xl shadow-sm transition cursor-pointer
                           disabled:opacity-50 disabled:cursor-not-allowed">
                    <LoaderCircle v-if="isLoading" :size="15" class="animate-spin" />

                    <Search v-else :size="15" />

                    {{ isLoading ? 'Memuat...' : 'Terapkan Filter' }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import {
    FileText,
    Filter,
    RotateCcw,
    Search,
    LoaderCircle,
} from 'lucide-vue-next'

import DatePicker from '../../../utilities/common/DatePicker.vue'
import SearchableDropdown from '../../../utilities/common/SearchableDropdown.vue'
import { useLaporanFilter } from '../composables/useLaporan.js'

const props = defineProps({
    statuses: {
        type: Array,
        default: () => [],
    },
    isLoading: {
        type: Boolean,
        default: false,
    },
})

const emit = defineEmits(['apply', 'reset'])

const {
    tanggalMulai,
    tanggalAkhir,
    statusFilter,
    terapkanFilter,
    resetFilter,
} = useLaporanFilter(emit)
</script>
