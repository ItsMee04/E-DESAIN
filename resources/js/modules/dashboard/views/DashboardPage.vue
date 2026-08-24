<template>
    <div class="space-y-6">
        <!-- Banner Welcoming -->
        <div
            class="p-6 lg:p-8 bg-white rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-semibold uppercase tracking-widest text-[#B20600]">RSU Wiradadi Husada</span>
                <h2 class="text-2xl lg:text-3xl font-extrabold tracking-tight text-blue-950 mt-1">Selamat Datang, Tim IT
                    👋</h2>
                <p class="text-sm text-blue-950/70 mt-1 max-w-xl">
                    Kelola template visual, pantau permintaan desain dari unit rumah sakit, dan buat materi banner baru
                    di sini.
                </p>
            </div>
            <button
                class="py-3.5 px-6 bg-[#B20600] hover:bg-[#8D0500] text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2.5 cursor-pointer shrink-0">
                <Plus :size="20" />
                <span>Buat Desain Baru</span>
            </button>
        </div>

        <!-- Stats Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div v-for="stat in stats" :key="stat.title"
                class="p-5 bg-white rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-blue-950/60">{{ stat.title }}</p>
                    <h3 class="text-2xl font-extrabold text-blue-950 mt-1">{{ stat.value }}</h3>
                    <span class="text-xs font-semibold text-emerald-600 flex items-center gap-1 mt-1">
                        <TrendingUp :size="14" /> {{ stat.change }}
                    </span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-[#B20600]/10 flex items-center justify-center text-[#B20600]">
                    <component :is="stat.icon" :size="24" />
                </div>
            </div>
        </div>

        <!-- Section Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Tabel Permintaan Desain Terbaru -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-blue-950">Permintaan Desain Terbaru</h3>
                        <p class="text-xs text-blue-950/60">Pengajuan dari berbagai unit & departemen</p>
                    </div>
                    <a href="#" class="text-xs font-semibold text-[#B20600] hover:underline">Lihat Semua</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-blue-950">
                        <thead class="bg-gray-50 text-xs font-semibold text-blue-950/60 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3 rounded-l-xl">Judul Desain</th>
                                <th class="px-4 py-3">Unit / Pengaju</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 rounded-r-xl text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="item in requests" :key="item.id" class="hover:bg-gray-50/50 transition">
                                <td class="px-4 py-3.5 font-medium text-blue-950">{{ item.title }}</td>
                                <td class="px-4 py-3.5 text-blue-950/70">{{ item.unit }}</td>
                                <td class="px-4 py-3.5">
                                    <span :class="[
                                        item.status === 'Selesai' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' :
                                            item.status === 'Proses' ? 'bg-blue-50 text-blue-700 border-blue-200' :
                                                'bg-amber-50 text-amber-700 border-amber-200',
                                        'px-2.5 py-1 rounded-lg text-xs font-semibold border'
                                    ]">
                                        {{ item.status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-right">
                                    <button class="text-blue-950/50 hover:text-[#B20600] transition cursor-pointer p-1">
                                        <MoreVertical :size="18" />
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Template Cepat -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                <div>
                    <h3 class="text-lg font-bold text-blue-950">Template Populer</h3>
                    <p class="text-xs text-blue-950/60">Gunakan preset standar RSU Wiradadi</p>
                </div>

                <div class="space-y-3">
                    <div v-for="template in templates" :key="template.name"
                        class="p-3.5 rounded-xl border border-gray-100 hover:border-[#B20600]/30 hover:shadow-xs transition flex items-center justify-between group cursor-pointer">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-blue-950 group-hover:bg-[#B20600] group-hover:text-white transition">
                                <component :is="template.icon" :size="20" />
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-blue-950">{{ template.name }}</h4>
                                <span class="text-xs text-blue-950/50">{{ template.size }}</span>
                            </div>
                        </div>
                        <ArrowRight :size="18"
                            class="text-blue-950/30 group-hover:text-[#B20600] group-hover:translate-x-1 transition-all" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {
    Plus,
    TrendingUp,
    Palette,
    Clock,
    CheckCircle2,
    FolderKanban,
    MoreVertical,
    ArrowRight,
    FileText,
    Image,
    Tv
} from 'lucide-vue-next';

const stats = [
    { title: 'Total Desain', value: '128', change: '+12%', icon: Palette },
    { title: 'Dalam Antrean', value: '14', change: '+5%', icon: Clock },
    { title: 'Selesai Bulan Ini', value: '45', change: '+18%', icon: CheckCircle2 },
    { title: 'Total Proyek', value: '8', change: '+2', icon: FolderKanban }
];

const requests = [
    { id: 1, title: 'Banner Edukasi DBD', unit: 'Unit Promkes', status: 'Proses' },
    { id: 2, title: 'Poster Jadwal Dokter Spesialis', unit: 'Pendaftaran', status: 'Selesai' },
    { id: 3, title: 'Desain Banner Poli Eksekutif', unit: 'Humas & Pemasaran', status: 'Pending' },
    { id: 4, title: 'Feeds IG Hari Kesehatan', unit: 'Promkes', status: 'Selesai' }
];

const templates = [
    { name: 'Banner IG Feed (1:1)', size: '1080 x 1080 px', icon: Image },
    { name: 'Poster A4 Layanan', size: '210 x 297 mm', icon: FileText },
    { name: 'Display TV Poliklinik', size: '1920 x 1080 px', icon: Tv }
];
</script>
