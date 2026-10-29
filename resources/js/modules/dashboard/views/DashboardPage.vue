<template>
    <div class="space-y-6">
        <!-- Banner Selamat Datang -->
        <DashboardWelcome @create="handleCreateRequest" />

        <!-- Kartu Statistik -->
        <DashboardStats :stats="stats" :is-loading="isLoading" />

        <DashboardRecentRequests :requests="requests" :is-loading="isLoadingRequests" @view-all="handleViewAll"
            @detail="handleDetail" />

        <PengajuanModalDetail :is-open="isDetailModalOpen" :item="selectedDetailItem" @close="closeDetailModal" />
        <PengajuanModal :is-open="isModalOpen" :is-editing="isEditing" :form="form" :errors="errors"
            :is-submitting="isSubmitting" :units="units" :jenis-media="jenisMedia" @close="closeModal" @save="saveForm"
            @validate-pengajuan="validatePengajuan" />

        <!-- Dua Grafik Statistik -->
        <DashboardStatistics :monthly-data="monthlyData" :status-data="statusData" />
    </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { useRouter } from 'vue-router';

import DashboardWelcome from '../components/DashboardWelcome.vue';
import DashboardStats from '../components/DashboardStats.vue';
import DashboardRecentRequests from '../components/DashboardRecentRequests.vue';
import DashboardStatistics from '../components/DashboardStatistics.vue';

import PengajuanModalDetail from '../../pengajuan/components/PengajuanModalDetail.vue';
import PengajuanModal from '../../pengajuan/components/PengajuanModal.vue';

import { pengajuanService } from '../../pengajuan/services/pengajuanService.js';

import { useDashboard } from '../composables/useDashboard.js';
import { usePengajuan } from '../../pengajuan/composables/usePengajuan.js';

import {
    Files,
    Clock,
    CheckCircle2,
    CircleX,
} from 'lucide-vue-next';

const {
    units,
    jenisMedia,
    isModalOpen,
    isEditing,
    form,
    errors,
    isSubmitting,
    openAddModal,
    closeModal,
    saveForm,
    validatePengajuan,
} = usePengajuan();

const router = useRouter();

const isDetailModalOpen = ref(false);
const selectedDetailItem = ref(null);
const isLoadingDetail = ref(false);

const {
    dashboard,
    isLoading,
    requests,
    isLoadingRequests,
    monthlyData,
    statusData,
} = useDashboard();

// Statistik kartu dashboard
const stats = computed(() => [
    {
        title: 'Total Pengajuan',
        value: dashboard.value.total_pengajuan,
        description: 'Seluruh pengajuan tercatat',
        icon: Files,
        color: 'red',
    },
    {
        title: 'Pengajuan Aktif',
        value: dashboard.value.total_pengajuan_aktif,
        description: 'Masih dalam proses',
        icon: Clock,
        color: 'amber',
    },
    {
        title: 'Pengajuan Selesai',
        value: dashboard.value.total_pengajuan_selesai,
        description: 'Sudah diselesaikan',
        icon: CheckCircle2,
        color: 'emerald',
    },
    {
        title: 'Ditolak / Dibatalkan',
        value: dashboard.value.total_pengajuan_dibatalkan,
        description: 'Pengajuan berakhir',
        icon: CircleX,
        color: 'gray',
    },
]);

// Navigasi ke formulir pengajuan
const handleCreateRequest = () => {
    openAddModal();
};

// Navigasi ke seluruh daftar pengajuan
const handleViewAll = () => {
    router.push('/pengajuan/pengajuandesain');
};

// Mengambil detail pengajuan
const handleDetail = async (item) => {
    if (!item?.id) return;

    isDetailModalOpen.value = true;
    isLoadingDetail.value = true;
    selectedDetailItem.value = item;

    try {
        const response = await pengajuanService.getPengajuanById({
            id: item.id,
        });

        selectedDetailItem.value = response?.data ?? response ?? item;
    } catch (error) {
        console.error('Gagal mengambil detail pengajuan:', error);
    } finally {
        isLoadingDetail.value = false;
    }
};

const closeDetailModal = () => {
    isDetailModalOpen.value = false;
    selectedDetailItem.value = null;
};
</script>
