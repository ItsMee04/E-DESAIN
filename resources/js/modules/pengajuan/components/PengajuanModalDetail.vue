<template>
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-blue-950/40 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl w-full max-w-2xl overflow-hidden">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-base font-bold text-blue-950">
                        Detail Pengajuan Desain
                    </h3>

                    <p class="text-[11px] text-blue-950/50 mt-0.5">
                        Informasi lengkap pengajuan desain
                    </p>
                </div>

                <button type="button" @click="$emit('close')"
                    class="text-blue-950/40 hover:text-blue-950 transition cursor-pointer">
                    <X :size="18" />
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-5">

                <!-- Nomor & Status -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <p class="text-[11px] font-medium text-blue-950/40 uppercase tracking-wide">
                            Nomor Pengajuan
                        </p>

                        <p class="text-sm font-semibold text-blue-950 mt-1">
                            {{ item?.nomor ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <span v-if="item?.statuspengajuan"
                            class="inline-flex items-center px-2.5 py-1 rounded-lg border text-[11px] font-semibold"
                            :class="getStatusClass(item.statuspengajuan.key)">
                            {{ item.statuspengajuan.name }}
                        </span>

                        <span v-else class="text-xs text-blue-950/40">
                            -
                        </span>
                    </div>
                </div>

                <!-- Informasi Pengajuan -->
                <div class="rounded-xl border border-gray-100 overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
                        <h4 class="text-xs font-semibold text-blue-950">
                            Informasi Pengajuan
                        </h4>
                    </div>

                    <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">

                        <!-- Pengaju -->
                        <div>
                            <p class="text-[11px] font-medium text-blue-950/40">
                                Yang Mengajukan
                            </p>

                            <p class="text-xs font-medium text-blue-950 mt-1">
                                {{ item?.pegawai?.nama ?? '-' }}
                            </p>
                        </div>

                        <!-- Unit -->
                        <div>
                            <p class="text-[11px] font-medium text-blue-950/40">
                                Unit
                            </p>

                            <p class="text-xs font-medium text-blue-950 mt-1">
                                {{ item?.unit?.unit ?? '-' }}
                            </p>
                        </div>

                        <!-- Nama Desain -->
                        <div class="sm:col-span-2">
                            <p class="text-[11px] font-medium text-blue-950/40">
                                Nama Desain
                            </p>

                            <p class="text-xs font-medium text-blue-950 mt-1">
                                {{ item?.nama_desain ?? '-' }}
                            </p>
                        </div>

                        <!-- Jenis Media -->
                        <div v-if="item?.pengajuanjenismedia?.length" class="flex flex-wrap gap-1.5 mt-1.5">
                            <span v-for="media in item.pengajuanjenismedia" :key="media.id"
                                class="inline-flex items-center px-2 py-1 rounded-md bg-[#B20600]/5 text-[#B20600] text-[11px] font-medium">
                                {{ media.jenis_media?.jenismedia ?? '-' }}
                            </span>
                        </div>

                        <p v-else class="text-xs font-medium text-blue-950 mt-1">
                            -
                        </p>

                        <!-- Ukuran -->
                        <div>
                            <p class="text-[11px] font-medium text-blue-950/40">
                                Ukuran
                            </p>

                            <p class="text-xs font-medium text-blue-950 mt-1">
                                {{ item?.ukuran ?? '-' }}
                            </p>
                        </div>

                        <!-- Jumlah -->
                        <div>
                            <p class="text-[11px] font-medium text-blue-950/40">
                                Jumlah Cetak
                            </p>

                            <p class="text-xs font-medium text-blue-950 mt-1">
                                {{ item?.jumlah ?? 0 }}
                            </p>
                        </div>

                        <!-- Keperluan -->
                        <div class="sm:col-span-2">
                            <p class="text-[11px] font-medium text-blue-950/40">
                                Keperluan
                            </p>

                            <p class="text-xs font-medium text-blue-950 mt-1 whitespace-pre-line leading-relaxed">
                                {{ item?.keperluan ?? '-' }}
                            </p>
                        </div>

                    </div>
                </div>

                <!-- Riwayat -->
                <div v-if="item?.pengajuanhistory?.length" class="rounded-xl border border-gray-100 overflow-hidden">
                    <div class="p-4 space-y-4">
                        <div v-for="(history, index) in item.pengajuanhistory" :key="history.id" class="relative pl-6">
                            <!-- Garis timeline -->
                            <span v-if="index < item.pengajuanhistory.length - 1"
                                class="absolute left-[3px] top-3 bottom-[-16px] w-px bg-gray-200"></span>

                            <!-- Titik timeline -->
                            <span class="absolute left-0 top-1.5 w-2 h-2 rounded-full"
                                :class="getHistoryDotClass(history.status_pengajuan?.key)"></span>

                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs font-semibold"
                                        :class="getHistoryTextClass(history.status_pengajuan?.key)">
                                        {{ history.status_pengajuan?.name ?? '-' }}
                                    </p>

                                    <p v-if="history.catatan" class="text-xs text-gray-600 mt-1">
                                        {{ history.catatan }}
                                    </p>

                                    <p v-if="history.user?.pegawai?.nama" class="text-[11px] text-gray-400 mt-1">
                                        Oleh: {{ history.user.pegawai.nama }}
                                    </p>
                                </div>

                                <p class="text-[10px] text-gray-400 whitespace-nowrap">
                                    {{ formatDate(history.created_at) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end gap-3 p-6 pt-0">
                <button type="button" @click="$emit('close')"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-semibold text-blue-950/70 hover:bg-gray-200 transition cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { X } from 'lucide-vue-next';

defineProps({
    isOpen: {
        type: Boolean,
        default: false
    },

    item: {
        type: Object,
        default: null
    }
});

defineEmits([
    'close'
]);

const getStatusClass = (key) => {
    const statusClasses = {
        diajukan: 'bg-blue-50 text-blue-700 border-blue-200',
        menunggu_validasi: 'bg-amber-50 text-amber-700 border-amber-200',
        diproses: 'bg-indigo-50 text-indigo-700 border-indigo-200',
        revisi: 'bg-orange-50 text-orange-700 border-orange-200',
        selesai: 'bg-emerald-50 text-emerald-700 border-emerald-200',
        ditolak: 'bg-rose-50 text-rose-700 border-rose-200',
        dibatalkan: 'bg-gray-50 text-gray-700 border-gray-200'
    };

    return statusClasses[key] ?? 'bg-gray-50 text-gray-700 border-gray-200';
};

const formatDate = (date) => {
    if (!date) {
        return '-';
    }

    return new Date(date).toLocaleString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const getHistoryDotClass = (key) => {
    const classes = {
        diajukan: "bg-green-500",
        menunggu_validasi: "bg-amber-500",
        diproses: "bg-indigo-500",
        revisi: "bg-orange-500",
        selesai: "bg-green-600",
        ditolak: "bg-red-500",
        dibatalkan: "bg-gray-400",
    };

    return classes[key] || "bg-gray-300";
};

const getHistoryTextClass = (key) => {
    const classes = {
        diajukan: "text-green-600",
        menunggu_validasi: "text-amber-600",
        diproses: "text-indigo-600",
        revisi: "text-orange-600",
        selesai: "text-green-700",
        ditolak: "text-red-600",
        dibatalkan: "text-gray-500",
    };

    return classes[key] || "text-blue-950";
};
</script>
