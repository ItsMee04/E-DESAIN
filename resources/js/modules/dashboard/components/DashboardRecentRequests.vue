<script setup>
import { computed } from 'vue';
import {
    ClipboardList,
    ArrowRight,
    Eye,
    CalendarDays,
    Building2,
    RefreshCw,
    FileSearch,
} from 'lucide-vue-next';

const props = defineProps({
    requests: {
        type: Array,
        default: () => [],
    },
    isLoading: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['view-all', 'detail']);

const recentRequests = computed(() => props.requests.slice(0, 5));

const statusClasses = {
    diajukan: 'bg-blue-50 text-blue-700 border-blue-200',
    menunggu_verifikasi_it: 'bg-amber-50 text-amber-700 border-amber-200',
    menunggu_acc_pkrs: 'bg-amber-50 text-amber-700 border-amber-200',
    diproses: 'bg-indigo-50 text-indigo-700 border-indigo-200',
    siap_diambil: 'bg-purple-50 text-purple-700 border-purple-200',
    selesai: 'bg-emerald-50 text-emerald-700 border-emerald-200',
    ditolak: 'bg-rose-50 text-rose-700 border-rose-200',
    dibatalkan: 'bg-gray-100 text-gray-600 border-gray-200',
};

const getStatusClass = (key) => {
    return statusClasses[key] || 'bg-gray-100 text-gray-600 border-gray-200';
};

const formatDate = (date) => {
    if (!date) return '-';

    const parsedDate = new Date(date);

    if (Number.isNaN(parsedDate.getTime())) return '-';

    return new Intl.DateTimeFormat('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    }).format(parsedDate);
};

const getRequestDate = (item) => {
    return formatDate(item.created_at || item.updated_at);
};

const handleDetail = (item) => {
    emit('detail', item);
};
</script>

<template>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3
                   px-5 sm:px-6 py-5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-red-50 text-[#B20600]
                           flex items-center justify-center shrink-0">
                    <ClipboardList :size="22" />
                </div>

                <div>
                    <h3 class="text-base font-bold text-gray-900">
                        Pengajuan Terbaru
                    </h3>
                    <p class="text-sm text-gray-500 mt-0.5">
                        Daftar pengajuan desain terbaru
                    </p>
                </div>
            </div>

            <button type="button" @click="emit('view-all')" class="inline-flex items-center justify-center gap-2
                       text-sm font-semibold text-[#B20600]
                       hover:text-[#8E0500] transition-colors">
                Lihat Semua
                <ArrowRight :size="16" />
            </button>
        </div>

        <!-- Loading -->
        <div v-if="isLoading" class="flex flex-col items-center justify-center py-14 px-4">
            <RefreshCw :size="26" class="text-[#B20600] animate-spin mb-3" />
            <p class="text-sm font-medium text-gray-600">
                Memuat data pengajuan...
            </p>
            <p class="text-xs text-gray-400 mt-1">
                Mohon tunggu sebentar
            </p>
        </div>

        <!-- Empty State -->
        <div v-else-if="recentRequests.length === 0" class="flex flex-col items-center justify-center py-14 px-4">
            <div class="w-14 h-14 rounded-2xl bg-gray-50 text-gray-400
                       flex items-center justify-center mb-3">
                <FileSearch :size="28" />
            </div>

            <p class="text-sm font-semibold text-gray-700">
                Belum ada pengajuan
            </p>
            <p class="text-xs text-gray-500 text-center mt-1">
                Pengajuan desain terbaru akan ditampilkan di sini.
            </p>
        </div>

        <!-- Desktop Table -->
        <div v-else class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
                        <th class="px-6 py-3.5 text-xs font-semibold
                                   text-gray-500 uppercase tracking-wider">
                            Pengajuan
                        </th>
                        <th class="px-6 py-3.5 text-xs font-semibold
                                   text-gray-500 uppercase tracking-wider">
                            Unit
                        </th>
                        <th class="px-6 py-3.5 text-xs font-semibold
                                   text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th class="px-6 py-3.5 text-xs font-semibold
                                   text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3.5 text-xs font-semibold
                                   text-gray-500 uppercase tracking-wider text-right">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    <tr v-for="item in recentRequests" :key="item.id" class="hover:bg-gray-50/70 transition-colors">
                        <!-- Informasi Pengajuan -->
                        <td class="px-6 py-4">
                            <div class="min-w-[190px]">
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ item.nama_desain || '-' }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ item.nomor || '-' }}
                                </p>
                            </div>
                        </td>

                        <!-- Unit -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 min-w-[120px]">
                                <Building2 :size="15" class="text-gray-400 shrink-0" />
                                <span class="text-sm text-gray-600">
                                    {{ item.unit || '-' }}
                                </span>
                            </div>
                        </td>

                        <!-- Tanggal -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 min-w-[110px]">
                                <CalendarDays :size="15" class="text-gray-400 shrink-0" />
                                <span class="text-sm text-gray-600">
                                    {{ item.tanggal || '-' }}
                                </span>
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4">
                            <span :class="[
                                'inline-flex items-center px-2.5 py-1 rounded-lg border text-xs font-semibold whitespace-nowrap',
                                getStatusClass(item.status_key),
                            ]">
                                {{ item.status || '-' }}
                            </span>
                        </td>

                        <!-- Aksi -->
                        <td class="px-6 py-4 text-right">
                            <button @click="handleDetail(item)"
                                class="p-1.5 text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 rounded-lg border border-blue-200/60 transition cursor-pointer"
                                title="Detail Pengajuan">
                                <Eye :size="16" />
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div v-if="!isLoading && recentRequests.length > 0" class="px-5 sm:px-6 py-3.5 border-t border-gray-100
                   flex items-center justify-between gap-3">
            <p class="text-xs text-gray-500">
                Menampilkan
                <span class="font-semibold text-gray-700">
                    {{ recentRequests.length }}
                </span>
                pengajuan terbaru
            </p>
        </div>
    </div>
</template>
