```vue
<template>
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-blue-950/40 backdrop-blur-xs p-4">
        <div
            class="bg-white rounded-2xl border border-gray-100 shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 shrink-0">
                <div>
                    <h2 class="text-base font-bold text-blue-950">
                        {{
                            isEditing
                                ? "Edit Data Pegawai"
                                : "Tambah Data Pegawai"
                        }}
                    </h2>

                    <p class="text-xs text-blue-950/50 mt-1">
                        {{
                            isEditing
                                ? "Perbarui informasi data pegawai."
                                : "Tambahkan data pegawai baru."
                        }}
                    </p>
                </div>

                <button type="button" @click="$emit('close')" :disabled="isSubmitting"
                    class="w-8 h-8 flex items-center justify-center rounded-lg text-blue-950/50 hover:bg-gray-100 hover:text-blue-950 transition cursor-pointer disabled:opacity-50">
                    <X :size="18" />
                </button>
            </div>

            <!-- Form -->
            <form @submit.prevent="$emit('save')" class="p-6 space-y-4 overflow-y-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- NIP -->
                    <div class="space-y-1.5">
                        <BaseInput v-model="form.nip" label="NIP" type="text" placeholder="Masukkan NIP"
                            :error="errors.nip" required @blur="$emit('validate-pegawai', 'nip')" />
                    </div>

                    <!-- Nama -->
                    <div class="space-y-1.5">
                        <BaseInput v-model="form.nama" label="Nama Pegawai" type="text"
                            placeholder="Masukkan Nama Pegawai" :error="errors.nama" required
                            @blur="$emit('validate-pegawai', 'nama')" />
                    </div>
                </div>

                <!-- Tempat & Tanggal Lahir -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Tempat -->
                    <div class="space-y-1.5">
                        <BaseInput v-model="form.tempat" label="Tempat Lahir" type="text"
                            placeholder="Masukkan Tempat Lahir" :error="errors.tempat" required
                            @blur="$emit('validate-pegawai', 'tempat')" />
                    </div>

                    <!-- Tanggal -->
                    <div class="space-y-1.5">
                        <DatePicker v-model="form.tanggal" label="Tanggal Lahir" placeholder="Pilih tanggal lahir..."
                            :error="errors.tanggal" required />
                    </div>
                </div>

                <!-- Jenis Kelamin -->
                <div class="space-y-1.5">
                    <BaseRadioGroup v-model="form.jeniskelamin_id" label="Jenis Kelamin" :options="jeniskelaminOptions"
                        label-key="jeniskelamin" value-key="id" :error="errors.jeniskelamin_id" required
                        @change="$emit('validate-pegawai', 'jeniskelamin_id')" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Agama -->
                    <div class="space-y-1.5">
                        <SearchableDropdown v-model="form.agama_id" label="Agama" :options="agamaOptions"
                            label-key="agama" value-key="id" placeholder="Pilih agama..." dropdown-title="Pilih agama"
                            empty-text="Data agama tidak ditemukan." :error="errors.agama_id" required
                            @blur="$emit('validate-pegawai', 'agama_id')" />
                    </div>

                    <!-- Profesi -->
                    <div class="space-y-1.5">
                        <SearchableDropdown v-model="form.profesi_id" label="Profesi" :options="profesiOptions"
                            label-key="profesi" value-key="id" placeholder="Pilih profesi..."
                            dropdown-title="Pilih profesi" empty-text="Data profesi tidak ditemukan."
                            :error="errors.profesi_id" required @blur="$emit('validate-pegawai', 'profesi_id')" />
                    </div>
                </div>

                <!-- Alamat -->
                <div class="space-y-1.5">
                    <BaseTextarea v-model="form.alamat" label="Alamat" placeholder="Masukkan alamat" :rows="5" />
                </div>

                <!-- Kontak -->
                <div class="space-y-1.5">
                    <BaseInput v-model="form.kontak" label="Kontak" type="text" placeholder="Masukkan nomor kontak" />
                </div>

                <!-- Foto -->
                <div>
                    <ImageUpload v-model="form.image" :preview="imagePreview" label="Foto"
                        empty-text="Pilih foto pegawai" file-name-text="Foto pegawai" preview-alt="Preview foto pegawai"
                        success-text="Foto berhasil dipilih" change-text="Ganti foto" />
                </div>

                <!-- Footer -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" @click="$emit('close')" :disabled="isSubmitting"
                        class="px-4 py-2.5 rounded-xl border border-gray-200 text-xs font-semibold text-blue-950/70 hover:bg-gray-50 transition cursor-pointer disabled:opacity-50">
                        Batal
                    </button>

                    <button type="submit" :disabled="isSubmitting"
                        class="px-5 py-2.5 rounded-xl bg-[#B20600] hover:bg-[#970500] text-white text-xs font-semibold transition cursor-pointer shadow-sm flex items-center justify-center gap-2 disabled:opacity-50">
                        <span v-if="isSubmitting"
                            class="w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>

                        <span>
                            {{ isSubmitting ? "Menyimpan..." : "Simpan" }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { watch } from "vue";
import { X } from "lucide-vue-next";

import SearchableDropdown from "../../../utilities/common/SearchableDropdown.vue";
import DatePicker from "../../../utilities/common/Datepicker.vue";
import BaseInput from "../../../utilities/common/BaseInput.vue";
import BaseTextarea from "../../../utilities/common/BaseTextarea.vue";
import ImageUpload from "../../../utilities/common/ImageUpload.vue";
import BaseRadioGroup from "../../../utilities/common/BaseRadioGroup.vue";

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

    agamaOptions: {
        type: Array,
        default: () => [],
    },

    profesiOptions: {
        type: Array,
        default: () => [],
    },

    jeniskelaminOptions: {
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
