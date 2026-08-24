<template>
    <div class="min-h-screen bg-gray-50 flex font-sans antialiased text-blue-950">
        <!-- Sidebar Component -->
        <Sidebar :is-open="isSidebarOpen" />

        <!-- Mobile Overlay -->
        <div v-if="isSidebarOpen" @click="isSidebarOpen = false"
            class="fixed inset-0 bg-blue-950/20 backdrop-blur-xs z-20 lg:hidden"></div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Header Component -->
            <Header @toggle-sidebar="isSidebarOpen = !isSidebarOpen" />

            <!-- Content Renderer -->
            <main class="flex-1 p-6 lg:p-8 overflow-y-auto">
                <!-- Hapus max-w-7xl di sini dan gunakan w-full -->
                <div class="w-full space-y-6">
                    <router-view />
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import Sidebar from '../layouts/components/Sidebar.vue';
import Header from '../layouts/components/Header.vue';

const isSidebarOpen = ref(false);
</script>
