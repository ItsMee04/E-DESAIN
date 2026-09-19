<template>
    <div>
        <label
            v-if="label"
            class="block text-xs font-semibold text-blue-950 mb-2"
        >
            {{ label }}

            <span
                v-if="required"
                class="text-rose-600"
            >
                *
            </span>
        </label>

        <div class="flex flex-wrap items-center gap-6">
            <label
                v-for="item in options"
                :key="item[valueKey]"
                class="flex items-center gap-2 text-xs text-blue-950/70 cursor-pointer"
            >
                <input
                    v-model="selectedValue"
                    type="radio"
                    :value="item[valueKey]"
                    class="w-4 h-4 accent-[#B20600] cursor-pointer"
                    @change="handleChange"
                />

                <span>
                    {{ item[labelKey] }}
                </span>
            </label>
        </div>

        <p
            v-if="error"
            class="mt-1.5 text-[11px] text-rose-600"
        >
            {{ error }}
        </p>
    </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    modelValue: {
        type: [String, Number, null],
        default: null,
    },

    options: {
        type: Array,
        default: () => [],
    },

    label: {
        type: String,
        default: "",
    },

    labelKey: {
        type: String,
        default: "name",
    },

    valueKey: {
        type: String,
        default: "id",
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

const selectedValue = computed({
    get() {
        return props.modelValue;
    },

    set(value) {
        emit("update:modelValue", value);
    },
});

const handleChange = (event) => {
    emit("change", event.target.value);
};
</script>
