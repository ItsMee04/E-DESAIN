<template>
    <Transition name="modal-fade">
        <div v-if="isOpen"
            class="fixed inset-0 z-50 flex items-center justify-center bg-blue-950/40 backdrop-blur-xs p-4"
            @click.self="handleClose">
            <div
                class="bg-white rounded-2xl border border-gray-100 shadow-xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col">
                <!-- Header -->
                <div class="shrink-0 flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <div class="min-w-0">
                        <h2 class="text-lg font-bold text-blue-950">
                            {{ title }}
                        </h2>

                        <p v-if="description" class="text-xs text-blue-950/60 mt-0.5">
                            {{ description }}
                        </p>
                    </div>

                    <button type="button" @click="handleClose" :disabled="isSubmitting"
                        class="shrink-0 ml-3 p-2 rounded-lg text-blue-950/50 hover:bg-gray-100 hover:text-blue-950 transition cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                        title="Tutup">
                        <X :size="18" />
                    </button>
                </div>

                <!-- Content -->
                <div class="flex-1 min-h-0 overflow-y-auto">
                    <slot />
                </div>

                <!-- Footer -->
                <div v-if="$slots.footer" class="shrink-0 px-6 py-4 border-t border-gray-100 bg-white">
                    <slot name="footer" />
                </div>
            </div>
        </div>
    </Transition>
</template>


<script setup>
import { X } from "lucide-vue-next";

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        default: "",
    },
    isSubmitting: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["close"]);

const handleClose = () => {
    if (props.isSubmitting) return;

    emit("close");
};
</script>


<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active {
    transition: opacity 0.2s ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
    opacity: 0;
}
</style>
