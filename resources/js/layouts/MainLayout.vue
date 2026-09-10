```vue
<template>
    <div class="min-h-screen bg-gray-50 flex font-sans antialiased text-blue-950">

        <!-- Sidebar -->
        <Sidebar :is-open="isSidebarOpen" @close-sidebar="isSidebarOpen = false" />

        <!-- Mobile Overlay -->
        <Transition name="fade">
            <div v-if="isSidebarOpen" @click="isSidebarOpen = false"
                class="fixed inset-0 z-20 bg-blue-950/30 backdrop-blur-[2px] lg:hidden"></div>
        </Transition>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- Header -->
            <Header @toggle-sidebar="isSidebarOpen = !isSidebarOpen" />

            <!-- Content -->
            <main class="flex-1 min-w-0 overflow-x-hidden overflow-y-auto">
                <div class="w-full px-3 py-4 sm:px-4 sm:py-5 lg:px-6 lg:py-6">
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

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
