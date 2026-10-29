<script setup>
import {
    Files,
    Clock3,
    CheckCircle2,
    CircleX,
} from 'lucide-vue-next'

const props = defineProps({
    totalPengajuan: {
        type: Number,
        default: 0,
    },
    dalamProses: {
        type: Number,
        default: 0,
    },
    selesai: {
        type: Number,
        default: 0,
    },
    ditolakDibatalkan: {
        type: Number,
        default: 0,
    },
})
</script>

<template>
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <!-- Total Pengajuan -->
        <div class="bg-white rounded-2xl border border-gray-100
                   shadow-sm p-5 transition hover:shadow-md">
            <div class="flex items-center justify-between gap-3">
                <div class="min-w-0 space-y-2">
                    <p class="text-xs font-medium text-blue-950/60">
                        Total Pengajuan
                    </p>

                    <p class="text-2xl font-bold text-blue-950">
                        {{ totalPengajuan.toLocaleString('id-ID') }}
                    </p>
                </div>

                <div class="flex items-center justify-center shrink-0
                           w-11 h-11 rounded-xl
                           bg-blue-50 text-blue-600">
                    <Files :size="21" />
                </div>
            </div>

            <p class="mt-3 text-xs text-blue-950/50">
                Total pengajuan pada periode terpilih
            </p>
        </div>

        <!-- Dalam Proses -->
        <div class="bg-white rounded-2xl border border-gray-100
                   shadow-sm p-5 transition hover:shadow-md">
            <div class="flex items-center justify-between gap-3">
                <div class="min-w-0 space-y-2">
                    <p class="text-xs font-medium text-blue-950/60">
                        Dalam Proses
                    </p>

                    <p class="text-2xl font-bold text-blue-950">
                        {{ dalamProses.toLocaleString('id-ID') }}
                    </p>
                </div>

                <div class="flex items-center justify-center shrink-0
                           w-11 h-11 rounded-xl
                           bg-amber-50 text-amber-600">
                    <Clock3 :size="21" />
                </div>
            </div>

            <p class="mt-3 text-xs text-blue-950/50">
                Pengajuan yang belum selesai
            </p>
        </div>

        <!-- Selesai -->
        <div class="bg-white rounded-2xl border border-gray-100
                   shadow-sm p-5 transition hover:shadow-md">
            <div class="flex items-center justify-between gap-3">
                <div class="min-w-0 space-y-2">
                    <p class="text-xs font-medium text-blue-950/60">
                        Selesai
                    </p>

                    <p class="text-2xl font-bold text-blue-950">
                        {{ selesai.toLocaleString('id-ID') }}
                    </p>
                </div>

                <div class="flex items-center justify-center shrink-0
                           w-11 h-11 rounded-xl
                           bg-emerald-50 text-emerald-600">
                    <CheckCircle2 :size="21" />
                </div>
            </div>

            <p class="mt-3 text-xs text-blue-950/50">
                Pengajuan yang telah selesai
            </p>
        </div>

        <!-- Ditolak / Dibatalkan -->
        <div class="bg-white rounded-2xl border border-gray-100
                   shadow-sm p-5 transition hover:shadow-md">
            <div class="flex items-center justify-between gap-3">
                <div class="min-w-0 space-y-2">
                    <p class="text-xs font-medium text-blue-950/60">
                        Ditolak / Dibatalkan
                    </p>

                    <p class="text-2xl font-bold text-blue-950">
                        {{ ditolakDibatalkan.toLocaleString('id-ID') }}
                    </p>
                </div>

                <div class="flex items-center justify-center shrink-0
                           w-11 h-11 rounded-xl
                           bg-rose-50 text-rose-600">
                    <CircleX :size="21" />
                </div>
            </div>

            <p class="mt-3 text-xs text-blue-950/50">
                Total pengajuan ditolak dan dibatalkan
            </p>
        </div>
    </div>
</template>
