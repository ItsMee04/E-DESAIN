<template>
    <div class="space-y-6">
        <LaporanFilter
            :statuses="statuses"
            :is-loading="isLoading"
            @apply="applyFilter"
            @reset="resetFilter"
        />

        <LaporanStatistik v-bind="statistik" />

        <LaporanTable
            :items="laporan"
            :is-loading="isLoading"
            @export-excel="exportExcel"
            @export-pdf="exportPdf"
        />
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useToast } from '../../../utilities/toast/useToast.js'
import { useLaporanData } from '../composables/useLaporanData.js'

import LaporanFilter from '../components/LaporanFilter.vue'
import LaporanStatistik from '../components/LaporanStatistik.vue'
import LaporanTable from '../components/LaporanTable.vue'

const { toast } = useToast()
const statuses = ref([])

const {
    laporan,
    statistik,
    isLoading,
    getLaporan,
    exportExcel,
    exportPdf,
} = useLaporanData()

const applyFilter = async (payload) => {
    try {
        await getLaporan(payload)
    } catch (error) {
        console.error('Gagal mengambil laporan:', error)

        toast.error(
            error.response?.data?.message ??
            'Gagal mengambil laporan pengajuan.'
        )
    }
}

const resetFilter = async () => {
    try {
        await getLaporan({})
    } catch (error) {
        console.error('Gagal memuat ulang laporan:', error)

        toast.error(
            error.response?.data?.message ??
            'Gagal memuat laporan pengajuan.'
        )
    }
}
</script>
