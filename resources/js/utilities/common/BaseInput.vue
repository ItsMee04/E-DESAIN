<template>
    <div>
        <!-- Label -->
        <label v-if="label" class="block text-xs font-semibold text-blue-950 mb-2">
            {{ label }}

            <span v-if="required" class="text-rose-600">
                *
            </span>
        </label>

        <!-- Input -->
        <input :value="modelValue" :type="type" :placeholder="placeholder" :disabled="disabled" :readonly="readonly"
            class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 text-blue-950 focus:outline-none focus:border-[#B20600] focus:bg-white transition disabled:bg-gray-100 disabled:cursor-not-allowed"
            :class="{
                'border-rose-500 focus:border-rose-500 focus:bg-white':
                    error,
            }" @input="handleInput" @blur="handleBlur" />

        <!-- Error -->
        <p v-if="error" class="text-[11px] text-rose-600">
            {{ error }}
        </p>
    </div>
</template>

<script setup>
const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: "",
    },

    label: {
        type: String,
        default: "",
    },

    type: {
        type: String,
        default: "text",
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
