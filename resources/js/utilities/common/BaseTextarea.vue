<!-- components/BaseTextarea.vue -->

<template>
    <div class="space-y-1.5">
        <!-- Label -->
        <label
            v-if="label"
            class="text-xs font-semibold text-blue-950"
        >
            {{ label }}

            <span
                v-if="required"
                class="text-rose-600"
            >
                *
            </span>
        </label>

        <!-- Textarea -->
        <textarea
            :value="modelValue"
            :placeholder="placeholder"
            :disabled="disabled"
            :readonly="readonly"
            :rows="rows"
            class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-xs text-blue-950 outline-none focus:border-[#B20600] focus:ring-2 focus:ring-blue-950/5 transition resize-none disabled:bg-gray-100 disabled:cursor-not-allowed"
            :class="{
                'border-rose-500 focus:border-rose-500 focus:ring-rose-500/5':
                    error,
            }"
            @input="handleInput"
            @blur="handleBlur"
        ></textarea>

        <!-- Error -->
        <p
            v-if="error"
            class="text-[11px] text-rose-600"
        >
            {{ error }}
        </p>
    </div>
</template>

<script setup>
const props = defineProps({
    modelValue: {
        type: String,
        default: "",
    },

    label: {
        type: String,
        default: "",
    },

    placeholder: {
        type: String,
        default: "",
    },

    error: {
        type: [String, Boolean],
        default: "",
    },

    required: {
        type: Boolean,
        default: false,
    },

    disabled: {
        type: Boolean,
        default: false,
    },

    readonly: {
        type: Boolean,
        default: false,
    },

    rows: {
        type: [String, Number],
        default: 4,
    },
});

const emit = defineEmits([
    "update:modelValue",
    "blur",
]);

const handleInput = (event) => {
    emit("update:modelValue", event.target.value);
};

const handleBlur = () => {
    emit("blur");
};
</script>
