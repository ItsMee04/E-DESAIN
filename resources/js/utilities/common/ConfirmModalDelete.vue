<template>
    <Teleport to="body">
        <div v-if="isOpen"
            class="fixed inset-0 z-50 flex items-center justify-center bg-blue-950/40 backdrop-blur-xs p-4">
            <div
                class="bg-white rounded-2xl border border-gray-100 shadow-xl w-full max-w-sm overflow-hidden p-6 text-center space-y-4">
                <!-- Icon Peringatan -->
                <div
                    class="w-12 h-12 bg-rose-50 border border-rose-100 rounded-full flex items-center justify-center mx-auto text-rose-600">
                    <AlertTriangle :size="24" />
                </div>

                <!-- Teks Peringatan Dynamic -->
                <div class="space-y-1">
                    <h3 class="text-base font-bold text-blue-950">{{ title }}</h3>
                    <p class="text-xs text-blue-950/60 leading-relaxed">
                        Apakah Anda yakin ingin menghapus <span class="font-semibold text-blue-950">"{{ itemName
                            }}"</span>? {{ description }}
                    </p>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex items-center justify-center gap-3 pt-2">
                    <button type="button" @click="$emit('close')" :disabled="isDeleting"
                        class="w-full py-2.5 rounded-xl border border-gray-200 text-xs font-semibold text-blue-950/70 hover:bg-gray-50 transition cursor-pointer disabled:opacity-50">
                        Batal
                    </button>
                    <button type="button" @click="$emit('confirm')" :disabled="isDeleting"
                        class="w-full py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold transition cursor-pointer shadow-sm flex items-center justify-center gap-2 disabled:opacity-50">
                        <span v-if="isDeleting"
                            class="w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                        <span>{{ isDeleting ? 'Menghapus...' : 'Ya, Hapus' }}</span>
                    </button>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script setup>
import { AlertTriangle } from 'lucide-vue-next';

defineProps({
    isOpen: {
        type: Boolean,
        default: false
    },
    title: {
        type: String,
        default: 'Hapus Data?'
    },
    itemName: {
        type: String,
        default: ''
    },
    description: {
        type: String,
        default: 'Tindakan ini tidak dapat dibatalkan.'
    },
    isDeleting: {
        type: Boolean,
        default: false
    }
});

defineEmits(['close', 'confirm']);
</script>
