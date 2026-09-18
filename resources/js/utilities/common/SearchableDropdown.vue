<template>
    <div ref="dropdownRef" class="relative w-full">
        <!-- Input -->
        <div class="relative">
            <input v-model="searchQuery" ref="inputRef" type="text" :placeholder="placeholder"
                class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 pr-9 text-blue-950 placeholder:text-blue-950/40 focus:outline-none focus:border-[#B20600] focus:bg-white transition cursor-pointer"
                @focus="openDropdown" @input="isOpen = true" />

            <ChevronDown
                class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-blue-950/50 pointer-events-none transition-transform duration-200"
                :class="{ 'rotate-180': isOpen }" />
        </div>

        <!-- Dropdown -->
        <div v-if="isOpen"
            class="absolute z-50 left-0 right-0 mt-1.5 bg-white border border-blue-950/10 rounded-xl shadow-lg shadow-blue-950/10 overflow-hidden">
            <!-- Header -->
            <div class="px-3 py-2 border-b border-blue-950/5">
                <p class="text-[11px] font-medium text-blue-950/40 uppercase tracking-wide">
                    {{ dropdownTitle }}
                </p>
            </div>

            <!-- List -->
            <div class="max-h-48 overflow-y-auto py-1">
                <!-- Semua -->
                <button v-if="allowAll" type="button"
                    class="w-full flex items-center justify-between px-3 py-2.5 text-left text-sm transition-colors duration-150"
                    :class="!modelValue
                            ? 'bg-[#B20600]/5 text-[#B20600]'
                            : 'text-blue-950/80 hover:bg-blue-950/[0.03]'
                        " @mousedown.prevent="selectOption(null)">
                    <span>{{ allLabel }}</span>

                    <span v-if="!modelValue"
                        class="w-5 h-5 flex items-center justify-center rounded-full bg-[#B20600] text-white text-[10px]">
                        ✓
                    </span>
                </button>

                <!-- Options -->
                <button v-for="option in filteredOptions" :key="option[valueKey]" type="button"
                    class="w-full flex items-center justify-between px-3 py-2.5 text-left text-sm transition-colors duration-150"
                    :class="String(modelValue) === String(option[valueKey])
                            ? 'bg-[#B20600]/5 text-[#B20600]'
                            : 'text-blue-950/80 hover:bg-blue-950/[0.03]'
                        " @mousedown.prevent="selectOption(option)">
                    <span>{{ option[labelKey] }}</span>

                    <span v-if="String(modelValue) === String(option[valueKey])"
                        class="w-5 h-5 flex items-center justify-center rounded-full bg-[#B20600] text-white text-[10px]">
                        ✓
                    </span>
                </button>

                <!-- Empty -->
                <div v-if="filteredOptions.length === 0" class="px-3 py-6 text-center">
                    <p class="text-xs text-blue-950/40">
                        {{ emptyText }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onBeforeUnmount } from "vue";
import { ChevronDown } from "lucide-vue-next";

const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: "",
    },

    options: {
        type: Array,
        default: () => [],
    },

    labelKey: {
        type: String,
        default: "name",
    },

    valueKey: {
        type: String,
        default: "id",
    },

    placeholder: {
        type: String,
        default: "Pilih data",
    },

    dropdownTitle: {
        type: String,
        default: "Pilih data",
    },

    allowAll: {
        type: Boolean,
        default: false,
    },

    allLabel: {
        type: String,
        default: "Semua",
    },

    emptyText: {
        type: String,
        default: "Data tidak ditemukan.",
    },
});

const emit = defineEmits(["update:modelValue"]);

const dropdownRef = ref(null);
const inputRef = ref(null);

const isOpen = ref(false);
const searchQuery = ref("");

const filteredOptions = computed(() => {
    const query = searchQuery.value.trim().toLowerCase();

    if (!query) {
        return props.options;
    }

    return props.options.filter((option) =>
        String(option[props.labelKey] ?? "")
            .toLowerCase()
            .includes(query),
    );
});

const selectedOption = computed(() => {
    return props.options.find(
        (option) => String(option[props.valueKey]) === String(props.modelValue),
    );
});

watch(
    [() => props.modelValue, () => props.options],
    ([value, options]) => {
        if (
            value === null ||
            value === undefined ||
            value === ""
        ) {
            searchQuery.value = "";
            return;
        }

        const selected = options.find(
            (option) =>
                String(option[props.valueKey]) === String(value)
        );

        searchQuery.value = selected?.[props.labelKey] ?? "";
    },
    {
        immediate: true,
        deep: true
    }
);

const openDropdown = () => {
    isOpen.value = true;

    // Saat dropdown dibuka kembali,
    // tampilkan semua pilihan.
    searchQuery.value = "";
};

const selectOption = (option) => {
    if (!option) {
        emit("update:modelValue", "");
        searchQuery.value = "";
    } else {
        emit("update:modelValue", option[props.valueKey]);

        searchQuery.value = option[props.labelKey];
    }

    isOpen.value = false;

    // Lepaskan focus setelah memilih
    inputRef.value?.blur();
};

const handleClickOutside = (event) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        isOpen.value = false;

        if (!props.modelValue) {
            searchQuery.value = "";
            return;
        }

        searchQuery.value = selectedOption.value?.[props.labelKey] ?? "";
    }
};

onMounted(() => {
    document.addEventListener("mousedown", handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener("mousedown", handleClickOutside);
});
</script>
