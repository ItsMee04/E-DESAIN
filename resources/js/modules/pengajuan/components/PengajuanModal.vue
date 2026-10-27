<template>
    <BaseModal :is-open="isOpen" :title="isEditing ? 'Edit Pengajuan Desain' : 'Tambah Pengajuan Desain Baru'"
        :description="isEditing ? 'Perbarui data pengajuan desain' : 'Tambahkan pengajuan desain baru'"
        :is-submitting="isSubmitting" @close="$emit('close')">
        <!-- Modal Body -->
        <form id="pengajuan-desain-form" @submit.prevent="$emit('save')" class="p-6 space-y-4">
            <!-- Content: hanya bagian ini yang scroll -->
            <div class="space-y-5 flex-1 min-h-0 overflow-y-auto">
                <!-- Yang Mengajukan -->
                <div>
                    <label class="block text-xs font-semibold text-blue-950 mb-1.5">
                        Yang Mengajukan
                    </label>

                    <input :value="form.pegawai_nama || ''" type="text" readonly
                        class="w-full text-xs bg-gray-100 border border-gray-200 rounded-xl px-3.5 py-2.5 text-blue-950 cursor-not-allowed focus:outline-none" />
                </div>

                <!-- Unit -->
                <div class="relative">
                    <SearchableDropdown v-model="form.unit_id" label="Unit" :options="unitOptions" label-key="unit"
                        value-key="id" placeholder="Pilih Unit..." dropdown-title="Pilih Unit"
                        empty-text="Data unit tidak ditemukan." :error="errors.unit_id" required
                        @blur="$emit('validate-pengajuan', 'unit_id')" />
                </div>

                <!-- Nama Desain -->
                <div>
                    <BaseInput v-model="form.nama_desain" label="Nama Desain" type="text"
                        placeholder="Masukkan nama desain ..." :error="errors.nama_desain" required
                        @blur="$emit('validate-pengajuan', 'nama_desain')" />
                </div>

                <!-- Jenis Media -->
                <div>
                    <label class="block text-xs font-medium text-blue-950 mb-1.5">
                        Jenis Media <span class="text-red-500">*</span>
                    </label>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        <label v-for="media in jenisMedia" :key="media.id"
                            class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg border border-blue-950/10 bg-white hover:bg-blue-950/[0.02] cursor-pointer transition-colors">
                            <input type="checkbox" :value="media.id" v-model="form.jenis_media"
                                @change="emit('validate-pengajuan')"
                                class="w-4 h-4 rounded border-gray-300 text-[#B20600] accent-[#B20600] focus:ring-[#B20600] cursor-pointer" />

                            <span class="text-sm text-blue-950">
                                {{ media.jenismedia }}
                            </span>
                        </label>
                    </div>

                    <p v-if="errors.jenis_media" class="mt-1 text-[11px] text-red-500">
                        {{ errors.jenis_media }}
                    </p>
                </div>

                <!-- Ukuran & Jumlah -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <BaseInput v-model="form.ukuran" label="Ukuran" type="text" placeholder="Contoh: 3 x 1 meter"
                            :error="errors.ukuran" required @blur="$emit('validate-pengajuan', 'ukuran')" />
                    </div>

                    <div>
                        <BaseInput v-model="form.jumlah" label="Jumlah Cetak" type="text"
                            placeholder="Masukkan jumlah ..." :error="errors.jumlah" required
                            @blur="$emit('validate-pengajuan', 'jumlah')" />
                    </div>
                </div>

                <!-- Keperluan -->
                <div>
                    <BaseTextarea v-model="form.keperluan" label="Keperluan" placeholder="Jelaskan keperluan desain..."
                        :rows="5" required @blur="$emit('validate-pengajuan', 'keperluan')" :error="errors.keperluan" />
                </div>
            </div>
        </form>

        <!-- Modal Footer -->
        <template #footer>
            <div class="flex items-center justify-end gap-3">
                <button type="button" @click="$emit('close')" :disabled="isSubmitting"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-semibold text-blue-950/70 hover:bg-gray-50 transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Batal
                </button>

                <button type="submit" form="pengajuan-desain-form" :disabled="isSubmitting"
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
import { computed } from "vue";

import BaseInput from "../../../utilities/common/BaseInput.vue";
import BaseModal from "../../../utilities/common/BaseModal.vue";
import BaseTextarea from "../../../utilities/common/BaseTextarea.vue";
import SearchableDropdown from "../../../utilities/common/SearchableDropdown.vue";

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

    units: {
        type: Array,
        default: () => [],
    },

    jenisMedia: {
        type: Array,
        default: () => [],
    },
});
const unitOptions = computed(() => props.units);
const emit = defineEmits([
    "close",
    "save",
    "validate-pengajuan",
]);
</script>
