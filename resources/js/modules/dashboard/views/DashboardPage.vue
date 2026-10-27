<template>
    <div class="space-y-6">
        <!-- Banner Selamat Datang -->
        <DashboardWelcome @create="handleCreateRequest" />

        <!-- Kartu Statistik -->
        <DashboardStats :stats="stats" :is-loading="isLoading" />

        <!-- Pengajuan Terbaru -->
        <DashboardRecentRequests :requests="requests" @view-all="handleViewAll" @detail="handleDetail" />

        <!-- Dua Grafik Statistik -->
        <DashboardStatistics :monthly-data="monthlyData" :status-data="statusData" />
    </div>
</template>

<script setup>
import { computed } from 'vue';

import DashboardWelcome from '../components/DashboardWelcome.vue';
import DashboardStats from '../components/DashboardStats.vue';
import DashboardRecentRequests from '../components/DashboardRecentRequests.vue';
import DashboardStatistics from '../components/DashboardStatistics.vue';

import { useDashboard } from '../composables/useDashboard.js';

import {
    Files,
    Clock,
    CheckCircle2,
    CircleX,
} from 'lucide-vue-next';

// Data statistik dari API
const {
    dashboard,
    isLoading,
    fetchData,
} = useDashboard();

// Menyesuaikan data API dengan kebutuhan DashboardStats.vue
const stats = computed(() => [
    {
        title: 'Total Pengajuan',
        value: dashboard.value.total_pengajuan,
        description: 'Seluruh pengajuan tercatat',
        icon: Files,
    },
    {
        title: 'Pengajuan Aktif',
        value: dashboard.value.total_pengajuan_aktif,
        description: 'Masih dalam proses',
        icon: Clock,
    },
    {
        title: 'Pengajuan Selesai',
        value: dashboard.value.total_pengajuan_selesai,
        description: 'Sudah diselesaikan',
        icon: CheckCircle2,
    },
    {
        title: 'Ditolak / Dibatalkan',
        value: 0,
        description: 'Pengajuan berakhir',
        icon: CircleX,
    },
]);

// Lima pengajuan terbaru sementara
const requests = [
    {
        id: 1,
        nomor: 'PD-20261025-0001',
        nama_desain: 'Banner Edukasi DBD',
        unit: 'Promkes',
        status: 'Diproses',
        status_key: 'diproses',
    },
    {
        id: 2,
        nomor: 'PD-20261024-0002',
        nama_desain: 'Poster Jadwal Dokter',
        unit: 'Pendaftaran',
        status: 'Selesai',
        status_key: 'selesai',
    },
    {
        id: 3,
        nomor: 'PD-20261023-0003',
        nama_desain: 'Banner Poli Eksekutif',
        unit: 'Humas',
        status: 'Menunggu Verifikasi IT',
        status_key: 'menunggu_verifikasi_it',
    },
    {
        id: 4,
        nomor: 'PD-20261022-0004',
        nama_desain: 'Stiker Logo RSU',
        unit: 'Instalasi IT',
        status: 'Siap Diambil',
        status_key: 'siap_diambil',
    },
    {
        id: 5,
        nomor: 'PD-20261021-0005',
        nama_desain: 'Poster Kesehatan',
        unit: 'Rawat Jalan',
        status: 'Menunggu ACC PKRS',
        status_key: 'menunggu_acc_pkrs',
    },
];

// Data grafik sementara
const monthlyData = [
    { bulan: 'Mei', total: 12 },
    { bulan: 'Jun', total: 18 },
    { bulan: 'Jul', total: 15 },
    { bulan: 'Agu', total: 24 },
    { bulan: 'Sep', total: 27 },
    { bulan: 'Okt', total: 32 },
];

const statusData = [
    { label: 'Diajukan', total: 4 },
    { label: 'Menunggu Verifikasi IT', total: 5 },
    { label: 'Menunggu ACC PKRS', total: 5 },
    { label: 'Diproses', total: 8 },
    { label: 'Siap Diambil', total: 6 },
    { label: 'Selesai', total: 90 },
    { label: 'Ditolak', total: 6 },
    { label: 'Dibatalkan', total: 4 },
];

// Handler navigasi sementara
const handleCreateRequest = () => {
    // Hubungkan ke halaman atau modal formulir pengajuan.
};

const handleViewAll = () => {
    // Hubungkan ke halaman daftar pengajuan.
};

const handleDetail = (item) => {
    // Hubungkan ke detail pengajuan berdasarkan item.id.
};
</script>
