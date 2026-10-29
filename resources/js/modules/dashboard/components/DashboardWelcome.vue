<template>
    <div
        class="p-6 lg:p-8 bg-white rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <span class="text-xs font-semibold uppercase tracking-widest text-[#B20600]">
                RSU Wiradadi Husada
            </span>

            <h2 class="text-2xl lg:text-3xl font-bold tracking-tight text-blue-950 mt-1">
                Selamat Datang,
                <span class="text-red-700">{{ userName }}</span> 👋
            </h2>

            <p class="text-sm text-blue-950/70 mt-1 max-w-xl">
                Pantau pengajuan desain dari berbagai unit rumah sakit dan
                lihat perkembangan prosesnya melalui E-Desain.
            </p>
        </div>

        <button type="button" @click="$emit('create')"
            class="py-3.5 px-6 bg-[#B20600] hover:bg-[#8D0500] text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2.5 cursor-pointer shrink-0">
            <Plus :size="20" />
            <span>Buat Pengajuan</span>
        </button>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { Plus } from 'lucide-vue-next';

defineEmits(['create']);

const userName = computed(() => {
    try {
        const user = JSON.parse(localStorage.getItem('user'));

        return user?.pegawai?.nama || 'Pengguna';
    } catch (error) {
        console.error('Gagal mengambil nama user:', error);
        return 'Pengguna';
    }
});
</script>
