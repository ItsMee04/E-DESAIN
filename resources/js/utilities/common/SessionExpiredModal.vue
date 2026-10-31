<template>
    <Transition name="modal">
        <div v-if="show"
            class="fixed inset-0 z-[9999] flex items-center justify-center bg-gray-950/60 backdrop-blur-sm p-4">
            <div class="w-full max-w-md rounded-2xl bg-white p-7 shadow-2xl text-center" role="alertdialog"
                aria-modal="true" aria-labelledby="session-title" aria-describedby="session-description">
                <!-- Icon -->
                <div class="mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-full bg-amber-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-amber-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m0 3h.008M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z" />
                    </svg>
                </div>

                <!-- Content -->
                <h2 id="session-title" class="text-xl font-bold text-gray-900">
                    Sesi Login Berakhir
                </h2>

                <p id="session-description" class="mt-3 text-sm leading-relaxed text-gray-500">
                    Masa sesi login Anda telah berakhir.
                    Silakan login kembali untuk melanjutkan
                    penggunaan aplikasi E-Desain.
                </p>

                <!-- Action -->
                <button type="button" @click="handleLogin"
                    class="mt-7 w-full rounded-xl bg-[#B20600] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#8E0500] focus:outline-none focus:ring-4 focus:ring-red-100">
                    Login Kembali
                </button>
            </div>
        </div>
    </Transition>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";

const show = ref(false);

const handleSessionExpired = () => {
    show.value = true;
};

const handleLogin = () => {
    // Bersihkan data autentikasi
    localStorage.removeItem("token");
    localStorage.removeItem("user");

    // Arahkan ke halaman login dan reset state aplikasi
    window.location.replace("/login");
};

onMounted(() => {
    window.addEventListener(
        "session-expired",
        handleSessionExpired
    );
});

onUnmounted(() => {
    window.removeEventListener(
        "session-expired",
        handleSessionExpired
    );
});
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.2s ease;
}

.modal-enter-active>div,
.modal-leave-active>div {
    transition: transform 0.2s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}

.modal-enter-from>div,
.modal-leave-to>div {
    transform: translateY(12px) scale(0.98);
}
</style>
