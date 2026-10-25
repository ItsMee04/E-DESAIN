```vue
<template>
    <Teleport to="body">
        <div v-if="isOpen"
            class="fixed inset-0 z-50 flex items-center justify-center bg-blue-950/40 backdrop-blur-xs p-4">
            <div
                class="bg-white rounded-2xl border border-gray-100 shadow-xl w-full max-w-sm overflow-hidden p-6 text-center space-y-4">
                <!-- Icon Konfirmasi -->
                <div class="w-12 h-12 rounded-full border flex items-center justify-center mx-auto" :class="iconClass">
                    <component :is="currentIcon" :size="24" />
                </div>

                <!-- Informasi Konfirmasi -->
                <div class="space-y-1">
                    <h3 class="text-base font-bold text-blue-950">
                        {{ title }}
                    </h3>

                    <p class="text-xs text-blue-950/60 leading-relaxed">
                        {{ description }}
                    </p>

                    <p v-if="itemName" class="text-sm font-semibold text-blue-950 pt-1">
                        "{{ itemName }}"
                    </p>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex items-center justify-center gap-3 pt-2">
                    <button type="button" @click="$emit('close')" :disabled="isProcessing"
                        class="w-full py-2.5 rounded-xl border border-gray-200 text-xs font-semibold text-blue-950/70 hover:bg-gray-50 transition cursor-pointer disabled:opacity-50">
                        Batal
                    </button>

                    <button type="button" @click="$emit('confirm')" :disabled="isProcessing"
                        class="w-full py-2.5 rounded-xl text-white text-xs font-semibold transition cursor-pointer shadow-sm flex items-center justify-center gap-2 disabled:opacity-50"
                        :class="buttonClass">
                        <span v-if="isProcessing"
                            class="w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>

                        <span>
                            {{
                                isProcessing
                                    ? processingText
                                    : confirmText
                            }}
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script setup>
import { computed } from 'vue';
import { PackageCheck, CheckCircle2 } from 'lucide-vue-next';

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false,
    },
    type: {
        type: String,
        default: 'ready',
        validator: (value) => ['ready', 'complete'].includes(value),
    },
    itemName: {
        type: String,
        default: '',
    },
    isProcessing: {
        type: Boolean,
        default: false,
    },
});

defineEmits(['close', 'confirm']);

const config = {
    ready: {
        title: 'Konfirmasi Siap Diambil',
        description:
            'Apakah desain ini sudah selesai dikerjakan dan siap diambil oleh pemohon?',
        confirmText: 'Ya, Siap Diambil',
        processingText: 'Memproses...',
        icon: PackageCheck,
        iconClass: 'bg-orange-50 border-orange-100 text-orange-600',
        buttonClass: 'bg-orange-600 hover:bg-orange-700',
    },
    complete: {
        title: 'Konfirmasi Selesai Diambil',
        description:
            'Pastikan desain sudah diserahkan dan diterima oleh pemohon. Apakah Anda yakin ingin menyelesaikan pengajuan ini?',
        confirmText: 'Ya, Selesaikan',
        processingText: 'Menyelesaikan...',
        icon: CheckCircle2,
        iconClass: 'bg-emerald-50 border-emerald-100 text-emerald-600',
        buttonClass: 'bg-emerald-600 hover:bg-emerald-700',
    },
};

const currentConfig = computed(() => config[props.type]);

const title = computed(() => currentConfig.value.title);
const description = computed(() => currentConfig.value.description);
const confirmText = computed(() => currentConfig.value.confirmText);
const processingText = computed(() => currentConfig.value.processingText);
const currentIcon = computed(() => currentConfig.value.icon);
const iconClass = computed(() => currentConfig.value.iconClass);
const buttonClass = computed(() => currentConfig.value.buttonClass);
</script>
```
