<template>
    <div
        class="min-h-screen w-full flex items-center justify-center bg-gray-50 p-4 font-sans antialiased"
    >
        <div
            class="w-full max-w-md p-8 bg-white rounded-2xl shadow-xl border border-gray-100"
        >
            <!-- Header & Logo -->
            <div class="mb-10 text-center flex flex-col items-center">
                <div
                    class="w-20 h-20 mb-5 flex items-center justify-center p-1"
                >
                    <img
                        src="/public/assets/img/logo.png"
                        alt="Logo RSU Wiradadi Husada"
                        class="w-full h-full object-contain"
                    />
                </div>
                <div class="flex flex-col items-center gap-1">
                    <span
                        class="text-xs font-semibold uppercase tracking-widest text-[#B20600]"
                    >
                        RSU Wiradadi Husada
                    </span>
                    <div class="flex items-center gap-2">
                        <Palette :size="28" class="text-blue-950" />
                        <h1
                            class="text-4xl font-extrabold tracking-tighter text-blue-950"
                        >
                            E-Desain
                        </h1>
                    </div>
                    <p class="mt-2 text-sm text-blue-950/70 max-w-xs">
                        Portal aplikasi pembuatan desain grafis internal Rumah
                        Sakit
                    </p>
                </div>
            </div>
            <!-- Form Login -->
            <form class="space-y-6" @submit.prevent="handleLogin">
                <!-- Username -->
                <div class="space-y-1.5">
                    <label
                        for="username"
                        class="block text-sm font-medium text-blue-950"
                    >
                        Username
                    </label>
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-blue-950/40"
                        >
                            <Mail :size="20" />
                        </div>
                        <input
                            id="username"
                            v-model="form.username"
                            type="text"
                            autocomplete="username"
                            placeholder="Masukkan username"
                            :disabled="isLoading"
                            class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#B20600] focus:border-[#B20600] transition-all text-blue-950 placeholder-blue-950/40 outline-none disabled:bg-gray-100 disabled:cursor-not-allowed"
                        />
                    </div>
                    <p v-if="errors.username" class="text-xs text-red-600 mt-1">
                        {{ errors.username }}
                    </p>
                </div>
                <!-- Password -->
                <div class="space-y-1.5">
                    <label
                        for="password"
                        class="block text-sm font-medium text-blue-950"
                    >
                        Kata Sandi
                    </label>
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-blue-950/40"
                        >
                            <Lock :size="20" />
                        </div>
                        <input
                            id="password"
                            v-model="form.password"
                            type="password"
                            autocomplete="current-password"
                            placeholder="••••••••"
                            :disabled="isLoading"
                            class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#B20600] focus:border-[#B20600] transition-all text-blue-950 placeholder-blue-950/40 outline-none disabled:bg-gray-100 disabled:cursor-not-allowed"
                        />
                    </div>
                    <p v-if="errors.password" class="text-xs text-red-600 mt-1">
                        {{ errors.password }}
                    </p>
                </div>
                <!-- General Error -->
                <div
                    v-if="errors.general"
                    class="p-3 rounded-xl bg-red-50 border border-red-200 text-sm text-red-700"
                >
                    {{ errors.general }}
                </div>
                <div class="flex items-center justify-between gap-2">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input
                            type="checkbox"
                            v-model="rememberMe"
                            :disabled="isLoading"
                            class="w-5 h-5 rounded-md border-gray-300 text-[#B20600] focus:ring-[#B20600] transition"
                        />
                        <span class="text-sm text-blue-950">
                            Ingat sesi saya
                        </span>
                    </label>
                    <a
                        href="#"
                        class="text-sm font-semibold text-[#B20600] hover:underline transition"
                    >
                        Lupa kata sandi?
                    </a>
                </div>
                <!-- Login Button -->
                <button
                    type="submit"
                    :disabled="isLoading"
                    class="w-full py-4 px-6 bg-[#B20600] hover:bg-[#8D0500] text-white font-semibold rounded-xl shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-[#B20600] focus:ring-offset-2 transition-all flex items-center justify-center gap-3 cursor-pointer text-lg disabled:opacity-70 disabled:cursor-not-allowed"
                >
                    <!-- Loading -->
                    <svg
                        v-if="isLoading"
                        class="animate-spin h-6 w-6 text-white"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                        />
                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                        />
                    </svg>
                    <LogIn v-else :size="22" />
                    <span>
                        {{ isLoading ? "Memproses..." : "Masuk Aplikasi" }}
                    </span>
                </button>
            </form>
            <!-- Footer -->
            <div
                class="mt-12 text-center text-xs text-blue-950/50 border-t border-gray-100 pt-6"
            >
                &copy; {{ new Date().getFullYear() }} RSU Wiradadi Husada. Unit
                IT Sistem Informasi.
            </div>
        </div>
    </div>
</template>
<script setup>
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import { Mail, Lock, LogIn, Palette } from "lucide-vue-next";
import { useToast } from "../../../utilities/toast/useToast";
import { useAuthentication } from "../composables/useAuthentication";
const router = useRouter();
const { login, isLoading } = useAuthentication();
const form = reactive({ username: "", password: "" });
const rememberMe = ref(false);
const errors = reactive({ username: "", password: "", general: "" });
const { toast } = useToast();
const clearErrors = () => {
    errors.username = "";
    errors.password = "";
    errors.general = "";
};
const validateForm = () => {
    clearErrors();
    let isValid = true;
    if (!form.username.trim()) {
        errors.username = "Username wajib diisi.";
        isValid = false;
    }
    if (!form.password) {
        errors.password = "Kata sandi wajib diisi.";
        isValid = false;
    }
    return isValid;
};
const handleLogin = async () => {
    if (!validateForm()) {
        return;
    }
    try {
        const response = await login({
            username: form.username,
            password: form.password,
        });
        if (response?.success) {
            await router.push("/dashboard");
        }
    } catch (error) {
        console.error("Login error:", error);
        clearErrors();
        if (error.response?.status === 401) {
            toast.error(
                error.response?.data?.message ||
                    "Username atau kata sandi salah.",
            );
            return;
        }
        if (error.response?.status === 422) {
            const validationErrors = error.response?.data?.errors || {};
            errors.username = validationErrors.username?.[0] || "";
            errors.password = validationErrors.password?.[0] || "";
            return;
        }
        toast.error(
            error.response?.data?.message ||
                "Terjadi kesalahan saat melakukan login.",
        );
    }
};
</script>
