<template>
    <aside
        class="fixed inset-y-0 left-0 z-30 bg-white border-r border-gray-100 flex flex-col transition-all duration-300 relative lg:static lg:translate-x-0"
        :class="[
            isOpen ? 'translate-x-0' : '-translate-x-full',
            isCollapsed ? 'lg:w-20' : 'lg:w-64',
            'w-64'
        ]">
        <!-- Floating Edge Toggle Button -->
        <button @click="isCollapsed = !isCollapsed"
            class="hidden lg:flex absolute -right-3.5 top-7 z-40 w-7 h-7 bg-white border border-gray-200 rounded-full items-center justify-center text-blue-950/60 hover:text-[#B20600] hover:border-[#B20600] shadow-sm transition cursor-pointer"
            :title="isCollapsed ? 'Perbesar Sidebar' : 'Kecilkan Sidebar'">
            <ChevronLeft v-if="!isCollapsed" :size="14" />
            <ChevronRight v-else :size="14" />
        </button>

        <!-- Header: Logo & Brand -->
        <div class="h-20 border-b border-gray-100 flex items-center transition-all duration-300"
            :class="isCollapsed ? 'justify-center px-0' : 'px-5 justify-start gap-3.5'">
            <img src="/public/assets/img/logo.png" alt="Logo RSU Wiradadi Husada"
                class="w-9 h-9 object-contain shrink-0" />

            <div v-show="!isCollapsed" class="flex flex-col truncate transition-opacity duration-200">
                <span class="text-[10px] font-semibold uppercase tracking-widest text-[#B20600]">RSU Wiradadi</span>
                <h1 class="text-xl font-extrabold tracking-tight text-blue-950">E-Desain</h1>
            </div>
        </div>

        <!-- Navigation Menu -->
        <div class="flex-1 overflow-y-auto px-3 py-6 space-y-1">
            <div v-show="!isCollapsed"
                class="px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-blue-950/40">
                Menu Utama
            </div>

            <div v-for="item in menuItems" :key="item.name">
                <!-- Single Item Menu (Tanpa Submenu) -->
                <router-link v-if="!item.children" :to="item.to" v-slot="{ isActive }">
                    <div :class="[
                        isActive
                            ? 'bg-[#B20600]/10 text-[#B20600] font-semibold'
                            : 'text-blue-950/70 hover:bg-gray-50 hover:text-blue-950 font-medium',
                        'flex items-center gap-3.5 px-3 py-3 rounded-xl text-sm transition-all',
                        isCollapsed ? 'justify-center' : ''
                    ]" :title="isCollapsed ? item.name : ''">
                        <component :is="item.icon" :size="20"
                            :class="isActive ? 'text-[#B20600]' : 'text-blue-950/50'" />
                        <span v-show="!isCollapsed" class="truncate">{{ item.name }}</span>
                    </div>
                </router-link>

                <!-- Master Menu dengan Submenu Dropdown (Smooth Accordion) -->
                <div v-else>
                    <button @click="toggleSubMenu(item.name)" :class="[
                        isSubMenuOpen(item.name) ? 'text-blue-950 bg-gray-50 font-semibold' : 'text-blue-950/70 hover:bg-gray-50 hover:text-blue-950 font-medium',
                        'w-full flex items-center gap-3.5 px-3 py-3 rounded-xl text-sm transition-all cursor-pointer',
                        isCollapsed ? 'justify-center' : 'justify-between'
                    ]" :title="isCollapsed ? item.name : ''">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <component :is="item.icon" :size="20" class="text-blue-950/50 shrink-0" />
                            <span v-show="!isCollapsed" class="truncate">{{ item.name }}</span>
                        </div>

                        <ChevronDown v-show="!isCollapsed" :size="16"
                            class="text-blue-950/40 transition-transform duration-300 shrink-0"
                            :class="{ 'rotate-180': isSubMenuOpen(item.name) }" />
                    </button>

                    <!-- List Submenu dengan Animasi Collapse Smooth (JavaScript Hooks) -->
                    <transition @enter="enter" @leave="leave">
                        <div v-show="!isCollapsed && isSubMenuOpen(item.name)"
                            class="overflow-hidden transition-all duration-300 ease-in-out">
                            <div class="mt-1 ml-4 pl-3.5 border-l-2 border-gray-100 space-y-1 py-1">
                                <router-link v-for="sub in item.children" :key="sub.name" :to="sub.to"
                                    v-slot="{ isActive }">
                                    <div :class="[
                                        isActive
                                            ? 'text-[#B20600] font-semibold bg-[#B20600]/5'
                                            : 'text-blue-950/60 hover:text-blue-950 hover:bg-gray-50 font-medium',
                                        'px-3 py-2 rounded-lg text-xs transition-all flex items-center gap-2'
                                    ]">
                                        <span class="w-1.5 h-1.5 rounded-full"
                                            :class="isActive ? 'bg-[#B20600]' : 'bg-gray-300'"></span>
                                        <span class="truncate">{{ sub.name }}</span>
                                    </div>
                                </router-link>
                            </div>
                        </div>
                    </transition>
                </div>
            </div>
        </div>

        <!-- Footer Profile Info & Logout Button -->
        <div class="p-3 border-t border-gray-100">
            <div :class="[
                'flex items-center gap-2 p-2 rounded-xl bg-gray-50',
                isCollapsed ? 'justify-center' : 'justify-between'
            ]">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div
                        class="w-8 h-8 rounded-lg bg-[#B20600] text-white font-bold flex items-center justify-center text-xs shadow-sm shrink-0">
                        AD
                    </div>
                    <div v-show="!isCollapsed" class="flex flex-col min-w-0">
                        <span class="text-xs font-bold text-blue-950 truncate">Administrator</span>
                        <span class="text-[10px] text-blue-950/50 truncate">it@wiradadihusada.co.id</span>
                    </div>
                </div>

                <button v-show="!isCollapsed" @click="handleLogout"
                    class="p-1.5 text-blue-950/40 hover:text-red-600 hover:bg-red-50 rounded-lg transition cursor-pointer shrink-0"
                    title="Keluar">
                    <LogOut :size="16" />
                </button>
            </div>
        </div>
    </aside>
