<template>
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-blue-950/40 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl w-full max-w-md overflow-hidden">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="text-base font-bold text-blue-950">
                    {{ isEditing ? 'Edit Data Profesi' : 'Tambah Profesi Baru' }}
                </h3>
                <button @click="$emit('close')" class="text-blue-950/40 hover:text-blue-950 transition">
                    <X :size="18" />
                </button>
            </div>

            <!-- Modal Body -->
            <form @submit.prevent="$emit('save')" class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-blue-950/70 mb-1.5">Nama Profesi *</label>
                    <input v-model="form.profesi" type="text" required placeholder="Contoh: Dokter Spesialis Dahulu"
                        class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-blue-950 focus:outline-none focus:border-[#B20600] focus:bg-white transition" />
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" @click="$emit('close')"
                        class="px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-semibold text-blue-950/70 hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2.5 rounded-xl bg-[#B20600] hover:bg-[#8E0500] text-white text-xs font-semibold transition shadow-sm">
                        {{ isEditing ? 'Simpan Perubahan' : 'Tambah' }}
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
    form: Object
});

defineEmits(['close', 'save']);
</script>
