<template>
    <header class="h-20 bg-white border-b border-gray-100 px-6 flex items-center justify-between sticky top-0 z-20">
        <!-- Left Section: Toggle & Time / Location Info -->
        <div class="flex items-center gap-4">
            <button @click="$emit('toggle-sidebar')"
                class="lg:hidden p-2 rounded-xl text-blue-950/70 hover:bg-gray-100 transition cursor-pointer">
                <Menu :size="22" />
            </button>

            <!-- Display Jam, Tanggal & Lokasi -->
            <div
                class="hidden sm:flex items-center gap-3 bg-gray-50 px-3.5 py-2 rounded-xl text-xs border border-gray-100/80">
                <div class="flex items-center gap-2">
                    <Clock :size="16" class="text-[#B20600] shrink-0" />
                    <div class="flex items-center gap-1.5">
                        <span class="font-bold text-blue-950 font-mono text-xs">{{ currentTime }}</span>
                        <span class="text-blue-950/30">•</span>
                        <span class="text-blue-950/70 font-medium hidden md:inline">{{ currentDate }}</span>
                    </div>
                </div>

                <div class="h-3.5 w-px bg-gray-200"></div>

                <div class="flex items-center gap-1.5 text-blue-950/70 font-medium">
                    <MapPin :size="15" class="text-blue-950/40 shrink-0" />
                    <span>Purwokerto, Jawa Tengah</span>
                </div>
            </div>
        </div>

        <!-- Right Section: Actions & Profile -->
        <div class="flex items-center gap-3">
            <!-- Notification Dropdown -->
            <div class="relative" ref="notificationRef">
                <button @click="toggleNotification"
                    class="relative p-2.5 text-blue-950/70 hover:bg-gray-50 rounded-xl transition cursor-pointer">
                    <Bell :size="20" />
                    <span v-if="unreadCount > 0" class="absolute -top-1 -right-1 min-w-5 h-5 px-1
           flex items-center justify-center bg-[#B20600]
           text-white text-[10px] font-bold rounded-full ring-2 ring-white">
                        {{ unreadCount > 99 ? '99+' : unreadCount }}
                    </span>
                </button>

                <!-- Notification Popover -->
                <div v-if="isNotificationOpen"
                    class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50 transition-all">
                    <div class="px-4 py-2 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-blue-950">
                            Notifikasi
                        </h3>
                        <button v-if="unreadCount > 0" @click="markAllAsRead" :disabled="isProcessing"
                            class="text-[11px] text-[#B20600] font-semibold hover:underline cursor-pointer disabled:opacity-50">
                            {{ isProcessing ? 'Memproses...' : 'Tandai Dibaca' }}
                        </button>
                    </div>

                    <div class="max-h-72 overflow-y-auto divide-y divide-gray-50">

                        <div v-if="isLoading" class="p-5 text-center text-xs text-gray-400">
                            Memuat notifikasi...
                        </div>

                        <div v-else-if="notifications.length === 0" class="p-5 text-center text-xs text-gray-400">
                            Belum ada notifikasi.
                        </div>

                        <div v-else v-for="notif in notifications" :key="notif.id"
                            @click="handleNotificationClick(notif)"
                            class="p-3.5 hover:bg-gray-50 transition cursor-pointer flex gap-3"
                            :class="{ 'bg-red-50/40': !notif.is_read }">
                            <div v-if="!notif.is_read" class="w-2 h-2 rounded-full bg-[#B20600] mt-1.5 shrink-0"></div>

                            <div :class="{ 'pl-3.5': notif.is_read }" class="flex-1">
                                <p class="text-xs font-semibold text-blue-950">
                                    {{ notif.title }}
                                </p>

                                <p class="text-xs text-blue-950/60 mt-0.5">
                                    {{ notif.message }}
                                </p>

                                <span class="text-[10px] text-blue-950/40 mt-1 block">
                                    {{ new Date(notif.created_at).toLocaleString('id-ID') }}
                                </span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="h-6 w-px bg-gray-200 mx-1"></div>

            <!-- Profile Dropdown (Tanpa tombol sign out terpisah) -->
            <div class="relative" ref="profileRef">
                <button @click="toggleProfile"
                    class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-gray-50 transition cursor-pointer">
                    <!-- Jika foto tersedia -->
                    <img v-if="user?.pegawai?.image" :src="`/storage/pegawai/image/${user.pegawai.image}`"
                        :alt="user?.pegawai?.nama || 'Foto profil'" class="w-8 h-8 rounded-lg object-cover" />

                    <!-- Jika foto tidak tersedia -->
                    <div v-else
                        class="w-8 h-8 rounded-lg bg-[#B20600] text-white font-bold flex items-center justify-center text-xs">
                        {{ user?.pegawai?.nama?.substring(0, 2).toUpperCase() || 'US' }}
                    </div>
                    <ChevronDown :size="16" class="text-blue-950/50 transition-transform duration-200"
                        :class="{ 'rotate-180': isProfileOpen }" />
                </button>

                <!-- Profile Popover -->
                <div v-if="isProfileOpen"
                    class="absolute right-0 top-12 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 p-2 z-50 transition-all">
                    <div class="px-3 py-2 border-b border-gray-100 mb-1">
                        <p class="text-xs font-bold text-blue-950">
                            {{ user?.pegawai?.nama || '-' }}
                        </p>
                        <p class="text-[11px] text-blue-950/50 truncate">
                            {{ user?.pegawai?.nip || '-' }}
                        </p>
                    </div>

                    <a href="#"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-medium text-blue-950/70 hover:bg-gray-50 hover:text-blue-950 transition">
                        <User :size="16" />
                        Profil Saya
                    </a>
                    <a href="#"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-medium text-blue-950/70 hover:bg-gray-50 hover:text-blue-950 transition">
                        <Settings :size="16" />
                        Pengaturan Akun
                    </a>

                    <div class="h-px bg-gray-100 my-1"></div>

                    <button @click="handleLogout"
                        class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-medium text-red-600 hover:bg-red-50 transition cursor-pointer">
                        <LogOut :size="16" />
                        Keluar
                    </button>
                </div>
            </div>
        </div>
    </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { useRouter } from 'vue-router';
import { authenticationService } from '../../modules/authentication/services/authenticationService';
import { useNotification } from '../composables/useNotifications';
import {
    Menu,
    Bell,
    ChevronDown,
    LogOut,
    User,
    Settings,
    Clock,
    MapPin,
} from "lucide-vue-next";

const {
    notifications,
    unreadCount,
    isLoading,
    isProcessing,
    fetchNotificationData,
    fetchNotifications,
    markAsRead,
    markAllAsRead,
} = useNotification();

defineEmits(["toggle-sidebar"]);

// Real-time Clock Logic
const currentTime = ref("");
const currentDate = ref("");
let timer = null;

const updateDateTime = () => {
    const now = new Date();

    currentTime.value = `${now
        .toLocaleTimeString("id-ID", {
            hour: "2-digit",
            minute: "2-digit",
            second: "2-digit",
            hour12: false,
        })
        .replace(/\./g, ":")} WIB`;

    currentDate.value = now.toLocaleDateString("id-ID", {
        weekday: "long",
        day: "numeric",
        month: "long",
        year: "numeric",
    });
};

// Dropdown Control
const isNotificationOpen = ref(false);
const isProfileOpen = ref(false);
const notificationRef = ref(null);
const profileRef = ref(null);
const user = ref(null);
const router = useRouter();

const hasUnread = ref(true);

const toggleNotification = async () => {
    isNotificationOpen.value = !isNotificationOpen.value;

    if (isNotificationOpen.value) {
        isProfileOpen.value = false;

        await fetchNotificationData();
    }
};

const handleNotificationClick = async (notif) => {
    try {
        await markAsRead(notif.id);
    } catch (error) {
        console.error('Gagal membaca notifikasi:', error);
    }
};

const toggleProfile = () => {
    isProfileOpen.value = !isProfileOpen.value;
    if (isProfileOpen.value) isNotificationOpen.value = false;
};

const handleLogout = async () => {
    try {
        await authenticationService.logout();
    } catch (error) {
        console.error('Gagal melakukan logout:', error);
    } finally {
        localStorage.removeItem('token');
        localStorage.removeItem('user');

        router.push('/login');
    }
};

const handleClickOutside = (event) => {
    if (
        notificationRef.value &&
        !notificationRef.value.contains(event.target)
    ) {
        isNotificationOpen.value = false;
    }
    if (profileRef.value && !profileRef.value.contains(event.target)) {
        isProfileOpen.value = false;
    }
};

const getUserData = () => {
    try {
        const storedUser = localStorage.getItem('user');

        if (storedUser) {
            user.value = JSON.parse(storedUser);
        }
    } catch (error) {
        console.error('Gagal mengambil data user:', error);
        user.value = null;
    }
};

onMounted(() => {
    getUserData();
    fetchNotificationData();

    updateDateTime();
    timer = setInterval(updateDateTime, 1000);
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    if (timer) clearInterval(timer);
    document.removeEventListener("click", handleClickOutside);
});
</script>
