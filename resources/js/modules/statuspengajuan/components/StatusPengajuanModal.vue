<template>
    <BaseModal :is-open="isOpen" :title="isEditing ? 'Edit Data Status Pengajuan' : 'Tambah Status Pengajuan Baru'"
        :description="isEditing ? 'Perbarui data status pengajuan' : 'Tambahkan status pengajuan baru'"
        :is-submitting="isSubmitting" @close="$emit('close')">
        <!-- Modal Body -->
        <form id="status-pengajuan-form" @submit.prevent="$emit('save')" class="p-6 space-y-4">
            <div>
                <BaseInput v-model="form.name" label="Nama Status Pengajuan" type="text"
                    placeholder="Contoh: Diajukan, Proses" :error="errors.name" required
                    @blur="$emit('validate-statuspengajuan', 'name')" />
            </div>

            <div>
                <BaseInput v-model="form.key" label="Key" type="text" placeholder="Contoh: diajukan, proses"
                    :error="errors.key" required @blur="$emit('validate-statuspengajuan', 'key')" />
            </div>

            <div>
                <BaseInput v-model="form.description" label="Deskripsi" type="text"
                    placeholder="Contoh: Pengajuan desain telah dibuat oleh pegawai." :error="errors.description"
                    required @blur="$emit('validate-statuspengajuan', 'description')" />
            </div>

            <div>
                <BaseInput v-model="form.sort_order" label="Sort Order / Urutan" type="text" placeholder="Contoh: 1."
                    :error="errors.sort_order" required @blur="$emit('validate-statuspengajuan', 'sort_order')" />
            </div>
        </form>

        <!-- Modal Footer -->
        <template #footer>
            <div class="flex items-center justify-end gap-3">
                <button type="button" @click="$emit('close')" :disabled="isSubmitting"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-semibold text-blue-950/70 hover:bg-gray-50 transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Batal
                </button>

                <button type="submit" form="status-pengajuan-form" :disabled="isSubmitting"
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
import BaseInput from '../../../utilities/common/BaseInput.vue';
import BaseModal from '../../../utilities/common/BaseModal.vue';

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
    'validate-statuspengajuan'
]);
</script>
