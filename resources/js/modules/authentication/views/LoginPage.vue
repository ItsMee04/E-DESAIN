<template>
    <div class="min-h-screen w-full flex items-center justify-center bg-gray-50 p-4 font-sans antialiased">
        <div class="w-full max-w-md p-8 bg-white rounded-2xl shadow-xl border border-gray-100">

            <!-- Header & Logo -->
            <div class="mb-10 text-center flex flex-col items-center">
                <div class="w-20 h-20 mb-5 flex items-center justify-center p-1">
                    <img src="/public/assets/img/logo.png" alt="Logo RSU Wiradadi Husada" class="w-full h-full object-contain" />
                </div>
                <div class="flex flex-col items-center gap-1">
                    <span class="text-xs font-semibold uppercase tracking-widest text-[#B20600]">RSU Wiradadi
                        Husada</span>
                    <div class="flex items-center gap-2">
                        <Palette :size="28" class="text-blue-950" />
                        <h1 class="text-4xl font-extrabold tracking-tighter text-blue-950">E-Desain</h1>
                    </div>
                    <p class="mt-2 text-sm text-blue-950/70 max-w-xs">Portal aplikasi pembuatan desain grafis internal
                        Rumah Sakit</p>
                </div>
            </div>

            <!-- Form Login -->
            <form class="space-y-6" @submit.prevent>
                <div class="space-y-1.5">
                    <label for="email" class="block text-sm font-medium text-blue-950">Email Resmi</label>
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-blue-950/40">
                            <Mail :size="20" />
                        </div>
                        <input id="email" type="email" placeholder="nama@wiradadihusada.co.id"
                            class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#B20600] focus:border-[#B20600] transition-all text-blue-950 placeholder-blue-950/40 outline-none" />
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label for="password" class="block text-sm font-medium text-blue-950">Kata Sandi</label>
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-blue-950/40">
                            <Lock :size="20" />
                        </div>
                        <input id="password" type="password" placeholder="••••••••"
                            class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#B20600] focus:border-[#B20600] transition-all text-blue-950 placeholder-blue-950/40 outline-none" />
                    </div>
                </div>

                <div class="flex items-center justify-between gap-2">
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox"
                            class="w-5 h-5 rounded-md border-gray-300 text-[#B20600] focus:ring-[#B20600] transition" />
                        <span class="text-sm text-blue-950">Ingat sesi saya</span>
                    </label>
                    <a href="#" class="text-sm font-semibold text-[#B20600] hover:underline transition">Lupa kata
                        sandi?</a>
                </div>

                <button type="submit"
                    class="w-full py-4 px-6 bg-[#B20600] hover:bg-[#8D0500] text-white font-semibold rounded-xl shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-[#B20600] focus:ring-offset-2 transition-all flex items-center justify-center gap-3 cursor-pointer text-lg">
                    <LogIn :size="22" />
                    <span>Masuk Aplikasi</span>
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-12 text-center text-xs text-blue-950/50 border-t border-gray-100 pt-6">
                &copy; {{ new Date().getFullYear() }} RSU Wiradadi Husada. Unit IT Sistem Informasi.
            </div>

        </div>
    </div>
</template>

<script setup>
import { Mail, Lock, LogIn, Palette } from 'lucide-vue-next';
</script>
