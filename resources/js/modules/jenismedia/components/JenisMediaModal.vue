<template>
    <BaseModal :is-open="isOpen" :title="isEditing ? 'Edit Data Jenis Media' : 'Tambah Jenis Media Baru'"
        :description="isEditing ? 'Perbarui data jenis media' : 'Tambahkan jenis media baru'"
        :is-submitting="isSubmitting" @close="$emit('close')">
        <!-- Modal Body -->
        <form id="jenis-media-form" @submit.prevent="$emit('save')" class="p-6 space-y-4">
            <div>
                <BaseInput v-model="form.jenismedia" label="Nama Jenis Media" type="text"
                    placeholder="Contoh: Sticker, Banner, dll." :error="errors.jenismedia" required
                    @blur="$emit('validate-jenismedia', 'jenismedia')" />
            </div>
        </form>

        <!-- Modal Footer -->
        <template #footer>
            <div class="flex items-center justify-end gap-3">
                <button type="button" @click="$emit('close')" :disabled="isSubmitting"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-semibold text-blue-950/70 hover:bg-gray-50 transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Batal
                </button>

                <button type="submit" form="jenis-media-form" :disabled="isSubmitting"
                    class="px-4 py-2.5 rounded-xl bg-[#B20600] hover:bg-[#8E0500] text-white text-xs font-semibold transition shadow-sm disabled:opacity-70 disabled:cursor-not-allowed">
                    <span class="flex items-center gap-2">
                        <!-- Spinner -->
                        <svg v-if="isSubmitting" class="w-3.5 h-3.5 animate-spin" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />

                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                        </svg>

                        <!-- Text -->
                        <span>
                            {{
                                isSubmitting
                                    ? (isEditing ? 'Menyimpan perubahan...' : 'Menambahkan...')
                                    : (isEditing ? 'Simpan Perubahan' : 'Tambah')
                            }}
                        </span>
                    </span>
                </button>
            </div>
        </template>
    </BaseModal>
</template>

<script setup>
import BaseModal from '../../../utilities/common/BaseModal.vue';
import BaseInput from '../../../utilities/common/BaseInput.vue';

defineProps({
    isOpen: Boolean,
    isEditing: Boolean,
    form: Object,
    errors: Object,
    isSubmitting: Boolean,
});

defineEmits([
    'close',
    'save',
    'validate-jenismedia',
]);
</script>
