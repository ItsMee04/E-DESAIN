<template>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h3 class="text-lg font-bold text-blue-950">
                    Pengajuan Terbaru
                </h3>
                <p class="text-xs text-blue-950/60">
                    Lima pengajuan desain terbaru dari berbagai unit
                </p>
            </div>

            <button type="button" @click="$emit('view-all')"
                class="text-xs font-semibold text-[#B20600] hover:underline cursor-pointer">
                Lihat Semua
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-blue-950">
                <thead class="bg-gray-50 text-xs font-semibold text-blue-950/60 uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 rounded-l-xl">Nama Desain</th>
                        <th class="px-4 py-3">Unit</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 rounded-r-xl text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    <tr v-for="item in requests.slice(0, 5)" :key="item.id" class="hover:bg-gray-50/50 transition">
                        <td class="px-4 py-3.5">
                            <p class="font-semibold text-blue-950">
                                {{ item.nama_desain }}
                            </p>
                            <p class="text-xs text-blue-950/50 mt-1">
                                {{ item.nomor }}
                            </p>
                        </td>

                        <td class="px-4 py-3.5 text-blue-950/70">
                            {{ item.unit }}
                        </td>

                        <td class="px-4 py-3.5">
                            <span :class="[
                                getStatusClass(item.status_key),
                                'inline-flex whitespace-nowrap px-2.5 py-1 rounded-lg text-xs font-semibold border',
                            ]">
                                {{ item.status }}
                            </span>
                        </td>

                        <td class="px-4 py-3.5 text-right">
                            <button type="button" @click="$emit('detail', item)"
                                class="text-xs font-semibold text-[#B20600] hover:underline cursor-pointer">
                                Detail
                            </button>
                        </td>
                    </tr>

                    <tr v-if="requests.length === 0">
                        <td colspan="4" class="px-4 py-10 text-center text-sm text-blue-950/50">
                            Belum ada pengajuan desain.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
defineProps({
    requests: {
        type: Array,
        default: () => [],
    },
});

defineEmits(['view-all', 'detail']);

const getStatusClass = (key) => {
    const classes = {
        diajukan: 'bg-blue-50 text-blue-700 border-blue-200',
        menunggu_verifikasi_it: 'bg-amber-50 text-amber-700 border-amber-200',
        menunggu_acc_pkrs: 'bg-amber-50 text-amber-700 border-amber-200',
        diproses: 'bg-indigo-50 text-indigo-700 border-indigo-200',
        siap_diambil: 'bg-violet-50 text-violet-700 border-violet-200',
        selesai: 'bg-emerald-50 text-emerald-700 border-emerald-200',
        ditolak: 'bg-rose-50 text-rose-700 border-rose-200',
        dibatalkan: 'bg-gray-50 text-gray-600 border-gray-200',
    };

    return classes[key] ?? 'bg-gray-50 text-gray-600 border-gray-200';
};
</script>