</template>

<script setup>
import { ref } from 'vue';
import {
    LayoutDashboard,
    Palette,
    FolderKanban,
    Users,
    FileText,
    Settings,
    ChevronLeft,
    ChevronRight,
    ChevronDown,
    Database,
    LogOut,
} from 'lucide-vue-next';

defineProps({
    isOpen: Boolean
});

const isCollapsed = ref(false);

const openSubMenus = ref(['Data Master']);

const menuItems = [
    { name: 'Dashboard', to: '/dashboard', icon: LayoutDashboard },
    {
        name: 'Data Master',
        icon: Database,
        children: [
            { name: 'Profesi', to: '/master/profesi' },
            { name: 'Jenis Kelamin', to: '/master/jeniskelamin' },
            { name: 'Agama', to: '/master/agama' },
            { name: 'Jenis Media', to: '/master/jenismedia' },
        ]
    },
    {
        name: 'Management User',
        icon: Users,
        children: [
            { name: 'Pegawai', to: '/master/pegawai' },
            { name: 'Pengguna', to: '/master/users' }
        ]
    },
    { name: 'Kanvas Desain', to: '/kanvas', icon: Palette },
    { name: 'Proyek Saya', to: '/proyek', icon: FolderKanban },
    { name: 'Template Resep', to: '/template', icon: FileText },
    { name: 'Pengguna', to: '/pengguna', icon: Users },
    { name: 'Pengaturan', to: '/pengaturan', icon: Settings }
];

const toggleSubMenu = (menuName) => {
    if (openSubMenus.value.includes(menuName)) {
        openSubMenus.value = openSubMenus.value.filter(name => name !== menuName);
    } else {
        openSubMenus.value.push(menuName);
    }
};

const isSubMenuOpen = (menuName) => {
    return openSubMenus.value.includes(menuName);
};

// Fungsi JavaScript Hooks untuk Animasi Buka-Tutup (Accordion) yang Smooth
const enter = (element) => {
    const width = getComputedStyle(element).width;
    element.style.width = width;
    element.style.position = 'absolute';
    element.style.visibility = 'hidden';
    element.style.height = 'auto';

    const height = element.offsetHeight;

    element.style.width = null;
    element.style.position = null;
    element.style.visibility = null;
    element.style.height = 0;

    // Paksa reflow
    getComputedStyle(element).height;

    setTimeout(() => {
        element.style.height = `${height}px`;
    });
};

const leave = (element) => {
    const height = element.offsetHeight;
    element.style.height = `${height}px`;

    // Paksa reflow
    getComputedStyle(element).height;

    setTimeout(() => {
        element.style.height = 0;
    });
};

const handleLogout = () => {
    console.log('Logging out from Sidebar...');
};
</script>
