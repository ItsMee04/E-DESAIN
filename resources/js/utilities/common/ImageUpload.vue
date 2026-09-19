<template>
    <div>
        <!-- Label -->
        <label v-if="label" class="block text-xs font-medium text-blue-950 mb-1.5">
            {{ label }}

            <span v-if="required" class="text-rose-600">
                *
            </span>
        </label>

        <!-- Upload Area -->
        <div class="relative border-2 border-dashed rounded-xl transition-all duration-200" :class="[
            currentPreview
                ? 'border-[#B20600]/30 bg-[#B20600]/[0.02]'
                : 'border-blue-950/10 hover:border-[#B20600]/30 hover:bg-blue-950/[0.01]',
            error
                ? 'border-rose-500 bg-rose-50/20'
                : ''
        ]">
            <input ref="fileInput" type="file" :accept="accept" class="hidden" @change="handleFileChange" />

            <!-- Belum ada foto -->
            <button v-if="!currentPreview" type="button"
                class="w-full px-6 py-8 flex flex-col items-center justify-center cursor-pointer"
                @click="openFilePicker">
                <div class="w-12 h-12 flex items-center justify-center rounded-full bg-blue-950/[0.04] mb-3">
                    <ImageIcon class="w-6 h-6 text-blue-950/40" />
                </div>

                <p class="text-sm font-medium text-blue-950/70">
                    {{ emptyText }}
                </p>

                <p class="mt-1 text-[11px] text-blue-950/40">
                    {{ helperText }}
                </p>
            </button>

            <!-- Sudah ada foto -->
            <div v-else class="p-4">
                <div class="flex items-center gap-4">

                    <!-- Preview -->
                    <div class="relative shrink-0">
                        <img :src="currentPreview" :alt="previewAlt"
                            class="w-20 h-20 object-cover rounded-xl border border-blue-950/10" />

                        <div
                            class="absolute -right-1.5 -bottom-1.5 w-5 h-5 rounded-full bg-green-500 border-2 border-white flex items-center justify-center">
                            <span class="text-white text-[10px]">
                                ✓
                            </span>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-blue-950 truncate">
                            {{ fileName || fileNameText }}
                        </p>

                        <p class="text-[11px] text-green-600 mt-1">
                            {{ successText }}
                        </p>

                        <button type="button" class="mt-2 text-[11px] font-medium text-[#B20600] hover:underline"
                            @click="openFilePicker">
                            {{ changeText }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error -->
        <p v-if="error" class="mt-1.5 text-[11px] text-rose-600">
            {{ error }}
        </p>
    </div>
</template>

<script setup>
import { ref, computed, watch, onBeforeUnmount } from "vue";
import { Image as ImageIcon } from "lucide-vue-next";

const props = defineProps({
    modelValue: {
        type: [File, null],
        default: null,
    },
    preview: {
        type: String,
        default: "",
    },
    label: {
        type: String,
        default: "Foto",
    },
    previewAlt: {
        type: String,
        default: "Preview foto",
    },
    accept: {
        type: String,
        default: "image/jpeg,image/png,image/jpg",
    },
    maxSize: {
        type: Number,
        default: 2,
    },
    emptyText: {
        type: String,
        default: "Pilih foto",
    },
    helperText: {
        type: String,
        default: "JPG, JPEG atau PNG • Maksimal 2 MB",
    },
    successText: {
        type: String,
        default: "Foto berhasil dipilih",
    },
    changeText: {
        type: String,
        default: "Ganti foto",
    },
    fileNameText: {
        type: String,
        default: "Foto",
    },
    error: {
        type: [String, Boolean],
        default: "",
    },
    required: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits([
    "update:modelValue",
    "change",
]);

const fileInput = ref(null);
const localPreview = ref("");
const objectUrl = ref("");

const fileName = computed(() => {
    return props.modelValue?.name || "";
});

const currentPreview = computed(() => {
    // Kalau ada File baru, gunakan preview dari File
    if (localPreview.value) {
        return localPreview.value;
    }

    // Kalau edit data lama, gunakan preview dari parent
    return props.preview;
});

const openFilePicker = () => {
    fileInput.value?.click();
};

const handleFileChange = (event) => {
    const file = event.target.files?.[0];

    if (!file) {
        return;
    }

    // Hapus object URL sebelumnya
    if (objectUrl.value) {
        URL.revokeObjectURL(objectUrl.value);
    }

    // Buat preview dari File
    objectUrl.value = URL.createObjectURL(file);
    localPreview.value = objectUrl.value;

    emit("update:modelValue", file);
    emit("change", file);

    // Supaya file yang sama tetap bisa dipilih ulang
    event.target.value = "";
};

watch(
    () => props.preview,
    (value) => {
        // Kalau parent mengganti preview
        // dan tidak ada file baru yang dipilih
        if (!props.modelValue) {
            localPreview.value = "";
        }
    },
    { immediate: true }
);

watch(
    () => props.modelValue,
    (file) => {
        // Kalau form di-reset menjadi null
        if (!file) {
            if (objectUrl.value) {
                URL.revokeObjectURL(objectUrl.value);
                objectUrl.value = "";
            }

            localPreview.value = "";
        }
    }
);

onBeforeUnmount(() => {
    if (objectUrl.value) {
        URL.revokeObjectURL(objectUrl.value);
    }
});
</script>
