<template>
    <div class="space-y-6">
        <LaporanFilter :statuses="statuses" :is-loading="isLoading" @apply="applyFilter" @reset="resetFilter" />

        <LaporanStatistik v-bind="statistik" />

        <LaporanTable :items="laporan" :is-loading="isLoading" @export-excel="exportExcel" @export-pdf="exportPdf" />
    </div>
</template>

<script setup>
import { ref } from 'vue'
import LaporanFilter from '../components/LaporanFilter.vue'
import LaporanStatistik from '../components/LaporanStatistik.vue'
import LaporanTable from '../components/LaporanTable.vue'

const statuses = ref([])
const isLoading = ref(false)
const laporan = ref([])

const statistik = ref({
    totalPengajuan: 0,
    dalamProses: 0,
    selesai: 0,
    ditolakDibatalkan: 0,
})

const applyFilter = (payload) => {
    console.log('Terapkan filter:', payload)
}

const resetFilter = () => {
    console.log('Reset filter')
}

const exportExcel = () => {
    // Nanti hubungkan ke endpoint ekspor Excel.
}

const exportPdf = () => {
    // Nanti hubungkan ke endpoint ekspor PDF.
}
</script>
