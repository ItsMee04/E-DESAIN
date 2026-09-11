<template>
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-blue-950/40 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl w-full max-w-md overflow-hidden">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="text-base font-bold text-blue-950">
                    {{ isEditing ? 'Edit Data Agama' : 'Tambah Agama Baru' }}
                </h3>
                <button @click="$emit('close')" class="text-blue-950/40 hover:text-blue-950 transition">
                    <X :size="18" />
                </button>
            </div>

            <!-- Modal Body -->
            <form @submit.prevent="$emit('save')" class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-blue-950/70 mb-1.5">Nama Agama *</label>
                    <input v-model="form.agama" type="text" placeholder="Contoh: Islam"
                        @blur="$emit('validate-agama')"
                        class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-blue-950 focus:outline-none focus:border-[#B20600] focus:bg-white transition" />

                    <p v-if="errors.agama" class="mt-1.5 text-[11px] text-red-600">
                        {{ errors.agama }}
                    </p>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" @click="$emit('close')" :disabled="isSubmitting"
                        class="px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-semibold text-blue-950/70 hover:bg-gray-50 transition disabled:opacity-50 disabled:cursor-not-allowed">
                        Batal
                    </button>

                    <button type="submit" :disabled="isSubmitting"
                        class="px-4 py-2.5 rounded-xl bg-[#B20600] hover:bg-[#8E0500] text-white text-xs font-semibold transition shadow-sm disabled:opacity-70 disabled:cursor-not-allowed">
                        <span class="flex items-center gap-2">
                            <!-- Spinner -->
                            <svg v-if="isSubmitting" class="w-3.5 h-3.5 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4" />

                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                            </svg>

                            <!-- Text -->
                            <span>
                                {{ isSubmitting
                                    ? (isEditing ? 'Menyimpan perubahan...' : 'Menambahkan...')
                                    : (isEditing ? 'Simpan Perubahan' : 'Tambah')
                                }}
                            </span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { X } from 'lucide-vue-next';

defineProps({
    isOpen: Boolean,
    isEditing: Boolean,
    form: Object,
    errors: Object,
    isSubmitting: Boolean
});

defineEmits([
    'close',
    'save',
    'validate-agama'
]);
</script>
