<template>
    <BaseModal :is-open="isOpen" :title="isEditing ? 'Edit Data Pegawai' : 'Tambah Pegawai Baru'"
        :description="isEditing ? 'Perbarui data pegawai' : 'Tambahkan pegawai baru'" :is-submitting="isSubmitting"
        @close="$emit('close')">
        <!-- Modal Body -->
        <form id="pegawai-form" @submit.prevent="$emit('save')" class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- NIP -->
                <div class="space-y-1.5">
                    <BaseInput v-model="form.nip" label="NIP" type="text" placeholder="Masukkan NIP" :error="errors.nip"
                        required @blur="$emit('validate-pegawai', 'nip')" />
                </div>

                <!-- Nama -->
                <div class="space-y-1.5">
                    <BaseInput v-model="form.nama" label="Nama Pegawai" type="text" placeholder="Masukkan Nama Pegawai"
                        :error="errors.nama" required @blur="$emit('validate-pegawai', 'nama')" />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Unit -->
                <div class="space-y-1.5">
                    <SearchableDropdown v-model="form.unit_id" label="Unit" :options="unitOptions" label-key="unit"
                        value-key="id" placeholder="Pilih unit..." dropdown-title="Pilih unit"
                        empty-text="Data unit tidak ditemukan." :error="errors.unit_id" required
                        @blur="$emit('validate-pegawai', 'unit_id')" />
                </div>

                <!-- Profesi -->
                <div class="space-y-1.5">
                    <SearchableDropdown v-model="form.profesi_id" label="Profesi" :options="profesiOptions"
                        label-key="profesi" value-key="id" placeholder="Pilih profesi..." dropdown-title="Pilih profesi"
                        empty-text="Data profesi tidak ditemukan." :error="errors.profesi_id" required
                        @blur="$emit('validate-pegawai', 'profesi_id')" />
                </div>
            </div>

            <!-- Kontak -->
            <div class="space-y-1.5">
                <BaseInput v-model="form.kontak" label="Kontak" type="text" placeholder="Masukkan nomor kontak" />
            </div>

            <!-- Foto -->
            <div>
                <ImageUpload v-model="form.image" :preview="imagePreview" label="Foto" empty-text="Pilih foto pegawai"
                    file-name-text="Foto pegawai" preview-alt="Preview foto pegawai"
                    success-text="Foto berhasil dipilih" change-text="Ganti foto" />
            </div>
        </form>

        <!-- Modal Footer -->
        <template #footer>
            <div class="flex items-center justify-end gap-3">
                <button type="button" @click="$emit('close')" :disabled="isSubmitting"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-semibold text-blue-950/70 hover:bg-gray-50 transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Batal
                </button>

                <button type="submit" form="pegawai-form" :disabled="isSubmitting"
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
import { watch } from "vue";

import SearchableDropdown from "../../../utilities/common/SearchableDropdown.vue";
import BaseInput from "../../../utilities/common/BaseInput.vue";
import BaseModal from "../../../utilities/common/BaseModal.vue";
import ImageUpload from "../../../utilities/common/ImageUpload.vue";

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false,
    },

    isEditing: {
        type: Boolean,
        default: false,
    },

    form: {
        type: Object,
        required: true,
    },

    errors: {
        type: Object,
        default: () => ({}),
    },

    isSubmitting: {
        type: Boolean,
        default: false,
    },

    unitOptions: {
        type: Array,
        default: () => [],
    },

    profesiOptions: {
        type: Array,
        default: () => [],
    },

    imagePreview: {
        type: String,
        default: "",
    },
});

const emit = defineEmits([
    "close",
    "save",
    "validate-pegawai",
]);

watch(
    () => props.isOpen,
    (isOpen) => {
        // Tidak ada state upload yang perlu di-reset di modal.
        // ImageUpload menangani state file melalui v-model.
    },
);
</script>
