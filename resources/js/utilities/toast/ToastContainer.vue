<template>
    <!-- Fixed Container di Kanan Atas Screen -->
    <div class="fixed top-5 right-5 z-50 flex flex-col gap-3 max-w-sm w-full pointer-events-none">
        <TransitionGroup enter-active-class="transform transition duration-300 ease-out"
            enter-from-class="translate-x-full opacity-0" enter-to-class="translate-x-0 opacity-100"
            leave-active-class="transform transition duration-200 ease-in" leave-from-class="translate-x-0 opacity-100"
            leave-to-class="translate-x-full opacity-0">
            <div v-for="item in toasts" :key="item.id" :class="[
                toastStyles[item.type] || toastStyles.info,
                'flex items-center justify-between gap-3 p-4 rounded-xl shadow-lg border pointer-events-auto backdrop-blur-md transition-all'
            ]" role="alert">
                <div class="flex items-center gap-3">
                    <!-- Icon Berdasarkan Tipe -->
                    <CheckCircle2 v-if="item.type === 'success'" :size="20" class="shrink-0 text-emerald-600" />
                    <AlertCircle v-else-if="item.type === 'error'" :size="20" class="shrink-0 text-rose-600" />
                    <AlertTriangle v-else-if="item.type === 'warning'" :size="20" class="shrink-0 text-amber-600" />
                    <Info v-else :size="20" class="shrink-0 text-blue-600" />

                    <p class="text-xs font-semibold text-gray-800 leading-snug">
                        {{ item.message }}
                    </p>
                </div>

                <!-- Tombol Close -->
                <button @click="removeToast(item.id)"
                    class="text-gray-400 hover:text-gray-600 p-1 rounded-lg transition shrink-0 cursor-pointer">
                    <X :size="16" />
                </button>
            </div>
        </TransitionGroup>
    </div>
</template>

<script setup>
import { useToast } from './useToast';
import { CheckCircle2, AlertCircle, AlertTriangle, Info, X } from 'lucide-vue-next';

const { toasts, removeToast } = useToast();

// Variant Warna Toast Tailwind
const toastStyles = {
    success: 'bg-emerald-50/95 border-emerald-200 text-emerald-900',
    error: 'bg-rose-50/95 border-rose-200 text-rose-900',
    warning: 'bg-amber-50/95 border-amber-200 text-amber-900',
    info: 'bg-blue-50/95 border-blue-200 text-blue-900'
};
</script>
