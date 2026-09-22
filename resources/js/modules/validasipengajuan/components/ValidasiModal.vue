<template>
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-blue-950/40 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-xl w-full max-w-2xl overflow-hidden">
            <!-- Modal Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-base font-bold text-blue-950">
                        Detail & Validasi Pengajuan
                    </h3>

                    <p class="text-xs text-blue-950/50 mt-0.5">
                        Periksa detail pengajuan sebelum memberikan keputusan.
                    </p>
                </div>

                <button @click="$emit('close')" :disabled="isSubmitting"
                    class="text-blue-950/40 hover:text-blue-950 transition disabled:opacity-50 disabled:cursor-not-allowed">
                    <X :size="18" />
                </button>
            </div>

            <!-- Modal Body -->
            <form @submit.prevent="$emit('save')" class="p-6 space-y-5">
                <!-- Detail Pengajuan -->
                <div class="space-y-3">
                    <div>
                        <h4 class="text-xs font-bold text-blue-950">
                            Informasi Pengajuan
                        </h4>

                        <p class="text-[11px] text-blue-950/50 mt-0.5">
                            Informasi pengajuan desain yang akan divalidasi.
                        </p>
                    </div>

                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 gap-x-5 gap-y-3 bg-gray-50 border border-gray-100 rounded-xl p-4">
                        <!-- Nomor Pengajuan -->
                        <div>
                            <label class="block text-[11px] font-medium text-blue-950/50 mb-1">
                                Nomor Pengajuan
                            </label>

                            <p class="text-xs font-semibold text-blue-950">
                                {{ selectedItem?.nomor || "-" }}
                            </p>
                        </div>

                        <!-- Pemohon -->
                        <div>
                            <label class="block text-[11px] font-medium text-blue-950/50 mb-1">
                                Pemohon
                            </label>

                            <p class="text-xs font-semibold text-blue-950">
                                {{ selectedItem?.pegawai?.nama || "-" }}
                            </p>
                        </div>

                        <!-- Unit -->
                        <div>
                            <label class="block text-[11px] font-medium text-blue-950/50 mb-1">
                                Unit
                            </label>

                            <p class="text-xs font-semibold text-blue-950">
                                {{ selectedItem?.unit?.unit || "-" }}
                            </p>
                        </div>

                        <!-- Nama Desain -->
                        <div>
                            <label class="block text-[11px] font-medium text-blue-950/50 mb-1">
                                Nama Desain
                            </label>

                            <p class="text-xs font-semibold text-blue-950">
                                {{ selectedItem?.nama_desain || "-" }}
                            </p>
                        </div>

                        <!-- Ukuran -->
                        <div>
                            <label class="block text-[11px] font-medium text-blue-950/50 mb-1">
                                Ukuran
                            </label>

                            <p class="text-xs font-semibold text-blue-950">
                                {{ selectedItem?.ukuran || "-" }}
                            </p>
                        </div>

                        <!-- Jumlah -->
                        <div>
                            <label class="block text-[11px] font-medium text-blue-950/50 mb-1">
                                Jumlah Cetak
                            </label>

                            <p class="text-xs font-semibold text-blue-950">
                                {{ selectedItem?.jumlah || "-" }}
                            </p>
                        </div>

                        <!-- Jenis Media -->
                        <div class="sm:col-span-2">
                            <label class="block text-[11px] font-medium text-blue-950/50 mb-1">
                                Jenis Media
                            </label>

                            <div class="flex flex-wrap gap-1.5">
                                <span v-if="
                                    selectedItem?.pengajuanjenismedia
                                        ?.length
                                " v-for="media in selectedItem.pengajuanjenismedia" :key="media.id"
                                    class="px-2 py-1 rounded-lg bg-white border border-gray-200 text-[11px] font-medium text-blue-950">
                                    {{ media.jenismedia?.jenismedia || "-" }}
                                </span>

                                <span v-else class="text-xs text-blue-950/50">
                                    -
                                </span>
                            </div>
                        </div>

                        <!-- Keperluan -->
                        <div class="sm:col-span-2">
                            <label class="block text-[11px] font-medium text-blue-950/50 mb-1">
                                Keperluan
                            </label>

                            <p class="text-xs leading-relaxed text-blue-950">
                                {{ selectedItem?.keperluan || "-" }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Keputusan Validasi -->
                <div class="space-y-3">
                    <div>
                        <h4 class="text-xs font-bold text-blue-950">
                            Keputusan Validasi
                        </h4>

                        <p class="text-[11px] text-blue-950/50 mt-0.5">
                            Tentukan keputusan terhadap pengajuan ini.
                        </p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-semibold text-blue-950 mb-2">
                            Keputusan
                            <span class="text-[#B20600]">*</span>
                        </label>

                        <div class="grid grid-cols-2 gap-3">
                            <!-- ACC -->
                            <button type="button" @click="form.status = 'approved'" :disabled="isSubmitting" :class="[
                                'flex items-center justify-center gap-2 px-4 py-3 rounded-xl border text-xs font-semibold transition cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed',
                                form.status === 'approved'
                                    ? 'bg-emerald-0 text-emerald-700 border-emerald-300 ring-1 ring-emerald-200'
                                    : 'bg-white text-blue-950/60 border-gray-200 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200',
                            ]">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>

                                ACC
                            </button>

                            <!-- Tidak ACC -->
                            <button type="button" @click="form.status = 'rejected'" :disabled="isSubmitting" :class="[
                                'flex items-center justify-center gap-2 px-4 py-3 rounded-xl border text-xs font-semibold transition cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed',
                                form.status === 'rejected'
                                    ? 'bg-rose-50 text-rose-700 border-rose-300 ring-1 ring-rose-200'
                                    : 'bg-white text-blue-950/60 border-gray-200 hover:bg-rose-50 hover:text-rose-700 hover:border-rose-200',
                            ]">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>

                                Tidak ACC
                            </button>
                        </div>

                        <p v-if="errors.status" class="mt-1.5 text-[11px] text-rose-600">
                            {{ errors.status }}
                        </p>
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-xs font-semibold text-blue-950 mb-1.5">
                            Catatan
                            <span v-if="form.status === 'rejected'" class="text-[#B20600]">
                                *
                            </span>
                        </label>

                        <textarea v-model="form.catatan" rows="3" :disabled="isSubmitting"
                            placeholder="Tambahkan catatan validasi..."
                            class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-blue-950 placeholder:text-blue-950/40 focus:outline-none focus:border-[#B20600] focus:bg-white transition resize-none disabled:opacity-50 disabled:cursor-not-allowed"
                            :class="{
                                'border-rose-300 focus:border-rose-400':
                                    errors.catatan,
                            }"></textarea>

                        <p v-if="errors.catatan" class="mt-1.5 text-[11px] text-rose-600">
                            {{ errors.catatan }}
                        </p>
                    </div>
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

                            <span>
                                {{
                                    isSubmitting
                                        ? "Memproses validasi..."
                                        : "Simpan Validasi"
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
import { X } from "lucide-vue-next";

defineProps({
    isOpen: Boolean,
    selectedItem: Object,
    form: Object,
    errors: Object,
    isSubmitting: Boolean,
});

defineEmits(["close", "save"]);
</script>
