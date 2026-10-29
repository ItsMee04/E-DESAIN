<template>
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div v-for="stat in stats" :key="stat.title"
            class="p-5 bg-white rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between gap-3">
            <div>
                <p class="text-xs font-medium text-blue-950/60">
                    {{ stat.title }}
                </p>

                <h3 class="text-2xl font-extrabold text-blue-950 mt-1">
                    {{ stat.value }}
                </h3>

                <p class="text-xs text-blue-950/50 mt-1">
                    {{ stat.description }}
                </p>
            </div>

            <div :class="[
                'w-12 h-12 rounded-xl flex items-center justify-center',
                colorClasses[stat.color] || colorClasses.gray,
            ]">
                <component :is="stat.icon" :size="22" />
            </div>
        </div>
    </div>
</template>

<script setup>
import {
    Files,
    Clock,
    CheckCircle2,
    CircleX,
} from 'lucide-vue-next';

const colorClasses = {
    red: 'bg-red-50 text-[#B20600]',
    amber: 'bg-amber-50 text-amber-600',
    emerald: 'bg-emerald-50 text-emerald-600',
    gray: 'bg-gray-100 text-gray-600',
};

defineProps({
    stats: {
        type: Array,
        default: () => [],
    },
});
</script>
