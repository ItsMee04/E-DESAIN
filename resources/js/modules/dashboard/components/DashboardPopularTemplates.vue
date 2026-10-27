<template>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
        <div>
            <h3 class="text-lg font-bold text-blue-950">
                Template Populer
            </h3>
            <p class="text-xs text-blue-950/60">
                Referensi ukuran media desain rumah sakit
            </p>
        </div>

        <div class="space-y-3">
            <div v-for="item in templates" :key="item.name"
                class="p-3.5 rounded-xl border border-gray-100 hover:border-[#B20600]/30 hover:shadow-xs transition flex items-center justify-between group">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-blue-950 group-hover:bg-[#B20600] group-hover:text-white transition">
                        <component :is="item.icon" :size="20" />
                    </div>

                    <div>
                        <h4 class="text-sm font-semibold text-blue-950">
                            {{ item.name }}
                        </h4>
                        <span class="text-xs text-blue-950/50">
                            {{ item.size }}
                        </span>
                    </div>
                </div>

                <ArrowRight :size="18"
                    class="text-blue-950/30 group-hover:text-[#B20600] group-hover:translate-x-1 transition-all" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ArrowRight, Image, FileText, Tv } from 'lucide-vue-next';

const templates = [
    {
        name: 'Banner Media Sosial',
        size: '1080 × 1080 px',
        icon: Image,
    },
    {
        name: 'Poster Layanan',
        size: '210 × 297 mm',
        icon: FileText,
    },
    {
        name: 'Display TV Poliklinik',
        size: '1920 × 1080 px',
        icon: Tv,
    },
];
</script>
