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
                    <span v-if="hasUnread"
                        class="absolute top-2 right-2 w-2 h-2 bg-[#B20600] rounded-full ring-2 ring-white"></span>
                </button>

                <!-- Notification Popover -->
                <div v-if="isNotificationOpen"
                    class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50 transition-all">
                    <div class="px-4 py-2 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-blue-950">Notifikasi</h3>
                        <button @click="markAllAsRead"
                            class="text-[11px] text-[#B20600] font-semibold hover:underline cursor-pointer">
                            Tandai Dibaca
                        </button>
                    </div>

                    <div class="max-h-72 overflow-y-auto divide-y divide-gray-50">
                        <div v-for="notif in notifications" :key="notif.id"
                            class="p-3.5 hover:bg-gray-50 transition cursor-pointer flex gap-3">
                            <div class="w-2 h-2 rounded-full bg-[#B20600] mt-1.5 shrink-0" v-if="!notif.read"></div>
                            <div :class="{ 'pl-3.5': notif.read }" class="flex-1">
                                <p class="text-xs font-semibold text-blue-950">{{ notif.title }}</p>
                                <p class="text-xs text-blue-950/60 mt-0.5">{{ notif.message }}</p>
                                <span class="text-[10px] text-blue-950/40 mt-1 block">{{ notif.time }}</span>
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
                    <div
                        class="w-8 h-8 rounded-lg bg-[#B20600] text-white font-bold flex items-center justify-center text-xs">
                        IT
                    </div>
                    <ChevronDown :size="16" class="text-blue-950/50 transition-transform duration-200"
                        :class="{ 'rotate-180': isProfileOpen }" />
                </button>

                <!-- Profile Popover -->
                <div v-if="isProfileOpen"
                    class="absolute right-0 top-12 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 p-2 z-50 transition-all">
                    <div class="px-3 py-2 border-b border-gray-100 mb-1">
                        <p class="text-xs font-bold text-blue-950">Tim IT RSU</p>
                        <p class="text-[11px] text-blue-950/50 truncate">it@wiradadihusada.co.id</p>
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
import { ref, onMounted, onUnmounted } from 'vue';
import {
    Menu,
    Bell,
    ChevronDown,
    LogOut,
    User,
    Settings,
    Clock,
    MapPin
} from 'lucide-vue-next';

defineEmits(['toggle-sidebar']);

// Real-time Clock Logic
const currentTime = ref('');
const currentDate = ref('');
let timer = null;

const updateDateTime = () => {
    const now = new Date();

    currentTime.value = `${now.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false
    }).replace(/\./g, ':')} WIB`;

    currentDate.value = now.toLocaleDateString('id-ID', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};

// Dropdown Control
const isNotificationOpen = ref(false);
const isProfileOpen = ref(false);
const notificationRef = ref(null);
const profileRef = ref(null);

const hasUnread = ref(true);
const notifications = ref([
    { id: 1, title: 'Desain Baru Diunggah', message: 'Desain Banner Pamflet 17 Agustus telah ditambahkan', time: '10 menit lalu', read: false },
    { id: 2, title: 'Revisi Selesai', message: 'Template Resep Dokter V2 disetujui', time: '1 jam lalu', read: false },
]);

const toggleNotification = () => {
    isNotificationOpen.value = !isNotificationOpen.value;
    if (isNotificationOpen.value) isProfileOpen.value = false;
};

const toggleProfile = () => {
    isProfileOpen.value = !isProfileOpen.value;
    if (isProfileOpen.value) isNotificationOpen.value = false;
};

const markAllAsRead = () => {
    notifications.value.forEach(n => n.read = true);
    hasUnread.value = false;
};

const handleLogout = () => {
    console.log('Logging out from Header dropdown...');
};

const handleClickOutside = (event) => {
    if (notificationRef.value && !notificationRef.value.contains(event.target)) {
        isNotificationOpen.value = false;
    }
    if (profileRef.value && !profileRef.value.contains(event.target)) {
        isProfileOpen.value = false;
    }
};

onMounted(() => {
    updateDateTime();
    timer = setInterval(updateDateTime, 1000);
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    if (timer) clearInterval(timer);
    document.removeEventListener('click', handleClickOutside);
});
</script>
