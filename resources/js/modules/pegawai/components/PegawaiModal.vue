```vue
<template>
    <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-blue-950/40 backdrop-blur-xs p-4">
        <div
            class="bg-white rounded-2xl border border-gray-100 shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 shrink-0">
                <div>
                    <h2 class="text-base font-bold text-blue-950">
                        {{ isEditing ? 'Edit Data Pegawai' : 'Tambah Data Pegawai' }}
                    </h2>

                    <p class="text-xs text-blue-950/50 mt-1">
                        {{
                            isEditing
                                ? 'Perbarui informasi data pegawai.'
                                : 'Tambahkan data pegawai baru.'
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
                        <label class="text-xs font-semibold text-blue-950">
                            NIP <span class="text-rose-600">*</span>
                        </label>
                        <input v-model="form.nip" type="text" placeholder="Masukkan NIP"
                            class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-xs text-blue-950 outline-none focus:border-blue-950/30 focus:ring-2 focus:ring-blue-950/5 transition"
                            :class="{
                                'border-rose-500 focus:border-rose-500 focus:ring-rose-500/5':
                                    errors.nip
                            }" @blur="$emit('validate-pegawai', 'nip')" />

                        <p v-if="errors.nip" class="text-[11px] text-rose-600">
                            {{ errors.nip }}
                        </p>
                    </div>

                    <!-- Nama -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-blue-950">
                            Nama Pegawai <span class="text-rose-600">*</span>
                        </label>
                        <input v-model="form.nama" type="text" placeholder="Masukkan nama pegawai"
                            class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-xs text-blue-950 outline-none focus:border-blue-950/30 focus:ring-2 focus:ring-blue-950/5 transition"
                            :class="{
                                'border-rose-500 focus:border-rose-500 focus:ring-rose-500/5':
                                    errors.nama
                            }" @blur="$emit('validate-pegawai', 'nama')" />

                        <p v-if="errors.nama" class="text-[11px] text-rose-600">
                            {{ errors.nama }}
                        </p>
                    </div>
                </div>

                <!-- Tempat & Tanggal Lahir -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Tempat -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-blue-950">
                            Tempat Lahir
                            <span class="text-rose-600">*</span>
                        </label>

                        <input v-model="form.tempat" type="text" placeholder="Masukkan tempat lahir"
                            class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-xs text-blue-950 outline-none focus:border-blue-950/30 focus:ring-2 focus:ring-blue-950/5 transition"
                            :class="{
                                'border-rose-500 focus:border-rose-500 focus:ring-rose-500/5':
                                    errors.tempat
                            }" @blur="$emit('validate-pegawai', 'tempat')" />

                        <p v-if="errors.tempat" class="text-[11px] text-rose-600">
                            {{ errors.tempat }}
                        </p>
                    </div>

                    <!-- Tanggal -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-blue-950">
                            Tanggal Lahir
                            <span class="text-rose-600">*</span>
                        </label>

                        <input v-model="form.tanggal" type="date"
                            class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-xs text-blue-950 outline-none focus:border-blue-950/30 focus:ring-2 focus:ring-blue-950/5 transition"
                            :class="{
                                'border-rose-500 focus:border-rose-500 focus:ring-rose-500/5':
                                    errors.tanggal
                            }" @blur="$emit('validate-pegawai', 'tanggal')" />

                        <p v-if="errors.tanggal" class="text-[11px] text-rose-600">
                            {{ errors.tanggal }}
                        </p>
                    </div>
                </div>

                <!-- Jenis Kelamin -->
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-blue-950">
                        Jenis Kelamin
                        <span class="text-rose-600">*</span>
                    </label>

                    <div class="flex flex-wrap items-center gap-6">
                        <label v-for="item in jeniskelaminOptions" :key="item.id"
                            class="flex items-center gap-2 text-xs text-blue-950/70 cursor-pointer">
                            <input v-model="form.jeniskelamin_id" type="radio" :value="item.id"
                                class="w-4 h-4 accent-[#B20600] cursor-pointer" @change="
                                    $emit(
                                        'validate-pegawai',
                                        'jeniskelamin_id'
                                    )
                                    " />

                            <span>{{ item.jeniskelamin }}</span>
                        </label>
                    </div>

                    <p v-if="errors.jeniskelamin_id" class="text-[11px] text-rose-600">
                        {{ errors.jeniskelamin_id }}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <!-- Agama -->
                    <div class="relative">
                        <label class="block text-xs font-medium text-blue-950 mb-1.5">
                            Agama <span class="text-red-500">*</span>
                        </label>

                        <!-- Input -->
                        <div class="relative">
                            <input v-model="agamaSearch" type="text" placeholder="Pilih agama..."
                                class="w-full h-10 px-3 pr-10 text-sm text-blue-950 bg-white border border-blue-950/15 rounded-lg outline-none transition-all duration-200 focus:border-[#B20600] focus:ring-2 focus:ring-[#B20600]/10"
                                @focus="isAgamaOpen = true" @input="isAgamaOpen = true" @blur="closeAgamaDropdown" />

                            <ChevronDown
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-blue-950/50 pointer-events-none transition-transform duration-200"
                                :class="{ 'rotate-180': isAgamaOpen }" />
                        </div>

                        <!-- Dropdown -->
                        <div v-if="isAgamaOpen"
                            class="absolute z-50 left-0 right-0 mt-1.5 bg-white border border-blue-950/10 rounded-xl shadow-lg shadow-blue-950/10 overflow-hidden">
                            <!-- Header kecil -->
                            <div class="px-3 py-2 border-b border-blue-950/5">
                                <p class="text-[11px] font-medium text-blue-950/40 uppercase tracking-wide">
                                    Pilih agama
                                </p>
                            </div>

                            <!-- List -->
                            <div class="max-h-48 overflow-y-auto py-1">
                                <button v-for="item in filteredAgama" :key="item.id" type="button"
                                    class="w-full flex items-center justify-between px-3 py-2.5 text-left text-sm transition-colors duration-150"
                                    :class="form.agama_id === item.id
                                        ? 'bg-[#B20600]/5 text-[#B20600]'
                                        : 'text-blue-950/80 hover:bg-blue-950/[0.03]'
                                        " @mousedown.prevent="selectAgama(item)">
                                    <span>{{ item.agama }}</span>

                                    <span v-if="form.agama_id === item.id"
                                        class="w-5 h-5 flex items-center justify-center rounded-full bg-[#B20600] text-white text-[10px]">
                                        ✓
                                    </span>
                                </button>

                                <!-- Empty state -->
                                <div v-if="filteredAgama.length === 0" class="px-3 py-6 text-center">
                                    <p class="text-xs text-blue-950/40">
                                        Data agama tidak ditemukan.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Error -->
                        <p v-if="errors.agama_id" class="mt-1 text-[11px] text-red-500">
                            {{ errors.agama_id }}
                        </p>
                    </div>

                    <!-- Profesi -->
                    <div class="relative">
                        <label class="block text-xs font-medium text-blue-950 mb-1.5">
                            Profesi <span class="text-red-500">*</span>
                        </label>

                        <!-- Input -->
                        <div class="relative">
                            <input v-model="profesiSearch" type="text" placeholder="Pilih profesi..."
                                class="w-full h-10 px-3 pr-10 text-sm text-blue-950 bg-white border border-blue-950/15 rounded-lg outline-none transition-all duration-200 focus:border-[#B20600] focus:ring-2 focus:ring-[#B20600]/10"
                                @focus="isProfesiOpen = true" @input="isProfesiOpen = true"
                                @blur="closeProfesiDropdown" />

                            <ChevronDown
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-blue-950/50 pointer-events-none transition-transform duration-200"
                                :class="{ 'rotate-180': isProfesiOpen }" />
                        </div>

                        <!-- Dropdown -->
                        <div v-if="isProfesiOpen"
                            class="absolute z-50 left-0 right-0 mt-1.5 bg-white border border-blue-950/10 rounded-xl shadow-lg shadow-blue-950/10 overflow-hidden">
                            <!-- Header kecil -->
                            <div class="px-3 py-2 border-b border-blue-950/5">
                                <p class="text-[11px] font-medium text-blue-950/40 uppercase tracking-wide">
                                    Pilih profesi
                                </p>
                            </div>

                            <!-- List -->
                            <div class="max-h-48 overflow-y-auto py-1">
                                <button v-for="item in filteredProfesi" :key="item.id" type="button"
                                    class="w-full flex items-center justify-between px-3 py-2.5 text-left text-sm transition-colors duration-150"
                                    :class="form.profesi_id === item.id
                                        ? 'bg-[#B20600]/5 text-[#B20600]'
                                        : 'text-blue-950/80 hover:bg-blue-950/[0.03]'
                                        " @mousedown.prevent="selectProfesi(item)">
                                    <span>{{ item.profesi }}</span>

                                    <span v-if="form.profesi_id === item.id"
                                        class="w-5 h-5 flex items-center justify-center rounded-full bg-[#B20600] text-white text-[10px]">
                                        ✓
                                    </span>
                                </button>

                                <!-- Empty state -->
                                <div v-if="filteredProfesi.length === 0" class="px-3 py-6 text-center">
                                    <p class="text-xs text-blue-950/40">
                                        Data profesi tidak ditemukan.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Error -->
                        <p v-if="errors.profesi_id" class="mt-1 text-[11px] text-red-500">
                            {{ errors.profesi_id }}
                        </p>
                    </div>
                </div>

                <!-- Alamat -->
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-blue-950">
                        Alamat
                    </label>

                    <textarea v-model="form.alamat" rows="3" placeholder="Masukkan alamat"
                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-xs text-blue-950 outline-none resize-none focus:border-blue-950/30 focus:ring-2 focus:ring-blue-950/5 transition"></textarea>
                </div>

                <!-- Kontak -->
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-blue-950">
                        Kontak
                    </label>

                    <input v-model="form.kontak" type="text" placeholder="Masukkan nomor kontak"
                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-xs text-blue-950 outline-none focus:border-blue-950/30 focus:ring-2 focus:ring-blue-950/5 transition" />
                </div>

                <!-- Foto -->
                <div>
                    <label class="block text-xs font-medium text-blue-950 mb-1.5">
                        Foto
                    </label>

                    <!-- Upload Area -->
                    <div class="relative border-2 border-dashed rounded-xl transition-all duration-200" :class="imagePreview
                        ? 'border-[#B20600]/30 bg-[#B20600]/[0.02]'
                        : 'border-blue-950/10 hover:border-[#B20600]/30 hover:bg-blue-950/[0.01]'
                        ">
                        <!-- Input file -->
                        <input ref="fileInput" type="file" accept="image/jpeg,image/png,image/jpg" class="hidden"
                            @change="handleImageChange" />

                        <!-- Belum ada foto -->
                        <button v-if="!imagePreview" type="button"
                            class="w-full px-6 py-8 flex flex-col items-center justify-center cursor-pointer"
                            @click="fileInput?.click()">
                            <div
                                class="w-12 h-12 flex items-center justify-center rounded-full bg-blue-950/[0.04] mb-3">
                                <ImageIcon class="w-6 h-6 text-blue-950/40" />
                            </div>

                            <p class="text-sm font-medium text-blue-950/70">
                                Pilih foto pegawai
                            </p>

                            <p class="mt-1 text-[11px] text-blue-950/40">
                                JPG, JPEG atau PNG • Maksimal 2 MB
                            </p>
                        </button>

                        <!-- Sudah ada foto -->
                        <div v-else class="p-4">
                            <div class="flex items-center gap-4">
                                <!-- Preview -->
                                <div class="relative shrink-0">
                                    <img :src="imagePreview" alt="Preview foto"
                                        class="w-20 h-20 object-cover rounded-xl border border-blue-950/10" />

                                    <!-- Status -->
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
                                        {{ selectedFileName || 'Foto pegawai' }}
                                    </p>

                                    <p class="text-[11px] text-green-600 mt-1">
                                        Foto berhasil dipilih
                                    </p>

                                    <button type="button"
                                        class="mt-2 text-[11px] font-medium text-[#B20600] hover:underline"
                                        @click="fileInput?.click()">
                                        Ganti foto
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
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
                            {{ isSubmitting ? 'Menyimpan...' : 'Simpan' }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import {
    X,
    ChevronDown,
    Image as ImageIcon
} from 'lucide-vue-next';

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false
    },

    isEditing: {
        type: Boolean,
        default: false
    },

    form: {
        type: Object,
        required: true
    },

    errors: {
        type: Object,
        default: () => ({})
    },

    isSubmitting: {
        type: Boolean,
        default: false
    },

    agamaOptions: {
        type: Array,
        default: () => []
    },

    profesiOptions: {
        type: Array,
        default: () => []
    },

    jeniskelaminOptions: {
        type: Array,
        default: () => []
    },

    imagePreview: {
        type: String,
        default: ''
    }
});

const emit = defineEmits([
    'close',
    'save',
    'validate-pegawai',
    'image-change'
]);

const agamaSearch = ref('');
const profesiSearch = ref('');

const isAgamaOpen = ref(false);
const isProfesiOpen = ref(false);

const fileInput = ref(null);
const selectedFileName = ref('');

/*
|--------------------------------------------------------------------------
| Filter Agama
|--------------------------------------------------------------------------
*/

const filteredAgama = computed(() => {
    const query = agamaSearch.value
        .toLowerCase()
        .trim();

    if (!query) {
        return props.agamaOptions;
    }

    return props.agamaOptions.filter((item) =>
        item.agama?.toLowerCase().includes(query)
    );
});

/*
|--------------------------------------------------------------------------
| Filter Profesi
|--------------------------------------------------------------------------
*/

const filteredProfesi = computed(() => {
    const query = profesiSearch.value
        .toLowerCase()
        .trim();

    if (!query) {
        return props.profesiOptions;
    }

    return props.profesiOptions.filter((item) =>
        item.profesi?.toLowerCase().includes(query)
    );
});

/*
|--------------------------------------------------------------------------
| Select Agama
|--------------------------------------------------------------------------
*/

const selectAgama = (item) => {
    props.form.agama_id = item.id;
    agamaSearch.value = item.agama;

    isAgamaOpen.value = false;

    emit('validate-pegawai', 'agama_id');
};

/*
|--------------------------------------------------------------------------
| Select Profesi
|--------------------------------------------------------------------------
*/

const selectProfesi = (item) => {
    props.form.profesi_id = item.id;
    profesiSearch.value = item.profesi;

    isProfesiOpen.value = false;

    emit('validate-pegawai', 'profesi_id');
};

/*
|--------------------------------------------------------------------------
| Close Dropdown
|--------------------------------------------------------------------------
*/

const closeAgamaDropdown = () => {
    setTimeout(() => {
        isAgamaOpen.value = false;
    }, 150);
};

const closeProfesiDropdown = () => {
    setTimeout(() => {
        isProfesiOpen.value = false;
    }, 150);
};

/*
|--------------------------------------------------------------------------
| Image
|--------------------------------------------------------------------------
*/

const handleImageChange = (event) => {
    const file = event.target.files?.[0];

    if (!file) {
        return;
    }

    selectedFileName.value = file.name;

    emit('image-change', file);

    // Reset input agar file yang sama bisa dipilih kembali
    event.target.value = '';
};

/*
|--------------------------------------------------------------------------
| Sync Agama Search
|--------------------------------------------------------------------------
*/

watch(
    [
        () => props.form.agama_id,
        () => props.agamaOptions
    ],
    ([id, options]) => {
        if (id === null || id === undefined || id === '') {
            agamaSearch.value = '';
            return;
        }

        const selected = options.find(
            (item) => Number(item.id) === Number(id)
        );

        agamaSearch.value = selected?.agama ?? '';
    },
    {
        immediate: true,
        deep: true
    }
);

/*
|--------------------------------------------------------------------------
| Sync Profesi Search
|--------------------------------------------------------------------------
*/

watch(
    [
        () => props.form.profesi_id,
        () => props.profesiOptions
    ],
    ([id, options]) => {
        if (id === null || id === undefined || id === '') {
            profesiSearch.value = '';
            return;
        }

        const selected = options.find(
            (item) => Number(item.id) === Number(id)
        );

        profesiSearch.value = selected?.profesi ?? '';
    },
    {
        immediate: true,
        deep: true
    }
);
/*
|--------------------------------------------------------------------------
| Reset Search ketika modal ditutup
|--------------------------------------------------------------------------
*/

watch(
    () => props.isOpen,
    (isOpen) => {
        if (!isOpen) {
            isAgamaOpen.value = false;
            isProfesiOpen.value = false;
        }
    }
);
</script>
