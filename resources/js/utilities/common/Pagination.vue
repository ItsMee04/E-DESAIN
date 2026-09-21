<script setup>
import { computed } from "vue";
import {
    ChevronsLeft,
    ChevronLeft,
    ChevronRight,
    ChevronsRight,
} from "lucide-vue-next";

const props = defineProps({
    currentPage: {
        type: Number,
        default: 1,
    },

    totalPages: {
        type: Number,
        default: 1,
    },

    totalItems: {
        type: Number,
        default: 0,
    },

    itemsPerPage: {
        type: Number,
        default: 5,
    },
});

const emit = defineEmits(["change-page"]);

const paginationPages = computed(() => {
    const total = props.totalPages;
    const current = props.currentPage;

    if (total <= 5) {
        return Array.from(
            { length: total },
            (_, index) => index + 1
        );
    }

    let startPage = current - 2;
    let endPage = current + 2;

    // Di awal
    if (current <= 3) {
        startPage = 1;
        endPage = 5;
    }

    // Di akhir
    if (current >= total - 2) {
        startPage = total - 4;
        endPage = total;
    }

    return Array.from(
        { length: endPage - startPage + 1 },
        (_, index) => startPage + index
    );
});

const startItem = computed(() => {
    if (props.totalItems === 0) {
        return 0;
    }

    return (
        (props.currentPage - 1) *
        props.itemsPerPage +
        1
    );
});

const endItem = computed(() => {
    return Math.min(
        props.currentPage * props.itemsPerPage,
        props.totalItems
    );
});

// Pindah ke halaman tertentu
const goToPage = (page) => {
    if (
        page < 1 ||
        page > props.totalPages ||
        page === props.currentPage
    ) {
        return;
    }

    emit("change-page", page);
};

// Sebelumnya / selanjutnya
const changePage = (page) => {
    goToPage(page);
};
</script>

<template>
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-3 border-t border-gray-100">
        <!-- Info Data -->
        <div class="text-xs text-blue-950/60">
            Menampilkan
            <span class="font-bold text-blue-950">
                {{ startItem }}
            </span>
            sampai
            <span class="font-bold text-blue-950">
                {{ endItem }}
            </span>
            dari
            <span class="font-bold text-blue-950">
                {{ totalItems }}
            </span>
            data
        </div>

        <!-- Navigation -->
        <div class="flex items-center gap-1.5">

            <!-- First Page -->
            <button type="button" @click="goToPage(1)" :disabled="currentPage === 1 ||
                totalPages === 0
                " title="Halaman pertama"
                class="p-2 rounded-lg border border-gray-200 text-blue-950 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-gray-50 transition cursor-pointer">
                <ChevronsLeft :size="15" />
            </button>

            <!-- Previous -->
            <button type="button" @click="changePage(currentPage - 1)" :disabled="currentPage === 1 ||
                totalPages === 0
                " title="Halaman sebelumnya"
                class="p-2 rounded-lg border border-gray-200 text-blue-950 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-gray-50 transition cursor-pointer">
                <ChevronLeft :size="15" />
            </button>

            <!-- Page Numbers -->
            <button v-for="page in paginationPages" :key="page" type="button" @click="goToPage(page)" :class="[
                page === currentPage
                    ? 'bg-[#B20600] text-white font-bold border-[#B20600]'
                    : 'bg-white text-blue-950 hover:bg-gray-50 border-gray-200',
                'w-8 h-8 rounded-lg text-xs border flex items-center justify-center transition cursor-pointer',
            ]">
                {{ page }}
            </button>

            <!-- Next -->
            <button type="button" @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages ||
                totalPages === 0
                " title="Halaman berikutnya"
                class="p-2 rounded-lg border border-gray-200 text-blue-950 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-gray-50 transition cursor-pointer">
                <ChevronRight :size="15" />
            </button>

            <!-- Last Page -->
            <button type="button" @click="goToPage(totalPages)" :disabled="currentPage === totalPages ||
                totalPages === 0
                " title="Halaman terakhir"
                class="p-2 rounded-lg border border-gray-200 text-blue-950 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-gray-50 transition cursor-pointer">
                <ChevronsRight :size="15" />
            </button>

        </div>
    </div>
</template>
