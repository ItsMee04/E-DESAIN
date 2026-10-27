<template>
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <!-- Grafik Pengajuan Per Bulan -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-blue-950">
                    Statistik Pengajuan
                </h3>
                <p class="text-xs text-blue-950/60 mt-1">
                    Jumlah pengajuan desain setiap bulan
                </p>
            </div>

            <div class="h-72">
                <Bar :data="monthlyChartData" :options="barOptions" />
            </div>
        </div>

        <!-- Grafik Distribusi Status -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-blue-950">
                    Distribusi Status
                </h3>
                <p class="text-xs text-blue-950/60 mt-1">
                    Ringkasan status seluruh pengajuan
                </p>
            </div>

            <div class="h-72 flex items-center justify-center">
                <Doughnut :data="statusChartData" :options="doughnutOptions" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { Bar, Doughnut } from 'vue-chartjs';
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    BarElement,
    ArcElement,
    Tooltip,
    Legend,
} from 'chart.js';

ChartJS.register(
    CategoryScale,
    LinearScale,
    BarElement,
    ArcElement,
    Tooltip,
    Legend,
);

const props = defineProps({
    monthlyData: {
        type: Array,
        default: () => [],
    },
    statusData: {
        type: Array,
        default: () => [],
    },
});

const monthlyChartData = computed(() => ({
    labels: props.monthlyData.map((item) => item.bulan),
    datasets: [
        {
            label: 'Jumlah Pengajuan',
            data: props.monthlyData.map((item) => item.total),
            backgroundColor: '#B20600',
            hoverBackgroundColor: '#8D0500',
            borderRadius: 6,
            maxBarThickness: 36,
        },
    ],
}));

const statusChartData = computed(() => ({
    labels: props.statusData.map((item) => item.label),
    datasets: [
        {
            data: props.statusData.map((item) => item.total),
            backgroundColor: [
                '#2563EB',
                '#F59E0B',
                '#6366F1',
                '#8B5CF6',
                '#10B981',
                '#E11D48',
                '#9CA3AF',
            ],
            borderWidth: 2,
            borderColor: '#FFFFFF',
            hoverOffset: 6,
        },
    ],
}));

const barOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false,
        },
    },
    scales: {
        y: {
            beginAtZero: true,
            ticks: {
                precision: 0,
            },
            grid: {
                color: '#F3F4F6',
            },
        },
        x: {
            grid: {
                display: false,
            },
        },
    },
};

const doughnutOptions = {
    responsive: true,
    maintainAspectRatio: false,
    cutout: '68%',
    plugins: {
        legend: {
            position: 'bottom',
            labels: {
                usePointStyle: true,
                pointStyle: 'circle',
                padding: 18,
                boxWidth: 8,
            },
        },
    },
};
</script>
