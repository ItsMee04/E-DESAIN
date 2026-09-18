<template>
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-blue-950/40 backdrop-blur-xs p-4">

        <div
            class="bg-white rounded-2xl border border-gray-100 shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h2 class="text-lg font-bold text-blue-950">
                        {{
                            isEditing
                                ? "Edit Pengajuan Desain"
                                : "Tambah Pengajuan Desain"
                        }}
                    </h2>

                    <p class="text-xs text-blue-950/60 mt-0.5">
                        {{
                            isEditing
                                ? "Perbarui data pengajuan desain"
                                : "Isi formulir pengajuan desain"
                        }}
                    </p>
                </div>

                <button @click="$emit('close')" :disabled="isSubmitting"
                    class="p-2 rounded-lg text-blue-950/50 hover:bg-gray-100 hover:text-blue-950 transition cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                    title="Tutup">
                    <X :size="18" />
                </button>
            </div>

            <!-- Form -->
            <form @submit.prevent="$emit('save')" class="p-6 space-y-5 overflow-y-auto">
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
                    <label class="block text-xs font-medium text-blue-950 mb-1.5">
                        Unit <span class="text-red-500">*</span>
                    </label>

                    <SearchableDropdown v-model="form.unit_id" :options="unitOptions" label-key="unit" value-key="id"
                        placeholder="Pilih unit..." dropdown-title="Pilih unit"
                        empty-text="Data unit tidak ditemukan." />

                    <!-- Error -->
                    <p v-if="errors.unit_id" class="mt-1 text-[11px] text-red-500">
                        {{ errors.unit_id }}
                    </p>
                </div>

                <!-- Nama Desain -->
                <div>
                    <label class="block text-xs font-semibold text-blue-950 mb-1.5">
                        Nama Desain
                        <span class="text-[#B20600]">*</span>
                    </label>

                    <input v-model="form.nama_desain" @blur="$emit('validate-nama-desain')" type="text"
                        placeholder="Masukkan nama desain..."
                        class="w-full text-xs bg-gray-50 border rounded-xl px-3.5 py-2.5 text-blue-950 placeholder:text-blue-950/40 focus:outline-none focus:bg-white focus:border-[#B20600] transition"
                        :class="errors.nama_desain
                            ? 'border-rose-400'
                            : 'border-gray-200'
                            " />

                    <p v-if="errors.nama_desain" class="mt-1.5 text-[11px] text-rose-600">
                        {{ errors.nama_desain }}
                    </p>
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
                                @change="emit('validate-jenis-media')"
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
                    <!-- Ukuran -->
                    <div>
                        <label class="block text-xs font-semibold text-blue-950 mb-1.5">
                            Ukuran
                            <span class="text-[#B20600]">*</span>
                        </label>

                        <input v-model="form.ukuran" @blur="$emit('validate-ukuran')" type="text"
                            placeholder="Contoh: 3 x 1 meter"
                            class="w-full text-xs bg-gray-50 border rounded-xl px-3.5 py-2.5 text-blue-950 placeholder:text-blue-950/40 focus:outline-none focus:bg-white focus:border-[#B20600] transition"
                            :class="errors.ukuran
                                ? 'border-rose-400'
                                : 'border-gray-200'
                                " />

                        <p v-if="errors.ukuran" class="mt-1.5 text-[11px] text-rose-600">
                            {{ errors.ukuran }}
                        </p>
                    </div>

                    <!-- Jumlah -->
                    <div>
                        <label class="block text-xs font-semibold text-blue-950 mb-1.5">
                            Jumlah Cetak
                            <span class="text-[#B20600]">*</span>
                        </label>

                        <input v-model="form.jumlah" @blur="$emit('validate-jumlah')" type="number" min="1"
                            placeholder="Masukkan jumlah"
                            class="w-full text-xs bg-gray-50 border rounded-xl px-3.5 py-2.5 text-blue-950 placeholder:text-blue-950/40 focus:outline-none focus:bg-white focus:border-[#B20600] transition"
                            :class="errors.jumlah
                                ? 'border-rose-400'
                                : 'border-gray-200'
                                " />

                        <p v-if="errors.jumlah" class="mt-1.5 text-[11px] text-rose-600">
                            {{ errors.jumlah }}
                        </p>
                    </div>
                </div>

                <!-- Keperluan -->
                <div>
                    <label class="block text-xs font-semibold text-blue-950 mb-1.5">
                        Keperluan
                        <span class="text-[#B20600]">*</span>
                    </label>

                    <textarea v-model="form.keperluan" @blur="$emit('validate-keperluan')" rows="4"
                        placeholder="Jelaskan keperluan desain..."
                        class="w-full text-xs bg-gray-50 border rounded-xl px-3.5 py-2.5 text-blue-950 placeholder:text-blue-950/40 focus:outline-none focus:bg-white focus:border-[#B20600] transition resize-none"
                        :class="errors.keperluan
                            ? 'border-rose-400'
                            : 'border-gray-200'
                            ">
                    </textarea>

                    <p v-if="errors.keperluan" class="mt-1.5 text-[11px] text-rose-600">
                        {{ errors.keperluan }}
                    </p>
                </div>

                <!-- Footer -->
                <div class="flex items-center justify-end gap-2.5 pt-2 border-t border-gray-100">
                    <button type="button" @click="$emit('close')" :disabled="isSubmitting"
                        class="px-4 py-2.5 text-xs font-semibold text-blue-950 bg-gray-100 hover:bg-gray-200 rounded-xl transition cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                        Batal
                    </button>

                    <button type="submit" :disabled="isSubmitting"
                        class="flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-semibold text-white bg-[#B20600] hover:bg-[#8E0500] rounded-xl transition cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed">
                        <div v-if="isSubmitting"
                            class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>

                        <span>
                            {{
                                isSubmitting
                                    ? isEditing
                                        ? "Menyimpan..."
                                        : "Mengajukan..."
                                    : isEditing
                                        ? "Simpan Perubahan"
                                        : "Ajukan Pengajuan"
                            }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import SearchableDropdown from "../../../utilities/common/SearchableDropdown.vue";
import { X } from "lucide-vue-next";

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
    "validate-unit",
    "validate-nama-desain",
    "validate-jenis-media",
    "validate-ukuran",
    "validate-jumlah",
    "validate-keperluan",
]);
</script>
